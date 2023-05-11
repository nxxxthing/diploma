<?php

declare(strict_types=1);

namespace App\Http\Livewire\Schedules;

use App\Api\v1\Enums\UserRoles;
use App\Enums\Days;
use App\Enums\WeekTypes;
use App\Http\Livewire\Traits\Form\Renderable;
use App\Models\Schedule;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;

class Schedules extends Component implements HasForms
{
    use InteractsWithForms;
    use Renderable;

    public Schedule $model;

    protected function getFormModel(): Schedule
    {
        return $this->model;
    }

    public function mount(): void
    {
        $data = $this->getFormModel()->attributesToArray();
        unset($data['id']);

        $this->form->fill($data);
    }

    public static function getFormSchema(): array
    {
        return [
            Grid::make(3)->schema([
                Group::make()->schema([
                    Section::make(__('admin_labels.general'))->schema([
                        Select::make('lesson_id')
                            ->id('lesson_id')
                            ->relationship(
                                'lesson',
                                'title',
                                function (Builder $query) {
                                    return $query->joinTranslations()
                                        ->select([
                                            'lessons.id as id',
                                            'lesson_translations.title as title'
                                        ]);
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->label(__('admin_labels.lesson'))
                            ->required(),

                        Select::make('teacher_id')
                            ->id('teacher_id')
                            ->relationship(
                                'teacher',
                                'teacher_full_name',
                                function (Builder $query) {
                                    return $query->whereRole(UserRoles::TEACHER->value)
                                        ->select([
                                            'id',
                                            'first_name',
                                            'last_name',
                                            \DB::raw("CONCAT(`first_name`, ' ', `last_name`) as `teacher_full_name`")
                                        ]);
                                }
                            )
                            ->searchable()
                            ->preload()
                            ->label(__('admin_labels.teacher'))
                            ->required(),

                        Select::make('group_id')
                            ->id('group_id')
                            ->options(
                                \App\Models\Group::joinTranslations()
                                    ->select('groups.id as id', 'group_translations.title as title')
                                    ->pluck('title', 'id')
                                    ->toArray()
                            )
                            ->searchable()
                            ->preload()
                            ->reactive()
                            ->afterStateHydrated(function (Select $component, $state) {
                                if (empty($state)) {
                                    $state = request()->route()->parameter('group');
                                }
                                $state = $state?->id ?? $state;
                                $component->state($state);
                            })
                            ->disabled()
                            ->label(__('admin_labels.group'))
                            ->required()
                    ]),

                    Section::make(__('admin_labels.time'))->schema([
                        Radio::make('week')
                            ->options(WeekTypes::formOptions())
                            ->inline()
                            ->label(__('admin_labels.week'))
                            ->required(),

                        Radio::make('day')
                            ->label(__('admin_labels.day'))
                            ->options(Days::formOptions())
                            ->inline()
                            ->required(),

                        TimePicker::make('time')
                            ->label(__('admin_labels.attributes.time'))
                            ->hoursStep(1)
                            ->withoutSeconds()
                            ->required()
                    ])
                ])->columnSpan(['lg' => 3]),

                View::make('admin.filament.buttons.submit')
            ]),
        ];
    }

    public function submit()
    {
        $return_to_index = !is_numeric($this->model->id);
        $data = $this->form->getState();

        $this->model->fill($data);
        $this->model->save();

        toastr()->success(__('admin_labels.successfully_saved'));

        $route = route('admin.groups.edit', ['group' => $this->model->group_id]);

        return redirect($route);
    }
}
