<?php

namespace App\Http\Livewire\Traits\Form;

trait Submitable
{
    public function submit()
    {
        $return_to_index = !is_numeric($this->model->id);

        $this->model->fill($this->form->getState());
        $this->model->save();

        $this->dispatchBrowserEvent('toastr-notify', [
            'type' => 'success',
            'title' => __('admin_labels.successfully_saved'),
        ]);

        if ($return_to_index) {
            toastr()->success(__('admin_labels.successfully_saved'));

            $route = isset($this->module)
                ? route('admin.' . $this->module . '.edit', $this->model)
                : route('admin.home');

            return redirect($route);
        }
    }
}
