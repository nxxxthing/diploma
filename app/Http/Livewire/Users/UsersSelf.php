<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Api\v1\Enums\UserRoles;
use App\Models\Role;
use App\Models\User;
use Closure;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class UsersSelf extends Component implements HasForms
{
    use InteractsWithForms;

    public User $model;

    public $module = 'users';

    protected function getFormModel(): User
    {
        return $this->model;
    }

    public function mount(): void
    {
        $data = $this->getFormModel()->attributesToArray();
        unset($data['id']);
        $this->form->fill($data);
    }

    public static function getFormSchema($isHidden = false): array
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

                View::make('admin.filament.buttons.submit'),
            ]),
        ];
    }


    public function submit()
    {
        $model = $this->getFormModel();

        $model->fill($this->form->getState());
        $model->save();

        $this->dispatchBrowserEvent('toastr-notify', [
            'type' => 'success',
            'title' => __('admin_labels.successfully_saved'),
        ]);
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
