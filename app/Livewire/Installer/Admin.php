<?php

namespace App\Livewire\Installer;

use App\Services\InstallerService;
use Livewire\Component;

class Admin extends Component
{
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public bool $creating = false;
    public string $error = '';

    protected $rules = [
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'password' => 'required|string|min:8|confirmed',
    ];

    public function mount()
    {
        // Check if database was configured (use file flag)
        $step = @file_get_contents(storage_path('.installer_step'));
        if ($step !== 'admin' && $step !== 'site') {
            $this->redirect(route('install.database'), navigate: true);
        }
    }

    public function createAdmin()
    {
        $this->validate();

        $this->creating = true;
        $this->error = '';

        $installer = app(InstallerService::class);

        $result = $installer->createAdminUser([
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password,
        ]);

        $this->creating = false;

        if ($result['success']) {
            file_put_contents(storage_path('.installer_step'), 'site');
            $this->redirect(route('install.site'), navigate: true);
        } else {
            $this->error = $result['message'];
        }
    }

    public function render()
    {
        return view('livewire.installer.admin')
            ->layout('components.layouts.installer');
    }
}
