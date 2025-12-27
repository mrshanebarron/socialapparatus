<?php

namespace App\Livewire\Installer;

use App\Services\InstallerService;
use Livewire\Component;

class Welcome extends Component
{
    public function mount()
    {
        $installer = app(InstallerService::class);
        if ($installer->isInstalled()) {
            return redirect('/');
        }
    }

    public function render()
    {
        return view('livewire.installer.welcome')
            ->layout('components.layouts.installer');
    }
}
