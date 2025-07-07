<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Carbon\Carbon;

class CleanFilamentExports extends Command
{
    protected $signature = 'filament:clean-exports';
    protected $description = 'Remove old Filament export files and folders';

    public function handle(): void
    {
        $basePath = storage_path('app/private/filament_exports');

        if (!File::exists($basePath)) {
            $this->info('No export files found.');
            return;
        }

        $now = now();
        $deletedFiles = 0;
        $deletedFolders = 0;

        $directories = File::directories($basePath);

        foreach ($directories as $directory) {
            $files = File::allFiles($directory);
            $allExpired = true;

            foreach ($files as $file) {
                $modified = Carbon::createFromTimestamp($file->getMTime(), config('app.timezone'));
                if ($modified->diffInDays($now) > 7) {
                    File::delete($file);
                    $deletedFiles++;
                } else {
                    $allExpired = false;
                }
            }

            // Remove a pasta se estiver vazia ou todos os arquivos foram apagados
            if ($allExpired || count(File::files($directory)) === 0) {
                File::deleteDirectory($directory);
                $deletedFolders++;
            }
        }

        $this->info("Deleted {$deletedFiles} file(s) and {$deletedFolders} folder(s).");
    }
}
