<?php

namespace App\Console\Commands;

use App\Services\BedSyncService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Cache;
use Throwable;

class BedSyncCommand extends Command
{
    protected $signature = 'bed:sync-mjkn
                            {--force : Jalankan tanpa lock overlap untuk kebutuhan debugging}';

    protected $description = 'Sync bed availability from Aplicare API';

    public function handle(BedSyncService $service): int
    {
        $lock = null;

        if (! $this->option('force')) {
            $lock = Cache::lock('bed-sync-mjkn-command', 300);

            if (! $lock->get()) {
                $this->warn('Sync dilewati karena proses sebelumnya masih berjalan.');

                return self::SUCCESS;
            }
        }

        try {
            $result = $service->sync();

            if ($result['ok']) {
                $this->info(sprintf(
                    '%s Total API rows: %d | Unique rooms: %d | Duplicates merged: %d | Matched: %d | Created: %d | Updated: %d',
                    $result['message'],
                    $result['total'],
                    $result['unique'],
                    $result['duplicates_merged'],
                    $result['matched'],
                    $result['created'],
                    $result['updated'],
                ));

                return self::SUCCESS;
            }

            $this->error($result['message']);

            return self::FAILURE;
        } catch (Throwable $e) {
            $this->error($e->getMessage());

            return self::FAILURE;
        } finally {
            optional($lock)->release();
        }
    }
}
