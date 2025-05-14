<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\PesanSematan;
use Carbon\Carbon;

class CleanupExpiredPins extends Command
{
    protected $signature = 'cleanup:expired-pins';
    protected $description = 'Clean up expired pin messages in FAQ';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $this->info('Cleaning up expired pins...');
        
        $count = PesanSematan::where('durasi_sematan', '<', Carbon::now())
                            ->update(['aktif' => false]);
        
        $this->info("Deactivated {$count} expired pins.");
        
        return 0;
    }
}