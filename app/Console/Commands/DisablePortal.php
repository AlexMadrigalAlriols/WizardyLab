<?php

namespace App\Console\Commands;

use App\Models\GlobalConfiguration;
use App\Models\Invoice;
use App\Models\LeaveType;
use App\Models\Portal;
use App\Models\Project;
use App\Models\Scopes\PortalScope;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Console\Command;

class MakePortal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:status {portalId} {disable?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Disable/Enable a portal {portalId}';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        try {
            $portalId = $this->argument('portalId');
            $disable = (bool) $this->argument('disable');
            $portal = Portal::find($portalId);

            if($portal) {
                if($disable === false && !$portal->active) {
                    $this->error('Portal is already disabled');
                    return;
                }

                if($disable === true && $portal->active) {
                    $this->error('Portal is already enabled');
                    return;
                }

                $portal->active = $disable;
                $portal->save();
            } else {
                $this->error('Portal not found');
            }
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }

        $this->info('Portal updated successfully');
    }
}
