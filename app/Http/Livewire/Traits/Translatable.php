<?php

namespace App\Http\Livewire\Traits;

trait Translatable
{
    public function mount(): void
    {
        $data = $this->getFormModel()->attributesToArray();

        foreach ($this->getFormModel()->getTranslationsArray() as $key => $value) {
            $data[$key] = $value;
        }

        unset($data['id']);

        $this->form->fill($data);
    }
}
