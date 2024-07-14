<?php

namespace App\Console\Commands;

use App\Models\Client;
use App\Models\Company;
use App\Models\Department;
use App\Models\GlobalConfiguration;
use App\Models\Invoice;
use App\Models\LeaveType;
use App\Models\Portal;
use App\Models\Project;
use App\Models\Role;
use App\Models\Scopes\PortalScope;
use App\Models\Status;
use App\Models\Task;
use App\Models\User;
use App\UseCases\Users\StoreUseCase;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class CreatePortal extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'portal:make {subdomain} {type?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a new portal {subdomain} {type?}';

    /**
     * Execute the consoles command.
     */
    public function handle()
    {
        try {
            $subdomain = strtolower($this->argument('subdomain'));
            $sub_type = strtolower($this->argument('type') ?? 'basic');

            $name = ucfirst($subdomain) . ' Portal';

            if(Portal::where('subdomain', $subdomain)->exists()) {
                $this->error('Portal already exists');
                return;
            }

            $portal = Portal::create([
                'subdomain' => $subdomain,
                'name' => $name,
                'subscription_type' => $sub_type,
                'active' => 1,
                'data' => [
                    'primary_color' => '#374df1',
                    'secondary_color' => '#242424',
                    'btn_text_color' => '#fff',
                    'menu_text_color' => '#fff',
                    'logo' => env('APP_URL') . '/img/LogoLetters.png',
                ]
            ]);

            session(['portal_id' => $portal->id]);

            if($portal) {
                $client = $this->createClient($portal);
                $this->createStatuses($portal);
                $this->createGlobalConfigs($portal, $client);
                $this->createLeaveTypes($portal);
                $this->createUser($portal, $client);
            }
        } catch (\Throwable $th) {
            $this->error($th->getMessage());
        }

        $this->info('Portal created successfully');
    }

    private function createStatuses(Portal $portal) {
        $statuses = [
            ['title' => 'New', 'data' => json_encode(['background' => '#00bfff', 'color' => '#ffffff']), 'morphable' => Project::class],
            ['title' => 'In Progress', 'data' => json_encode(['background' => '#ff7e38', 'color' => '#ffffff']), 'morphable' => Project::class],
            ['title' => 'Completed', 'data' => json_encode(['background' => '#088743', 'color' => '#ffffff']), 'morphable' => Project::class],
            ['title' => 'Canceled', 'data' => json_encode(['background' => '#ae2121', 'color' => '#ffffff']), 'morphable' => Project::class],

            ['title' => 'New', 'data' => json_encode(['background' => '#00bfff', 'color' => '#ffffff']), 'morphable' => Task::class],
            ['title' => 'In Progress', 'data' => json_encode(['background' => '#ff7e38', 'color' => '#ffffff']), 'morphable' => Task::class],
            ['title' => 'Completed', 'data' => json_encode(['background' => '#088743', 'color' => '#ffffff']), 'morphable' => Task::class],
            ['title' => 'Canceled', 'data' => json_encode(['background' => '#ae2121', 'color' => '#ffffff']), 'morphable' => Task::class],
            ['title' => 'Facturated', 'data' => json_encode(['background' => '#e06666', 'color' => '#ffffff']), 'morphable' => Task::class],
            ['title' => 'Bugs', 'data' => json_encode(['background' => '#470d7d', 'color' => '#ffffff']), 'morphable' => Task::class],

            ['title' => 'Paid', 'data' => json_encode(['background' => '#088743', 'color' => '#ffffff']), 'morphable' => Invoice::class],
            ['title' => 'No Paid', 'data' => json_encode(['background' => '#b63737', 'color' => '#ffffff']), 'morphable' => Invoice::class]
        ];

        foreach ($statuses as $key => $status) {
            $statuses[$key]['portal_id'] = $portal->id;
        }

        Status::withoutGlobalScope(new PortalScope($portal->id))->insert($statuses);
    }

    private function createGlobalConfigs(Portal $portal, Client $client) {
        $configs = [
            ['type' => 'select-language', 'key' => 'language', 'value' => 'en'],
            ['type' => 'select-client', 'key' => 'invoice_client_id', 'value' => $client->id],
            ['type' => 'number', 'key' => 'price_per_hour', 'value' => '15'],
            ['type' => 'number', 'key' => 'tax_value', 'value' => '21'],
            ['type' => 'select-status-task', 'key' => 'completed_task_status', 'value' => ''],
            ['type' => 'select-status-task', 'key' => 'facturated_task_status', 'value' => ''],
            ['type' => 'select-status-project', 'key' => 'completed_project_status', 'value' => ''],
            ['type' => 'select-status-invoice', 'key' => 'default_invoice_status', 'value' => ''],
        ];

        foreach ($configs as $key => $status) {
            $configs[$key]['portal_id'] = $portal->id;
        }

        GlobalConfiguration::withoutGlobalScope(new PortalScope($portal->id))->insert($configs);
    }

    private function createLeaveTypes(Portal $portal) {
        $leaveTypes = [
            ['portal_id' => 1, 'name' => 'Festivos regionales/locales', 'data' => json_encode(['background' => '#0CCF67', 'color' => '#ffffff'])],
            ['portal_id' => 1, 'name' => 'Compensación de Horas', 'data' => json_encode(['background' => '#FF6D1F', 'color' => '#ffffff'])],
            ['portal_id' => 1, 'name' => 'Festivo Nacional', 'data' => json_encode(['background' => '#47759B', 'color' => '#ffffff'])],
            ['portal_id' => 1, 'name' => 'Vacaciones', 'data' => json_encode(['background' => '#6DE2A3', 'color' => '#ffffff'])]
        ];

        foreach ($leaveTypes as $key => $status) {
            $leaveTypes[$key]['portal_id'] = $portal->id;
        }

        LeaveType::withoutGlobalScope(new PortalScope($portal->id))->insert($leaveTypes);
    }

    private function createClient(Portal $portal): Client
    {
        $company = new Company();
        $company->name = 'Default Company';
        $company->active = 1;
        $company->portal_id = $portal->id;
        $company->save();

        $client = new Client();
        $client->name = 'Default Client';
        $client->active = 1;
        $client->portal_id = $portal->id;
        $client->email = 'default@' . $portal->subdomain . '.com';
        $client->company_id = $company->id;
        $client->save();

        return $client;
    }

    private function createUser(Portal $portal, Client $client)
    {
        $department = new Department();
        $department->name = 'Default Department';
        $department->portal_id = $portal->id;
        $department->save();

        $role = new Role();
        $role->portal_id = $portal->id;
        $role->name = 'Admin';
        $role->guard_name = 'web';
        $role->save();

        foreach (Permission::all() as $permission) {
            $role->permissions()->attach($permission->id);
        }

        $user = (new StoreUseCase(
            'Admin',
            now(),
            'admin@' . $portal->subdomain . '.com',
            null,
            'other',
            $department->id,
            $role->id,
            1,
            'password',
            $portal,
            1,
            'U-'.$portal->id.'0001'
        ))->action();
        $user->portal_id = $portal->id;
        $user->save();
        DB::table('model_has_roles')->insert([
            'role_id' => $role->id,
            'model_type' => User::class,
            'model_id' => $user->id
        ]);
    }
}
