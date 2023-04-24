<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Http\Livewire\Traits\Form\Renderable;
use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use LucasGiovanny\FilamentMultiselectTwoSides\Forms\Components\Fields\MultiselectTwoSides;

class Users extends Component implements HasForms
{
    use InteractsWithForms;
    use Renderable;

    public User $model;

    public $module = 'users';

    protected function getFormModel(): User
    {
        return $this->model;
    }

    public function mount(): void
    {
        $this->form->fill($this->getFormModel()->attributesToArray());
    }

    public static function getFormSchema(): array
    {
        return [
            Grid::make(3)->schema([
                Group::make()
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255)
                            ->label(__('admin_labels.email')),

                       TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->label(__('admin_labels.name')),

                        Section::make(__('admin_labels.roles'))
                            ->schema([
                                MultiselectTwoSides::make('roles')
                                    ->relationship(
                                        'roles',
                                        'title',
                                        function (Builder $query) {
                                            return $query
                                                ->select(
                                                    'roles.id as id',
                                                    'role_translations.title as title'
                                                )
                                                ->joinTranslations();
                                        }
                                    )
                                    ->preload()
                                    ->required()
                                    ->rules(['array'])
                                    ->label(__('admin_labels.roles'))
                                    ->selectableLabel(__('admin_labels.available_roles'))
                                    ->selectedLabel(__('admin_labels.selected_roles')),
                            ])
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make(__('admin_labels.password'))
                            ->schema([
                                TextInput::make('password')
                                    ->password()
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->maxLength(255)
                                    ->disableAutocomplete()
                                    ->label(__('admin_labels.password')),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),

                View::make('admin.filament.buttons.submit')
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
}
