<?php

namespace App\Console\Commands;

use App\Models\Attendance;
use App\Models\TaskAttendance;
use Illuminate\Console\Command;

class StopTimers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'timers:stop';

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
        $taskTimers = TaskAttendance::where('check_out', null)->get();

        foreach ($taskTimers as $tTimers) {
            $tTimers->update([
                'check_out' => now(),
            ]);
        }

        $timers = Attendance::where('check_out', null)->get();

        foreach ($timers as $timer) {
            $timer->update([
                'check_out' => now(),
            ]);
        }
    }
}
