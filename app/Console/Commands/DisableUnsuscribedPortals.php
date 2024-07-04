<?php

namespace App\Console\Commands;

use App\Models\Portal;
use Illuminate\Console\Command;

class DisableUnsuscribedPortals extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:disable_unsuscribed';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable/Enable all unsuscribed portals';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $portals = Portal::whereDate('subscription_ends_at', '<', now())->get();

            foreach ($portals as $portal) {
                $portal->active = 0;
                $portal->save();
            }
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }
    }
}
