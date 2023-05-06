<?php

namespace App\Http\Livewire\Groups;

use App\Http\Livewire\Traits\Table\Renderable;
use App\Models\Group;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class GroupsList extends Component implements Tables\Contracts\HasTable
{
    use InteractsWithTable;
    use Renderable;

    protected function getTableQuery(): Builder
    {
        return Group::query();
    }

    protected function getTableColumns(): array
    {
        $locale = app()->getLocale();

        return [
            Tables\Columns\TextColumn::make('id'),

            TextColumn::make('title:' . $locale)
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->whereTranslationLike('title', "%{$search}%");
                })
                ->label(__('admin_labels.title')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('edit')
                ->url(fn (Group $record): string => route('admin.groups.edit', $record))
                ->button()
                ->label(__('admin_labels.edit')),

            DeleteAction::make()->button()
                ->label(__('admin_labels.delete'))
                ->modalHeading(function () {
                    return __('admin_labels.delete_record');
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
