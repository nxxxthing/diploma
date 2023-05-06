<?php

declare(strict_types=1);

namespace App\Http\Livewire\Traits\Form;

trait Renderable
{
    public function render(): string
    {
        return <<<blade
            <div id="test">
                <form wire:submit.prevent="submit">
                    {{ \$this->form }}
                </form>
                {{ \$this->modal }}

            </div>
        blade;
    }
}
