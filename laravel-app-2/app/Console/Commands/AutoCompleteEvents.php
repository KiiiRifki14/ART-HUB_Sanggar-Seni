<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use App\Models\Booking;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AutoCompleteEvents extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'events:auto-complete';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Otomatis menandai event yang sudah lewat tanggal pelaksanaannya sebagai Selesai (completed).';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Mencari event yang sudah lewat untuk ditandai sebagai Selesai...');

        // Cari event yang tanggalnya sudah lewat dan belum completed/cancelled
        $events = Event::with('booking')
            ->where('event_date', '<', Carbon::today())
            ->whereNotIn('status', ['completed', 'cancelled'])
            ->get();

        if ($events->isEmpty()) {
            $this->info('Tidak ada event yang perlu diupdate.');
            return 0;
        }

        DB::beginTransaction();
        try {
            $count = 0;
            foreach ($events as $event) {
                /** @var \App\Models\Event $event */
                $event->update(['status' => 'completed']);
                
                if ($event->booking && !in_array($event->booking->status, ['completed', 'cancelled'])) {
                    /** @var \App\Models\Booking $booking */
                    $booking = $event->booking;
                    $booking->update(['status' => 'completed']);
                }
                
                $count++;
            }
            DB::commit();
            $this->info("Berhasil mengupdate {$count} event menjadi Selesai.");
            return 0;
        } catch (\Exception $e) {
            DB::rollBack();
            $this->error('Gagal mengupdate event: ' . $e->getMessage());
            return 1;
        }
    }
}
