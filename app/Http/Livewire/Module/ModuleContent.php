<?php

namespace App\Http\Livewire\Module;

use App\Http\Livewire\Traits\Form\Renderable;
use App\Http\Livewire\Traits\Translatable;
use App\Models\Module;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Tabs;
use Filament\Forms\Components\Tabs\Tab;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\View;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Livewire\Component;

class ModuleContent extends Component implements HasForms
{
    use InteractsWithForms;
    use Translatable;
    use Renderable;

    public Module $module;

    protected function getFormModel(): Module
    {
        return $this->module;
    }

    protected function getFormSchema(): array
    {
        $tabs = self::getTabs();

        return [
            Section::make(__('admin_labels.content'))->schema([
                Repeater::make('content')
                    ->relationship('content')
                    ->schema([
                        Tabs::make('Heading')
                            ->tabs($tabs)->columnSpan(12),

                        TextInput::make('video')
                            ->columnSpan(12)
                    ])
                    ->mutateRelationshipDataBeforeFillUsing(function ($data) {
                        $module_content = \App\Models\ModuleContent::find($data['id']);

                        foreach ($module_content->getTranslationsArray() as $key => $value) {
                            $data[$key] = $value;
                        }

                        return $data;
                    })
                    ->mutateRelationshipDataBeforeSaveUsing(function ($data) {
                        $data['module_id'] = $this->module->id;

                        return $data;
                    })
                    ->orderable('position')
                    ->label(__('admin_labels.content'))
                    ->createItemButtonLabel('+'),

                View::make('admin.filament.buttons.submit')
            ])->collapsible()->collapsed(),
        ];
    }

    public static function getTabs(): array
    {
        $tabs = self::getLocaleTabs([]);

        $tabs[] = Tab::make('General')
            ->schema([
                FileUpload::make('image')
                    ->label(__('admin_labels.image')),

                Toggle::make('status')
                    ->label(__('admin_labels.status')),
            ])
            ->label(__('admin_labels.general'));

        return $tabs;
    }

    public static function getLocaleTabs(array $tabs): array
    {
        foreach (config('app.locales') as $locale) {
            $tab = self::getLocalTab($locale);
            if (! $tab) {
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
                    ->label(__('admin_labels.title')),

                RichEditor::make($locale . '.text')
                    ->required($isMainLocale)
                    ->label(__('admin_labels.text')),

            ])
            ->label(__('admin_labels.locales.' . $locale));
    }

    public function submit()
    {
        $this->form->getState();

        $this->dispatchBrowserEvent('toastr-notify', [
            'type'  => 'success',
            'title' => __('admin_labels.successfully_saved'),
        ]);
    }
}
