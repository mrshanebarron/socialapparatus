<?php

namespace App\Livewire\Installer;

use App\Services\InstallerService;
use Livewire\Component;

class Database extends Component
{
    public string $driver = 'sqlite';
    public string $host = '127.0.0.1';
    public string $port = '3306';
    public string $database = 'socialapparatus';
    public string $username = 'root';
    public string $password = '';

    public bool $tested = false;
    public bool $testPassed = false;
    public string $testMessage = '';
    public bool $migrating = false;
    public bool $migrated = false;

    protected $rules = [
        'driver' => 'required|in:sqlite,mysql',
        'host' => 'required_if:driver,mysql',
        'port' => 'required_if:driver,mysql',
        'database' => 'required_if:driver,mysql',
        'username' => 'required_if:driver,mysql',
    ];

    public function testConnection()
    {
        $this->validate();

        $installer = app(InstallerService::class);

        $config = [
            'host' => $this->host,
            'port' => $this->port,
            'database' => $this->database,
            'username' => $this->username,
            'password' => $this->password,
        ];

        $result = $installer->testDatabaseConnection($this->driver, $config);

        $this->tested = true;
        $this->testPassed = $result['success'];
        $this->testMessage = $result['message'];
    }

    public function configureAndMigrate()
    {
        // Always test connection first
        $this->testConnection();
        if (!$this->testPassed) {
            return;
        }

        $this->migrating = true;
        $installer = app(InstallerService::class);

        $config = [
            'host' => $this->host,
            'port' => $this->port,
            'database' => $this->database,
            'username' => $this->username,
            'password' => $this->password,
        ];

        // Update .env file
        $installer->configureDatabaseEnv($this->driver, $config);

        // Clear config cache and reload
        \Artisan::call('config:clear');

        // Reconnect with new settings
        config(['database.default' => $this->driver]);
        if ($this->driver === 'mysql') {
            config(['database.connections.mysql.host' => $this->host]);
            config(['database.connections.mysql.port' => $this->port]);
            config(['database.connections.mysql.database' => $this->database]);
            config(['database.connections.mysql.username' => $this->username]);
            config(['database.connections.mysql.password' => $this->password]);
        }

        \DB::purge();
        \DB::reconnect();

        // Run migrations
        $result = $installer->runMigrations();

        $this->migrating = false;

        if ($result['success']) {
            $this->migrated = true;
            // Use file flag instead of session (db sessions get wiped by migrate)
            file_put_contents(storage_path('.installer_step'), 'admin');
            $this->redirect(route('install.admin'), navigate: true);
        } else {
            $this->testPassed = false;
            $this->testMessage = 'Migration failed: ' . $result['message'];
        }
    }

    public function render()
    {
        return view('livewire.installer.database')
            ->layout('components.layouts.installer');
    }
}
