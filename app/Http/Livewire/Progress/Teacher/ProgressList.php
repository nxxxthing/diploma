<?php

namespace App\Http\Livewire\Progress\Teacher;

use App\Api\v1\Enums\UserRoles;
use App\Http\Livewire\Traits\Table\Renderable;
use App\Models\Progress;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class ProgressList extends Component implements Tables\Contracts\HasTable
{
    use InteractsWithTable;
    use Renderable;

    protected function getTableQuery(): Builder
    {
        return Progress::query()->whereHas(
            'student',
            fn ($q) => $q->where('teacher_id', auth()->id())
        );
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('id')
                ->hidden(auth('web')->user()->role?->slug != UserRoles::ADMIN->value),

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
                ->url(fn (Progress $record): string => route('admin.teacher.progress.edit', $record))
                ->button()
                ->label(__('admin_labels.add_review')),
        ];
    }
}
