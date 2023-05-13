<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Api\v1\Enums\UserRoles;
use App\Http\Livewire\Traits\Table\Renderable;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class UserList extends Component implements Tables\Contracts\HasTable
{
    use InteractsWithTable;
    use Renderable;
    public $type;

    protected function getTableQuery(): Builder
    {
        $user = auth('web')->user();
        return match ($user->role?->slug)
        {
            UserRoles::ADMIN->value => User::query()->with('role')->whereRole($this->type),

            UserRoles::STUDENT->value => $this->getQueryForStudent($user),

            UserRoles::TEACHER->value => $this->getQueryForTeacher($user),
        };
    }

    public function getQueryForStudent($student)
    {
        $users = User::query()->with('role')->whereRole($this->type);

        return match($this->type) {
            UserRoles::STUDENT->value => $users->where('id', $student->id),

            UserRoles::TEACHER->value => $users->whereHas('students', fn($q) => $q->where('id', $student->id)),

            default => $users,
        };
    }

    public function getQueryForTeacher($teacher)
    {
        $users = User::query()->with('role')->whereRole($this->type);

        return match($this->type) {
            UserRoles::TEACHER->value => $users->where('id', $teacher->id),

            UserRoles::STUDENT->value => $users->where('teacher_id', $teacher->id),

            default => $users,
        };
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')
                ->hidden(auth('web')->user()->role?->slug != UserRoles::ADMIN->value)
                ->sortable()
                ->label(__('admin_labels.id')),

            TextColumn::make('full_name')
                ->sortable(['first_name', 'last_name'])
                ->searchable(['first_name', 'last_name'])
                ->label(__('admin_labels.name')),

            TextColumn::make('email')
                ->sortable()
                ->searchable()
                ->label(__('admin_labels.email')),

            TextColumn::make('roles')
                ->getStateUsing(function (User $record) {
                    return $record->role?->title;
                })
                ->color('primary')
                ->label(__('admin_labels.roles'))
        ];
    }

    protected function getTableActions(): array
    {
        $data =[];
        if (\Gate::allows($this->type . '_edit')) {
            $data[] = Action::make('edit')
                        ->url(fn (User $record): string => route('admin.users.edit', ['type' => $this->type, 'user' => $record]))
                        ->button()
                        ->label(__('admin_labels.edit'));
        }
        if (\Gate::allows($this->type . '_delete')) {
            $data[] = DeleteAction::make()->button()
                        ->label(__('admin_labels.delete'))
                        ->modalHeading(function () {
                            return __('admin_labels.delete_record');
                        })->hidden(fn (Model $record) => $record->id == auth('web')->id());
        }

        return $data;
    }

    protected function getTableBulkActions(): array
    {
        if (auth('web')->user()->role->slug != UserRoles::ADMIN->value) {
            return [];
        }

        return [
            BulkAction::make('delete')
                ->action(fn (Collection $records) => $records->each->delete())
                ->deselectRecordsAfterCompletion()
                ->label(__('admin_labels.delete'))
        ];
    }

    public function isTableRecordSelectable(): ?\Closure
    {
        return fn (Model $record): bool => $record->id != auth('web')->id();
    }
}
