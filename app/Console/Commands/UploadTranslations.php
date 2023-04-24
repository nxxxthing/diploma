<?php

declare(strict_types=1);

namespace App\Console\Commands;

use App\Http\Middleware\LocaleMiddleware;
use App\Imports\TranslationsImport;
use App\Models\Translation;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Revolution\Google\Sheets\Facades\Sheets;

class UploadTranslations extends BaseCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'translations:upload {locale?} {--json} {--sync}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Upload translations';

    public function handle()
    {
        $this->start();

        if ($this->option('sync')) {
            $this->syncWithGoogleSheet();
            $this->end();
            return;
        }

        $locale = $this->argument('locale');
        if (!$locale) {
            throw new \Exception('Locale required');
            return;
        }
        if ($this->option('json')) {
            $file_path = Storage::disk('public')->path($locale . '/' . config('translatable.json_path'));

            $json = json_decode(file_get_contents($file_path), true);
            $this->import(\Arr::dot($json), $locale);
        } else {
            $file_path = Storage::disk('public')->path(config('translatable.file_path'));

            Excel::import(new TranslationsImport($locale), $file_path);
        }

        $this->comment('Upload Translations '.$locale.' to site_labels success');

        $this->end();
    }

    private function import(array $data, string $locale): void
    {
        foreach ($data as $key => $value) {
            $translation = \App\Models\Translation::where('locale',$locale)
                ->where('key',$key)
                ->where('group', 'site_labels')
                ->first();

            if(!$translation)
            {
                \App\Models\Translation::create([
                    'key' => $key,
                    'locale' => $locale,
                    'group' => 'site_labels',
                    'value' => $value
                ]);
            }
            else
            {
                $translation->update(
                    [
                        'value'     => $value
                    ]
                );
            }
        }
    }

    private function syncWithGoogleSheet(): void
    {
        $sheet = Sheets::spreadsheet(config('google.spreadsheet_id'))->sheet(config('google.sheet_id'));

        $header = $sheet->get()->pull(0);

        if (!empty($locales = array_diff(LocaleMiddleware::$languages, $header))) {
            throw new \Exception('Missing locales '. implode(', ', $locales));
        }

        $values = Sheets::collection($header, $sheet->get()->except(0))->mapWithKeys(function ($item, $key) {
            $item['group'] = 'site_labels';
            return [$item['key'] => $item];
        });

        foreach (LocaleMiddleware::$languages as $locale) {
            $keys = array_keys($values->toArray());

            $dbKeys = Translation::whereIn('key', $keys)
                ->where('locale', $locale)
                ->select('key')
                ->distinct()
                ->get()
                ->pluck('key')
                ->toArray();

            $existingKeys = array_intersect($keys, $dbKeys);
            $newKeys = array_values(array_diff($keys, $existingKeys));

            // Create new translations
            $newTranslations = $values->whereIn('key', $newKeys)
                ->whereNotNull($locale)
                ->map(function ($item) use ($locale) {
                    return [
                        'key' => $item['key'],
                        'group' => $item['group'],
                        'value' => $item[$locale],
                        'locale' => $locale,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now(),
                    ];
                });

            Translation::insert($newTranslations->toArray());

            Translation::whereIn('key', $existingKeys)
                ->where('locale', $locale)
                ->get()
                ->each(function ($item) use ($values, $locale) {
                    $newValue = $values[$item->key][$locale];
                    if ($item->value != $newValue) {
                        $item->update(['new_value' => $newValue]);
                    } elseif ($item->new_value) {
                        $item->update(['new_value' => null]);
                    }
                });
        }
    }
}
