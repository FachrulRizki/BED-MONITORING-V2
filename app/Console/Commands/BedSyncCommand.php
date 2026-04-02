<?php

namespace App\Console\Commands;

use App\Services\BedSyncService;
use Illuminate\Console\Command;

class BedSyncCommand extends Command
{
    protected $signature = 'bed:sync-mjkn';

    protected $description = 'Sync bed availability from MJKN API';

    public function handle(BedSyncService $service): void
    {
        $service->sync();
    }
}
