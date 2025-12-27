<?php

namespace App\Livewire\Installer;

use App\Services\InstallerService;
use Livewire\Component;

class Requirements extends Component
{
    public array $requirements = [];
    public array $permissions = [];
    public bool $allPassed = false;

    public function mount(InstallerService $installer)
    {
        $this->requirements = $installer->checkRequirements();
        $this->permissions = $installer->checkPermissions();
        $this->allPassed = $installer->allRequirementsPassed();
    }

    public function refresh()
    {
        $installer = app(InstallerService::class);
        $this->requirements = $installer->checkRequirements();
        $this->permissions = $installer->checkPermissions();
        $this->allPassed = $installer->allRequirementsPassed();
    }

    public function render()
    {
        return view('livewire.installer.requirements')
            ->layout('components.layouts.installer');
    }
}
