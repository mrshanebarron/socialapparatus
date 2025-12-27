<?php

namespace App\Livewire\Installer;

use Livewire\Component;

class Complete extends Component
{
    public function render()
    {
        return view('livewire.installer.complete')
            ->layout('components.layouts.installer');
    }
}
