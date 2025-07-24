<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AppDeployCommand extends Command
{
    protected $signature = 'app:deploy {--production : Deploy to production environment}';

    protected $description = 'Deploy the application to Hostinger';

    public function handle(): void
    {
        $isProduction = (bool) $this->option('production');

        $this->createHtAccessFile();
        $this->createEnvironmentFile();
        $this->generateAppKey();
        $this->linkStorage();
        $this->migrateDatabase();
        $this->optimizeApplication($isProduction);
    }

    private function createHtAccessFile(): void
    {
        $htAccessContent = file_get_contents(public_path('.htaccess'));

        if ($htAccessContent === false) {
            $this->error('Failed to read .htaccess file.');

            return;
        }

        $htAccessContent = str_replace(
            'RewriteRule ^ index.php [L]',
            'RewriteRule ^ public/index.php [L]',
            $htAccessContent
        );

        if (file_put_contents(base_path('.htaccess'), $htAccessContent) === false) {
            $this->error('Failed to write to .htaccess file.');

            return;
        }

        $this->info('Successfully created .htaccess file for Hostinger deployment!');
    }

    private function createEnvironmentFile(): void
    {
        if (file_exists(base_path('.env'))) {
            return;
        }

        $envContent = file_get_contents(base_path('.env.example'));

        if ($envContent === false) {
            $this->error('Failed to read .env.example file.');

            return;
        }

        if (file_put_contents(base_path('.env'), $envContent) === false) {
            $this->error('Failed to write to .env file.');

            return;
        }

        $this->info('Successfully created .env file!');
    }

    private function generateAppKey(): void
    {
        $this->call('key:generate');
    }

    private function linkStorage(): void
    {
        $this->call('storage:link', [
            '--force' => true,
            '--no-interaction' => true,
        ]);
    }

    private function migrateDatabase(): void
    {
        $this->call('migrate', [
            '--force' => true,
            '--no-interaction' => true,
        ]);

        $this->info('Database migrated successfully!');
    }

    private function optimizeApplication(bool $production): void
    {
        $this->call('optimize:clear');

        if ($production) {
            $this->call('optimize');
        } else {
            $this->call('icons:cache');
        }

        $this->info('Application optimized successfully!');
    }
}
