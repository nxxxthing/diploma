<?php

namespace App\Http\Livewire\Progress\Student;

use App\Http\Livewire\Traits\Table\Renderable;
use App\Models\Progress;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class ProgressList extends Component implements Tables\Contracts\HasTable
{
    use InteractsWithTable;
    use Renderable;

    protected function getTableQuery(): Builder
    {
        return Progress::query()->where('user_id', auth('web')->id());
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id'),

            TextColumn::make('title')
                ->searchable()
                ->label(__('admin_labels.title')),

            TextColumn::make('type')
                ->sortable()
                ->formatStateUsing(fn ($state) => __("admin_labels.progress_types.$state"))
                ->label(__('admin_labels.type')),

            TextColumn::make('result')
                ->sortable()
                ->formatStateUsing(fn ($state) => $state ?? __('admin_labels.not_reviewed'))
                ->label(__('admin_labels.result')),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('edit')
                ->url(fn (Progress $record): string => route('admin.students.progress.edit', $record))
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
