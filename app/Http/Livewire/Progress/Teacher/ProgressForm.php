<?php

declare(strict_types=1);

namespace App\Http\Livewire\Progress\Teacher;

use App\Enums\ProgressTypes;
use App\Http\Livewire\Cathedras\Cathedras;
use App\Http\Livewire\Traits\Form\Renderable;
use App\Http\Livewire\Traits\Form\Submitable;
use App\Http\Livewire\Traits\Translatable;
use App\Models\Progress;
use Dotenv\Util\Str;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Database\Eloquent\Builder;
use Livewire\Component;
use Livewire\TemporaryUploadedFile;

class ProgressForm extends Component implements HasForms
{
    use InteractsWithForms;
    use Renderable;
    use Submitable;

    public Progress $model;

    private $module = 'lessons';

    public function mount()
    {
        $this->form->fill(
            collect($this->getFormModel()->attributesToArray())
                ->only('result', 'comment')->toArray()
        );
    }

    protected function getFormModel(): Progress
    {
        return $this->model;
    }

    public static function getFormSchema(): array
    {
        return [
            Grid::make(3)->schema([
                Group::make()
                    ->schema([
                        Section::make(__('admin_labels.review'))
                            ->schema([
                                TextInput::make('result')
                                    ->numeric()
                                    ->minValue(0)
                                    ->maxValue(100)
                                    ->label(__('admin_labels.result'))
                                    ->required(),

                                RichEditor::make('comment')
                                    ->label(__('admin_labels.description'))
                                    ->maxLength(50000),
                            ])->collapsible(),
                    ])
                    ->columnSpan(['lg' => 3]),

                View::make('admin.filament.buttons.submit'),
            ]),
        ];
    }
    public function submit()
    {
        $model = $this->getFormModel();
        $model->fill($this->form->getState());
        $model->save();

        $this->dispatchBrowserEvent('toastr-notify', [
            'type' => 'success',
            'title' => __('admin_labels.successfully_saved'),
        ]);

//        if ($model->wasRecentlyCreated) {
//            toastr()->success(__('admin_labels.successfully_saved'));
//
//            $route = route('admin.teacher.progress.progress');
//
//            return redirect($route);
//        }
    }
}
