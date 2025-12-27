<?php

namespace App\Livewire\Installer;

use App\Services\InstallerService;
use Livewire\Component;

class Site extends Component
{
    public string $app_name = 'SocialApparatus';
    public string $app_url = '';
    public string $app_description = '';

    public bool $saving = false;

    protected $rules = [
        'app_name' => 'required|string|max:255',
        'app_url' => 'required|url',
    ];

    public function mount()
    {
        // Check if admin was created (use file flag)
        $step = @file_get_contents(storage_path('.installer_step'));
        if ($step !== 'site') {
            $this->redirect(route('install.admin'), navigate: true);
            return;
        }

        // Default to current URL
        $this->app_url = url('/');
    }

    public function finishInstallation()
    {
        $this->validate();

        $this->saving = true;

        $installer = app(InstallerService::class);

        $installer->updateSiteSettings([
            'app_name' => $this->app_name,
            'app_url' => $this->app_url,
        ]);

        $installer->markAsInstalled();

        // Clean up installer step file
        @unlink(storage_path('.installer_step'));

        $this->redirect(route('install.complete'), navigate: true);
    }

    public function render()
    {
        return view('livewire.installer.site')
            ->layout('components.layouts.installer');
    }
}
