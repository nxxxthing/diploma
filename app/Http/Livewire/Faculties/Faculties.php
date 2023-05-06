<?php

declare(strict_types=1);

namespace App\Http\Livewire\Faculties;

use App\Http\Livewire\Traits\Form\Renderable;
use App\Http\Livewire\Traits\Form\Submitable;
use App\Http\Livewire\Traits\Translatable;
use App\Models\Faculty;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class Faculties extends Component implements HasForms
{
    use InteractsWithForms;
    use Translatable;
    use Renderable;
    use Submitable;

    public Faculty $model;

    private $module = 'faculties';


    protected function getFormModel(): Faculty
    {
        return $this->model;
    }

    public static function getFormSchema(bool $noActions = false): array
    {
        $tabs = self::getTabs();

        return [
            Grid::make(3)->schema([
                Group::make()
                    ->schema([
                        Tabs::make('Heading')
                            ->tabs($tabs),
                    ])
                    ->columnSpan(['lg' => 3]),

                View::make('admin.filament.buttons.submit')->hidden($noActions)
            ]),
        ];
    }

    public static function getTabs(): array
    {
        $tabs = self::getLocaleTabs([]);

        return $tabs;
    }

    public static function getLocaleTabs(array $tabs): array
    {
        foreach (config('app.locales') as $locale) {
            $tab = self::getLocalTab($locale);
            if (!$tab) {
                continue;
            }

            $tabs[] = $tab;
        }

        return $tabs;
    }

    public static function getLocalTab(string $locale): ?Tab
    {
        $isMainLocale = $locale == config('app.locale');

        return Tab::make($locale)
            ->schema([
                TextInput::make($locale . '.title')
                    ->required($isMainLocale)
                    ->reactive()
                    ->label(__('admin_labels.title')),

                TextInput::make($locale . '.short_title')
                    ->required($isMainLocale)
                    ->reactive()
                    ->label(__('admin_labels.short_title')),

            ])
            ->label(__('admin_labels.locales.' . $locale));
    }
}
