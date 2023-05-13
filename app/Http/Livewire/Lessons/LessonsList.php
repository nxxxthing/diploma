<?php

namespace App\Http\Livewire\Lessons;

use App\Api\v1\Enums\UserRoles;
use App\Http\Livewire\Traits\Table\Renderable;
use App\Models\Lesson;
use Filament\Tables;
use Filament\Tables\Actions\Action;
use Filament\Tables\Actions\BulkAction;
use Filament\Tables\Actions\DeleteAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Illuminate\Database\Eloquent\Collection;

class LessonsList extends Component implements Tables\Contracts\HasTable
{
    use InteractsWithTable;
    use Renderable;

    protected function getTableQuery(): Builder
    {
        $user = auth('web')->user();

        return match ($user->role?->slug) {
            UserRoles::ADMIN->value => Lesson::query(),

            UserRoles::STUDENT->value => Lesson::whereHas(
                'schedules',
                fn ($schedules) => $schedules->whereHas(
                    'group',
                    fn($group) => $group->whereHas(
                        'students',
                        fn($student) => $student->where('id', $user->id)
                    )
                )
            )
        };
    }

    protected function getTableColumns(): array
    {
        $locale = app()->getLocale();

        return [
            Tables\Columns\TextColumn::make('id')
                ->hidden(auth('web')->user()->role?->slug != UserRoles::ADMIN->value),

            TextColumn::make('title:' . $locale)
                ->searchable(query: function (Builder $query, string $search): Builder {
                    return $query->whereTranslationLike('title', "%{$search}%");
                })
                ->label(__('admin_labels.title')),
        ];
    }

    protected function getTableActions(): array
    {
        $data =[];
        if (\Gate::allows('lessons_edit')) {
            $data[] = Action::make('edit')
                ->url(fn (Lesson $record): string => route('admin.lessons.edit', $record))
                ->button()
                ->label(__('admin_labels.edit'));
        }
        if (\Gate::allows('lessons_delete')) {
            $data[] = DeleteAction::make()->button()
                ->label(__('admin_labels.delete'))
                ->modalHeading(function () {
                    return __('admin_labels.delete_record');
                });
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
}
