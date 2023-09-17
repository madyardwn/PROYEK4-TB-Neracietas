<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Event;
use Carbon\Carbon;

class UpdateEventsStatus extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:update-events-status';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // get all events
        $events = Event::query()
            ->select(
                [
                    'events.id',
                    'events.date',
                    'events.time'
                ]
            )
            ->where('events.is_active', 1)
            ->get();

        // update status
        foreach ($events as $event) {
            $date = Carbon::parse($event->date . ' ' . $event->time);
            $now = Carbon::now();

            if ($date->lt($now)) {
                $event->is_active = 0;
                $event->save();
            }
        }

        $this->info('Events status updated.');
    }
}
