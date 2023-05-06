<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Api\v1\Enums\UserRoles;
use App\Models\Role;
use App\Models\User;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Users extends Component implements HasForms
{
    use InteractsWithForms;

    public User $model;

    public $module = 'users';
    public $type;

    protected function getFormModel(): User
    {
        return $this->model;
    }

    public function mount(): void
    {
        $this->form->fill($this->getFormModel()->attributesToArray());
    }

    public static function getFormSchema($isHidden = false): array
    {
        $type = request()->route()->parameter('type');
        return [
            Grid::make(3)->schema([
                Group::make()
                    ->schema([
                        TextInput::make('email')
                            ->email()
                            ->unique(ignoreRecord: true)
                            ->required()
                            ->maxLength(255)
                            ->label(__('admin_labels.email'))
                            ->autocomplete('off'),

                       TextInput::make('first_name')
                            ->required()
                            ->maxLength(255)
                            ->label(__('admin_labels.first_name')),

                       TextInput::make('last_name')
                            ->required()
                            ->maxLength(255)
                            ->label(__('admin_labels.last_name')),

                        Select::make('teacher_id')
                            ->id('teacher_id')
                            ->relationship(
                                'teacher',
                                'teacher_full_name',
                                function (Builder $query) {
                                    return $query->whereRole(UserRoles::TEACHER->value)
                                        ->select([
                                            'id',
                                            'first_name',
                                            'last_name',
                                            \DB::raw("CONCAT(`first_name`, ' ', `last_name`) as `teacher_full_name`")
                                        ]);
                                }
                            )
                            ->searchable(['first_name', 'last_name'])
                            ->preload()
                            ->label(__('admin_labels.teacher'))
                            ->hidden($type != UserRoles::STUDENT->value),

                        Select::make('group_id')
                            ->id('group_id')
                            ->relationship(
                                'group',
                                'title',
                                function (Builder $query) {
                                    return $query->joinTranslations()
                                        ->select([
                                            'groups.id',
                                            'group_translations.title as title'
                                        ]);
                                }
                            )
                            ->searchable(['title'])
                            ->preload()
                            ->reactive()
                            ->label(__('admin_labels.group'))
                            ->hidden($type != UserRoles::STUDENT->value),

                        Section::make(__('admin_labels.roles'))
                            ->schema([
                                Select::make('role_id')
                                    ->options(Role::joinTranslations()->select('roles.id as id', 'role_translations.title as title')->pluck('title', 'id')->toArray())
                                    ->preload()
                                     ->required()
                                    ->label(__('admin_labels.roles'))
                                    ->afterStateHydrated(function (Select $component, $state) {
                                        if (empty($state)) {
                                            $state = Role::where('slug', $type)->first()?->id;
                                        }
                                        $component->state($state);
                                    }),
                            ])
                            ->collapsible(),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make(__('admin_labels.password'))
                            ->schema([
                                TextInput::make('password')
                                    ->dehydrateStateUsing(fn ($state) => Hash::make($state))
                                    ->dehydrated(fn ($state) => filled($state))
                                    ->maxLength(255)
                                    ->disableAutocomplete()
                                    ->label(__('admin_labels.password'))
                                    ->autocomplete('off'),
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
        if (! $model->id) {
            $this->validate(['password' => 'required']);
        }

        $model->fill($this->form->getState());
        $model->save();

        if ($model->wasRecentlyCreated) {
            $this->form->saveRelationships();
        }

//        $this->dispatchBrowserEvent('toastr-notify', [
//            'type' => 'success',
//            'title' => __('admin_labels.successfully_saved'),
//        ]);


        $type = collect($this->form->getState()['role_id']);
        $type = Role::where('id', $type)->first()?->slug ?? $this->type;

        if ($model->wasRecentlyCreated) {
            toastr()->success(__('admin_labels.successfully_saved'));

            $route = route('admin.' . $this->module . '.edit', ['user' => $model, 'type' => $type]);

            return redirect($route);
        }

        toastr()->success(__('admin_labels.successfully_saved'));
        return redirect()->route('admin.users.edit', ['user' => $model, 'type' => $type]);
    }

    public function render(): string
    {
        return <<<blade
            <div>
                <form wire:submit.prevent="submit" autocomplete="do-not-autofill">
                    {{ \$this->form }}
                </form>
            </div>
        blade;
    }
}
