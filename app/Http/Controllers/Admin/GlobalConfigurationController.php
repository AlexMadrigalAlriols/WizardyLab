<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\GlobalConfiguration\StoreRequest;
use App\Models\GlobalConfiguration;
use App\Models\Invoice;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use Illuminate\Http\Request;

class GlobalConfigurationController extends Controller
{
    public function index(Request $request)
    {
        $globalConfigurations = GlobalConfiguration::all();
        $taskStatuses = Status::where('morphable', Task::class)->get();
        $projectStatuses = Status::where('morphable', Project::class)->get();
        $invoiceStatuses = Status::where('morphable', Invoice::class)->get();

        return view('dashboard.globalConfigurations.index', compact('globalConfigurations', 'taskStatuses', 'projectStatuses', 'invoiceStatuses'));
    }

    public function store(StoreRequest $request)
    {
        foreach ($request->input('keys', []) as $key => $value) {
            $globalConfig = GlobalConfiguration::where('key', $value)->first();

            if($globalConfig) {
                $globalConfig->update(['value' => $request->values[$key]]);
            }
        }

        toast('Global Configurations updated', 'success');
        return redirect()->route('dashboard.global-configurations.index');
    }
}
