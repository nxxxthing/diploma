<?php

declare(strict_types=1);

namespace App\Http\Livewire\Permissions;

use App\Http\Livewire\Traits\Form\Renderable;
use App\Http\Livewire\Traits\Form\Submitable;
use App\Http\Livewire\Traits\Translatable;
use App\Models\Permission;
use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Str;
use Livewire\Component;

class Permissions extends Component implements HasForms
{
    use InteractsWithForms;
    use Translatable;
    use Submitable;
    use Renderable;

    public Permission $model;

    public $module = 'permissions';

    protected function getFormModel(): Permission
    {
        return $this->model;
    }

    public static function getFormSchema(): array
    {
        $tabs = self::getTabs();

        return [
            Grid::make(3)->schema([
                Group::make()
                    ->schema([
                        Tabs::make('Heading')
                            ->tabs($tabs),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make(__('admin_labels.general'))
                            ->schema([
                                TextInput::make('slug')
                                    ->unique(ignoreRecord: true)
                                    ->required()
                                    ->maxLength(255)
                                    ->label(__('admin_labels.slug')),

                                Toggle::make('generate_slug')
                                    ->onColor('success')
                                    ->offColor('danger')
                                    ->reactive()
                                    ->afterStateUpdated(function (Closure $set, $get, $state) {
                                        if ($state) {
                                            $set('slug', Str::slug($get(config('app.locale') . '.title')));
                                        }
                                    })
                                    ->label(__('admin_labels.buttons.generate'))
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),

                View::make('admin.filament.buttons.submit')
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
                    ->afterStateUpdated(function (Closure $set, $get, $state) use ($isMainLocale) {
                        if ($isMainLocale && $get('generate_slug')) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->label(__('admin_labels.title')),
            ])
            ->label(__('admin_labels.locales.' . $locale));
    }
}
