<?php

namespace App\Http\Livewire\Module;

use App\Http\Livewire\Traits\Form\Renderable;
use App\Http\Livewire\Traits\Form\Submitable;
use App\Http\Livewire\Traits\Translatable;
use App\Models\Module as ModuleModel;
use Closure;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Illuminate\Support\Str;
use Livewire\Component;

class Module extends Component implements HasForms
{
    use InteractsWithForms;
    use Translatable;
    use Renderable;
    use Submitable;

    public ModuleModel $model;

    protected function getFormModel(): ModuleModel
    {
        return $this->model;
    }

    public static function getFormSchema(): array
    {
        $tabs = self::getTabs();

        return [
            Grid::make(3)->schema([
                Group::make()
                    ->schema([
                        Tabs::make('Heading')
                            ->tabs($tabs),
                    ])
                    ->columnSpan(['lg' => 2]),

                Group::make()
                    ->schema([
                        Section::make(__('admin_labels.general'))
                            ->schema([
                                TextInput::make('slug')
                                    ->required()
                                    ->disabled()
                                    ->maxLength(255)
                                    ->label(__('admin_labels.slug')),

                                Toggle::make('status')
                                    ->label(__('admin_labels.status')),

                                TextInput::make('position')
                                    ->numeric()
                                    ->required()
                                    ->label(__('admin_labels.position')),
                            ]),

                        Section::make(__('admin_labels.image'))
                            ->schema([
                                FileUpload::make('image')
                                    ->enableDownload()
                                    ->enableOpen()
                                    ->label(__('admin_labels.image')),
                            ]),
                    ])
                    ->columnSpan(['lg' => 1]),

                View::make('admin.filament.buttons.submit')
            ]),
        ];
    }

    public static function getTabs(): array
    {
        $tabs = self::getLocaleTabs([]);

        return $tabs;
    }

    public static function getLocaleTabs(array $tabs): array
    {
        foreach (config('app.locales') as $locale) {
            $tab = self::getLocalTab($locale);
            if (!$tab) {
                continue;
            }

            $tabs[] = $tab;
        }

        return $tabs;
    }

    public static function getLocalTab(string $locale): ?Tab
    {
        $isMainLocale = $locale == config('app.locale');

        return Tab::make($locale)
            ->schema([
                TextInput::make($locale . '.title')
                    ->required($isMainLocale)
                    ->reactive()
                    ->afterStateUpdated(function (Closure $set, $state) use ($isMainLocale) {
                        if ($isMainLocale) {
                            $set('slug', Str::slug($state));
                        }
                    })
                    ->label(__('admin_labels.title')),

                RichEditor::make($locale . '.description')
                    ->required($isMainLocale)
                    ->label(__('admin_labels.description')),

            ])
            ->label(__('admin_labels.locales.' . $locale));
    }
}
