<?php

declare(strict_types=1);

namespace App\Http\Livewire\Roles;

use App\Http\Livewire\Traits\Table\Renderable;
use App\Models\Role;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class RoleList extends Component implements Tables\Contracts\HasTable
{
    use InteractsWithTable;
    use Renderable;

    protected function getTableQuery(): Builder
    {
        return Role::query();
    }

    protected function getTableColumns(): array
    {
        $locale = app()->getLocale();

        return [
            Tables\Columns\TextColumn::make('id')->sortable(),

            TextColumn::make('title:' . $locale)
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->whereTranslationLike('title', "%{$search}%");
                })
                ->label(__('admin_labels.title')),

            TextColumn::make('slug')
                ->label(__('admin_labels.slug')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('edit')
                ->url(fn (Role $record): string => route('admin.roles.edit', $record))
                ->button()
                ->label(__('admin_labels.edit')),

            DeleteAction::make()->button()
                ->label(__('admin_labels.delete'))
                ->modalHeading(function () {
                    return __('admin_labels.are_you_sure_you_wont_to_delete_this_role');
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
