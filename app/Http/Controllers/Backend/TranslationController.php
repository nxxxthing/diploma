<?php

declare(strict_types=1);

namespace App\Http\Controllers\Backend;

use App\Classes\Meta;
use App\Http\Controllers\Controller;
use App\Http\Middleware\LocaleMiddleware;
use App\Http\Requests\Backend\TranslationUpdateRequest;
use App\Models\Translation;
use Google\Service\Exception;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;
use Revolution\Google\Sheets\Facades\Sheets;

class TranslationController extends Controller
{
    public $module = "translation";

    /**
     * @var array
     */
    public $locales = [];

    /**
     * TranslationController constructor.
     */
    public function __construct()
    {
        Meta::title(trans('labels.translations'));

        $this->getExistsLocales();
    }

    /**
     * @param string $group
     *
     * @return \Illuminate\Contracts\View\View
     */
    public function index($group)
    {
        abort_unless(\Gate::allows('translations_access'), 403);
        $page = request('page', 1);
        $per_page = 50;

        $list = $this->getGroupCollection($group);
        $list_for_search = $this->getGroupDBCollection($group);

        $total = $list->count();
        $list = $list->slice(($page - 1) * $per_page);
        $list_for_search = $list_for_search->slice(($page - 1) * $per_page);

        $list = new LengthAwarePaginator(
            $list,
            $total,
            $per_page,
            $page,
            [
                'path' => route('admin.' . $this->module . '.index', $group),
                'query' => [],
            ]
        );

        $list_for_search = new LengthAwarePaginator(
            $list_for_search,
            $total,
            $per_page,
            $page,
            [
                'path' => route('admin.' . $this->module . '.index', $group),
                'query' => [],
            ]
        );

        $data['locales'] = $this->locales;
        $data['list'] = ['data' => $list];
        $data['list_for_search'] = ['data' => $list_for_search];
        $data['group'] = $group;
        $data['page'] = $page;
        $data['page_title'] = trans('labels.translation_group_' . $group);
        $data['module'] = $this->module;
        $data['has_conflicts'] = Translation::whereNotNull('new_value')->exists();

        request()->flush();

        return view('admin.view.' . $this->module . '.index', $data);
    }

    /**
     * @param \App\Http\Requests\Backend\TranslationUpdateRequest $request
     *
     * @return mixed
     */
    public function update(TranslationUpdateRequest $request)
    {
        $group = $request->route('group');

        $db_translations = Translation::where(['group' => $group])
            ->get()->pluck('id', 'full_id')->toArray();

        $_translations = [];
        foreach ($this->locales as $locale) {
            $translations = $request->get($locale, []);

            foreach ($translations as $key => $value) {
                $id = $db_translations[$group . '_' . $locale . '_' . $key] ?? null;
                $_translations[] = ['id' => $id, 'locale' => $locale, 'group' => $group, 'key' => $key, 'value' => $value, 'new_value' => null];
            }

            if (Cache::getStore() instanceof \Illuminate\Cache\TaggableStore) {
                cache()->tags('translations')->forget($locale . '_' . $group);
            }
        }

        Translation::upsert($_translations, ['id']);

        toastr()->success(__('admin_labels.success.update', ['model' => ucfirst($this->module)]));

        return redirect()
            ->route(
                'admin.' . $this->module . '.index',
                ['group' => $group, 'page' => $request->input('page', 1)]
            )->with('success', trans('messages.save_ok'));
    }

    public function conflicts()
    {
        $translations = [];
        foreach (LocaleMiddleware::$languages as $locale) {
            $data = Translation::whereNotNull('new_value')->where('locale', $locale)->get();
            if ($data->isNotEmpty()) {
                $translations[$locale] = $data;
            }
        }
        if (empty($translations)) {
            return redirect()->route('admin.translation.index', ['group' => 'site_labels']);
        }
        return view('admin.view.translation.conflicts', ['translations' => $translations, 'module' => $this->module]);
    }

    public function resolveConflicts(Request $request)
    {
        $dataNew = $request->get('new', []);
        $dataOld = $request->get('old', []);

        foreach($dataNew as $locale => $values) {
            $keys = array_keys($values);
            Translation::where('locale', $locale)
                ->whereIn('key', $keys)
                ->whereNotNull('new_value')
                ->update([
                    'value' => \DB::raw('new_value'),
                    'new_value' => null,
                ]);
        }

        foreach ($dataOld as $locale => $values) {
            $keys = array_keys($values);
            Translation::where('locale', $locale)
                ->whereIn('key', $keys)
                ->update(['new_value' => null]);
        }
        toastr()->success(__('admin_labels.successfully_updated'));
        return redirect()->route('admin.translation.index', ['group' => 'site_labels']);
    }

    public function forceMergeConflicts(Request $request)
    {
        $action = $request->get('type');
        if ($action == 'new') {
            Translation::whereNotNull('new_value')
                ->update([
                    'value' => \DB::raw('new_value'),
                    'new_value' => null,
                ]);
        } elseif ($action == 'old') {
            Translation::whereNotNull('new_value')->update(['new_value' => null]);
        } else {
            toastr()->error(__('admin_labels.internal_error'));
            return redirect()->back()->withInput();
        }
        toastr()->success(__('admin_labels.successfully_updated'));
        return redirect()->route('admin.translation.index', ['group' => 'site_labels']);
    }

    /**
     * fill array of all physical exists locales
     */
    public function getExistsLocales()
    {
        $this->locales = config('app.locales');
    }

    /**
     * @param string $group
     * @param string $locale
     *
     * @return Collection
     */
    private function getGroupDBCollection($group, $locale = null)
    {
        $locales = $locale ? [$locale] : $this->locales;

        $list = [];
        foreach ($locales as $locale) {
            $path = app()->langPath() . '/' . $locale . '/' . $group . '.php';
            $_file_list = Arr::dot(include($path));

            $_translation = Translation::whereLocale($locale)->whereGroup($group)
                ->get(['key', 'value'])
                ->keyBy('key');

            foreach ($_file_list as $key => $item) {
                $list[$key][$locale] = $_translation->has($key) ? $_translation->get($key)->value : $item;
            }

            $_db_list = Arr::except($_translation->toArray(), array_keys($_file_list));

            foreach ($_db_list as $key => $item) {
                $list[$key][$locale] = $item['value'];
            }
        }
        ksort($list);

        return Collection::make($list);
    }

    /**
     * @param string $group
     * @param string $locale
     *
     * @return Collection
     */
    private function getGroupCollection($group, $locale = null)
    {
        $locales = $locale ? [$locale] : $this->locales;

        $list = [];
        foreach ($locales as $locale) {
            $path = app()->langPath() . '/' . $locale . '/' . $group . '.php';
            $_file_list = Arr::dot(include($path));

            $_translation = Translation::whereLocale($locale)->whereGroup($group)
                ->get(['key', 'value'])
                ->keyBy('key');

            foreach ($_file_list as $key => $item) {
                $array_path = explode('.', $key);
                $result = &$list;
                foreach ($array_path as $i => $k) {
                    if ($i < count($array_path) - 1) {
                        if (!isset($result[$k])) {
                            $result[$k] = [];
                        }
                        try {
                            $result = &$result[$k];
                        } catch (\Exception $e) {
                            dd($k, $result, $e->getMessage());
                        }
                    } else {
                        $result[$k] = collect([
                            $key => array_merge(
                                $result[$k][$key] ?? [],
                                [$locale => ($_translation->has($key) ? $_translation->get($key)->value : $item)]
                            )
                        ]);
                    }
                }
            }

            $_db_list = Arr::except($_translation->toArray(), array_keys($_file_list));

            foreach ($_db_list as $key => $item) {
                $array_path = explode('.', $key);
                $result_db = &$list;
                foreach ($array_path as $i => $k) {
                    if ($i < count($array_path) - 1) {
                        if (!isset($result_db[$k])) {
                            $result_db[$k] = [];
                        }
                        $result_db = &$result_db[$k];
                    } else {
                        $result_db[$k] = collect([
                            $key => array_merge(
                                $result_db[$k][$key] ?? [],
                                [$locale => ($_translation->has($key) ? $_translation->get($key)->value : $item)]
                            )
                        ]);
                    }
                }
            }
        }

        ksort($list);

        return Collection::make($list);
    }

    /**
     * @param string $locale
     * @param string $group
     *
     * @return array
     */
    private function getLocaleExistTranslationsForGroup($locale, $group)
    {
        $list = [];

        foreach ($this->getGroupDBCollection($group, $locale) as $key => $translation) {
            $list[$key] = isset($translation[$locale]) ? $translation[$locale] : '';
        }

        return $list;
    }

    public function uploadFromFile(Request $request)
    {
        $file = $request->file('file');
        $hasConflicts = false;
        try {
            if ($request->boolean('sync')) {
                Artisan::call(
                    'translations:upload',
                    [
                        '--sync' => true
                    ]
                );
                $hasConflicts = Translation::whereNotNull('new_value')->exists();
            } elseif ($file) {
                if ($request->get('json')) {
                    $locale = $request->get('locale');
                    Storage::putFileAs($locale.'/translations', new File($file->getPathname()), 'translations.json');
                    Artisan::call(
                        'translations:upload',
                        [
                            'locale' => $locale,
                            '--json' => true
                        ]
                    );
                } else {
                    Storage::putFileAs('translations', new File($file->getPathname()), 'translations.xlsx');

                    foreach (config('app.locales') as $locale) {
                        Artisan::call(
                            'translations:upload',
                            [
                                'locale' => $locale
                            ]
                        );
                    }
                }
            }
        } catch(Exception $e) {
            toastr($e->getErrors()[0]['message'], 'error');

            return redirect()->back();
        } catch (\Exception $e) {
            toastr($e->getMessage(), 'error');

            return redirect()->back();
        }

        if ($hasConflicts) {
            toastr()->warning(__('admin_labels.updated_with_conflicts'));
        } else {
            toastr(__('admin_labels.successfully_updated'));
        }

        return redirect()->back();
    }
}
