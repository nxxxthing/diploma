<?php

declare(strict_types=1);

namespace App\Http\Livewire\Traits\Table;

trait Renderable
{
    public function render(): string
    {
        return <<<blade
            <div>
                 {{ \$this->table }}
            </div>
        blade;
    }
}
