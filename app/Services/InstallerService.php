<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use App\Models\Team;

class InstallerService
{
    protected string $lockFile;

    public function __construct()
    {
        $this->lockFile = storage_path('.installed');
    }

    public function isInstalled(): bool
    {
        return File::exists($this->lockFile);
    }

    public function markAsInstalled(): void
    {
        File::put($this->lockFile, json_encode([
            'installed_at' => now()->toIso8601String(),
            'version' => config('app.version', '1.0.0'),
        ]));
    }

    public function checkRequirements(): array
    {
        $requirements = [
            'php_version' => [
                'name' => 'PHP Version',
                'required' => '8.2.0',
                'current' => PHP_VERSION,
                'passed' => version_compare(PHP_VERSION, '8.2.0', '>='),
            ],
            'pdo' => [
                'name' => 'PDO Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('pdo') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('pdo'),
            ],
            'pdo_sqlite' => [
                'name' => 'PDO SQLite Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('pdo_sqlite') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('pdo_sqlite'),
            ],
            'pdo_mysql' => [
                'name' => 'PDO MySQL Extension',
                'required' => 'Enabled (for MySQL)',
                'current' => extension_loaded('pdo_mysql') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('pdo_mysql'),
            ],
            'mbstring' => [
                'name' => 'Mbstring Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('mbstring') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('mbstring'),
            ],
            'openssl' => [
                'name' => 'OpenSSL Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('openssl') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('openssl'),
            ],
            'tokenizer' => [
                'name' => 'Tokenizer Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('tokenizer') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('tokenizer'),
            ],
            'xml' => [
                'name' => 'XML Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('xml') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('xml'),
            ],
            'ctype' => [
                'name' => 'Ctype Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('ctype') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('ctype'),
            ],
            'json' => [
                'name' => 'JSON Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('json') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('json'),
            ],
            'fileinfo' => [
                'name' => 'Fileinfo Extension',
                'required' => 'Enabled',
                'current' => extension_loaded('fileinfo') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('fileinfo'),
            ],
            'gd' => [
                'name' => 'GD Extension',
                'required' => 'Enabled (for images)',
                'current' => extension_loaded('gd') ? 'Enabled' : 'Disabled',
                'passed' => extension_loaded('gd'),
            ],
        ];

        return $requirements;
    }

    public function checkPermissions(): array
    {
        $paths = [
            storage_path() => 'storage',
            storage_path('app') => 'storage/app',
            storage_path('framework') => 'storage/framework',
            storage_path('logs') => 'storage/logs',
            base_path('bootstrap/cache') => 'bootstrap/cache',
            base_path('.env') => '.env',
        ];

        $permissions = [];
        foreach ($paths as $path => $name) {
            $permissions[$name] = [
                'name' => $name,
                'path' => $path,
                'writable' => is_writable($path),
                'passed' => is_writable($path),
            ];
        }

        return $permissions;
    }

    public function allRequirementsPassed(): bool
    {
        foreach ($this->checkRequirements() as $req) {
            // Skip MySQL check as it's optional
            if (!$req['passed'] && $req['name'] !== 'PDO MySQL Extension') {
                return false;
            }
        }

        foreach ($this->checkPermissions() as $perm) {
            if (!$perm['passed']) {
                return false;
            }
        }

        return true;
    }

    public function testDatabaseConnection(string $driver, array $config): array
    {
        try {
            if ($driver === 'sqlite') {
                $path = $config['database'] ?? database_path('database.sqlite');

                // Create file if it doesn't exist
                if (!File::exists($path)) {
                    File::put($path, '');
                }

                $pdo = new \PDO("sqlite:{$path}");
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                return ['success' => true, 'message' => 'SQLite connection successful'];
            }

            if ($driver === 'mysql') {
                $dsn = "mysql:host={$config['host']};port={$config['port']}";
                $pdo = new \PDO($dsn, $config['username'], $config['password']);
                $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

                // Try to create database if it doesn't exist
                $dbName = $config['database'];
                $pdo->exec("CREATE DATABASE IF NOT EXISTS `{$dbName}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci");

                return ['success' => true, 'message' => 'MySQL connection successful'];
            }

            return ['success' => false, 'message' => 'Unknown database driver'];
        } catch (\PDOException $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function configureDatabaseEnv(string $driver, array $config): void
    {
        $envPath = base_path('.env');
        $env = File::get($envPath);

        if ($driver === 'sqlite') {
            $env = preg_replace('/^DB_CONNECTION=.*/m', 'DB_CONNECTION=sqlite', $env);
            $env = preg_replace('/^DB_HOST=.*/m', '# DB_HOST=127.0.0.1', $env);
            $env = preg_replace('/^DB_PORT=.*/m', '# DB_PORT=3306', $env);
            $env = preg_replace('/^DB_DATABASE=.*/m', '# DB_DATABASE=laravel', $env);
            $env = preg_replace('/^DB_USERNAME=.*/m', '# DB_USERNAME=root', $env);
            $env = preg_replace('/^DB_PASSWORD=.*/m', '# DB_PASSWORD=', $env);
        } else {
            $env = preg_replace('/^DB_CONNECTION=.*/m', 'DB_CONNECTION=mysql', $env);
            $env = preg_replace('/^# ?DB_HOST=.*/m', "DB_HOST={$config['host']}", $env);
            $env = preg_replace('/^# ?DB_PORT=.*/m', "DB_PORT={$config['port']}", $env);
            $env = preg_replace('/^# ?DB_DATABASE=.*/m', "DB_DATABASE={$config['database']}", $env);
            $env = preg_replace('/^# ?DB_USERNAME=.*/m', "DB_USERNAME={$config['username']}", $env);
            $env = preg_replace('/^# ?DB_PASSWORD=.*/m', "DB_PASSWORD={$config['password']}", $env);
        }

        File::put($envPath, $env);
    }

    public function runMigrations(): array
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            return ['success' => true, 'message' => 'Migrations completed successfully'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function createAdminUser(array $data): array
    {
        try {
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'],
                'password' => Hash::make($data['password']),
                'email_verified_at' => now(),
            ]);

            // Create personal team for admin
            $team = Team::forceCreate([
                'user_id' => $user->id,
                'name' => explode(' ', $user->name, 2)[0] . "'s Team",
                'personal_team' => true,
            ]);

            $user->current_team_id = $team->id;
            $user->save();

            return ['success' => true, 'message' => 'Admin user created successfully', 'user' => $user];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    public function updateSiteSettings(array $settings): void
    {
        $envPath = base_path('.env');
        $env = File::get($envPath);

        if (isset($settings['app_name'])) {
            $appName = '"' . str_replace('"', '\\"', $settings['app_name']) . '"';
            $env = preg_replace('/^APP_NAME=.*/m', "APP_NAME={$appName}", $env);
        }

        if (isset($settings['app_url'])) {
            $env = preg_replace('/^APP_URL=.*/m', "APP_URL={$settings['app_url']}", $env);
        }

        File::put($envPath, $env);

        // Clear config cache
        Artisan::call('config:clear');
    }
}
