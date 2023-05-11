<?php

declare(strict_types=1);

namespace App\Http\Livewire\Groups;

use App\Http\Livewire\Cathedras\Cathedras;
use App\Http\Livewire\Traits\Form\Renderable;
use App\Http\Livewire\Traits\Form\Submitable;
use App\Http\Livewire\Traits\Translatable;
use App\Models\Group as GroupModel;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Group;
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

class Groups extends Component implements HasForms
{
    use InteractsWithForms;
    use Translatable;
    use Renderable;
    use Submitable;

    public GroupModel $model;

    private $module = 'groups';

    protected function getFormModel(): GroupModel
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
                        Section::make(__('admin_labels.cathedra_id'))
                            ->schema([
                                Select::make('cathedra_id')
                                    ->relationship(
                                        'cathedra',
                                        'title',
                                        function (Builder $query) {
                                            $query->joinTranslations()
                                                ->select('cathedras.id as id', 'cathedra_translations.title as title');
                                        }
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required()
                                    ->label(__('admin_labels.cathedra_id'))
                                    ->createOptionForm(fn($get) => Cathedras::getFormSchema(true)),
                            ])
                    ])
                    ->columnSpan(['lg' => 3]),

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
                    ->label(__('admin_labels.title')),

            ])
            ->label(__('admin_labels.locales.' . $locale));
    }
}
