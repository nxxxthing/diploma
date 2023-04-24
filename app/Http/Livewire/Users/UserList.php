<?php

declare(strict_types=1);

namespace App\Http\Livewire\Users;

use App\Http\Livewire\Traits\Table\Renderable;
use App\Models\User;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class UserList extends Component implements Tables\Contracts\HasTable
{
    use InteractsWithTable;
    use Renderable;

    protected function getTableQuery(): Builder
    {
        return User::query()->with('roles');
    }

    protected function getTableColumns(): array
    {
        return [
            TextColumn::make('id')
                ->sortable()
                ->label(__('admin_labels.id')),

            TextColumn::make('name')
                ->label(__('admin_labels.name')),

            TextColumn::make('email')
                ->label(__('admin_labels.email')),

            TextColumn::make('roles')
                ->getStateUsing(function (User $record) {
                    return implode(',', $record->roles->pluck('title')->toArray());
                })
                ->color('primary')
                ->label(__('admin_labels.roles'))
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('edit')
                ->url(fn (User $record): string => route('admin.users.edit', $record))
                ->button()
                ->label(__('admin_labels.edit')),

            DeleteAction::make()->button()
                ->label(__('admin_labels.delete'))
                ->modalHeading(function () {
                    return __('admin_labels.are_you_sure_you_wont_to_delete_this_user');
                })
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
}
