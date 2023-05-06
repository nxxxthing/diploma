<?php

declare(strict_types=1);

namespace App\Http\Livewire\Roles;

use LucasGiovanny\FilamentMultiselectTwoSides\Forms\Components\Fields\MultiselectTwoSides;
use App\Http\Livewire\Traits\Form\Renderable;
use App\Http\Livewire\Traits\Form\Submitable;
use App\Http\Livewire\Traits\Translatable;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Role;
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

/**
 * @property \Filament\Forms\ComponentContainer $form
 */
class Roles extends Component implements HasForms
{
    use InteractsWithForms;
    use Translatable;
    use Submitable;
    use Renderable;

    public Role $model;

    public $module = 'roles';

    protected function getFormModel(): Role
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
                                    ->label(__('admin_labels.buttons.generate')),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),

                Section::make(__('admin_labels.permissions'))
                    ->schema([
                        MultiselectTwoSides::make('permissions')
                            ->relationship(
                                'permissions',
                                'title',
                                function (Builder $query) {
                                    return $query
                                        ->select(
                                            'permissions.id as id',
                                            'permission_translations.title as title'
                                        )
                                        ->joinTranslations();
                                }
                            )
                            ->enableSearch()
                            ->preload()
                            ->rules(['array'])
                            ->label(__('admin_labels.permissions'))
                            ->selectableLabel(__('admin_labels.available_roles'))
                            ->selectedLabel(__('admin_labels.selected_roles')),
                    ])
                    ->collapsible(),

                View::make('admin.filament.buttons.submit'),
            ]),
        ];
    }

    public function submit()
    {
        $model = $this->getFormModel();

        $model->fill($this->form->getState());
        $model->save();

        if ($model->wasRecentlyCreated) {
            $this->form->saveRelationships();
        }

        $this->dispatchBrowserEvent('toastr-notify', [
            'type' => 'success',
            'title' => __('admin_labels.successfully_saved'),
        ]);

        if ($model->wasRecentlyCreated) {
            toastr()->success(__('admin_labels.successfully_saved'));

            $route = isset($this->module) ? route('admin.' . $this->module . '.edit', $model) : route('admin.home');

            return redirect($route);
        }
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
