<?php

declare(strict_types=1);

namespace App\Http\Livewire\Progress\Student;

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
        $data = $this->getFormModel()->attributesToArray();

        unset($data['id']);

        $this->form->fill($data);
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
                        Section::make(__('admin_labels.general_data'))
                            ->schema([
                                TextInput::make('title')
                                    ->maxLength(255)
                                    ->label(__('admin_labels.title')),

                                Section::make(__('admin_labels.description'))
                                    ->schema([
                                        RichEditor::make('description')
                                            ->label(__('admin_labels.description'))
                                            ->maxLength(50000)
                                    ])->collapsible(),

                                Select::make('type')
                                    ->options(ProgressTypes::formOptions())
                                    ->label(__('admin_labels.type'))
                                    ->searchable()
                                    ->preload()
                                    ->reactive()
                                    ->required()
                            ]),

                        Section::make(__('admin_labels.data'))
                            ->schema([
                                RichEditor::make('text')
                                    ->label(__('admin_labels.text'))
                                    ->maxLength(50000)
                                    ->reactive()
                                    ->hidden(fn (\Closure $get) => $get('type') != ProgressTypes::TEXT->value)
                                    ->required(fn (\Closure $get) => $get('type') == ProgressTypes::TEXT->value),

                                FileUpload::make('image')
                                    ->label(__('admin_labels.image'))
                                    ->directory('uploads/images/progress')
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                        return \Str::lower(\Str::random(2))
                                            . '/' . \Str::lower(\Str::random(2))
                                            . '/' . \Str::random(16)
                                            . '.' . $file->extension();
                                    })
                                    ->image()
                                    ->hidden(fn (\Closure $get) => $get('type') != ProgressTypes::IMAGE->value)
                                    ->required(fn (\Closure $get) => $get('type') == ProgressTypes::IMAGE->value),

                                FileUpload::make('file')
                                    ->label(__('admin_labels.file'))
                                    ->directory('uploads/files/progress')
                                    ->getUploadedFileNameForStorageUsing(function (TemporaryUploadedFile $file): string {
                                        return \Str::lower(\Str::random(2))
                                            . '/' . \Str::lower(\Str::random(2))
                                            . '/' . \Str::random(16)
                                            . '.' . $file->extension();
                                    })
                                    ->hidden(fn (\Closure $get) => $get('type') != ProgressTypes::FILE->value)
                                    ->required(fn (\Closure $get) => $get('type') == ProgressTypes::FILE->value),

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
        $data = $this->form->getState();
        $data['user_id'] = auth('web')->id();
        $model->fill($data);
        $model->save();

        $this->dispatchBrowserEvent('toastr-notify', [
            'type' => 'success',
            'title' => __('admin_labels.successfully_saved'),
        ]);

        if ($model->wasRecentlyCreated) {
            toastr()->success(__('admin_labels.successfully_saved'));

            $route = route('admin.students.progress');

            return redirect($route);
        }
    }
}
