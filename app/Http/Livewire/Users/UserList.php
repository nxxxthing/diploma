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
        return User::query()->with('role')->whereRole($this->type);
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')
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
        return [
            Action::make('edit')
                ->url(fn (User $record): string => route('admin.users.edit', ['type' => $this->type, 'user' => $record]))
                ->button()
                ->label(__('admin_labels.edit')),

            DeleteAction::make()->button()
                ->label(__('admin_labels.delete'))
                ->modalHeading(function () {
                    return __('admin_labels.delete_record');
                })->hidden(fn (Model $record) => $record->id == auth('web')->id())
        ];
    }

    protected function getTableBulkActions(): array
    {
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
