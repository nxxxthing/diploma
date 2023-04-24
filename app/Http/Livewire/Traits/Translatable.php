<?php

namespace App\Http\Livewire\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Translatable
{
    public function mount(): void
    {
        $data = $this->getFormModel()->attributesToArray();

        foreach ($this->getFormModel()->getTranslationsArray() as $key => $value) {
            $data[$key] = $value;
        }

        $this->form->fill($data);
    }
}
