<?php

namespace App\Http\Livewire\Schedules;

use App\Http\Livewire\Traits\Table\Renderable;
use App\Models\Group;
use App\Models\Schedule;
use Carbon\Carbon;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class ScheduleList extends Component implements Tables\Contracts\HasTable
{
    use InteractsWithTable;
    use Renderable;

    public Group $group;

    protected function getTableQuery(): Builder
    {
        return Schedule::query()->where('group_id', $this->group->id);
    }

    protected function getTableColumns(): array
    {
        $locale = app()->getLocale();

        return [
            Tables\Columns\TextColumn::make('id'),

            TextColumn::make('lesson.title')
                ->label(__('admin_labels.lesson')),

            TextColumn::make('week')
                ->label(__('admin_labels.week'))
                ->formatStateUsing(fn (string $state): string => __("admin_labels.week_types.$state"))
                ->sortable(),

            TextColumn::make('day')
                ->label(__('admin_labels.day'))
                ->formatStateUsing(fn (string $state): string => __("admin_labels.days.$state"))
                ->sortable(),

            TextColumn::make('time')
                ->label(__('admin_labels.time'))
                ->formatStateUsing(fn (string $state): string => Carbon::parse($state)->format('H:i'))
                ->sortable()
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Action::make('edit')
                ->url(fn (Schedule $record): string => route('admin.schedules.edit', ['group' => $record->group_id, 'schedule' => $record]))
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
