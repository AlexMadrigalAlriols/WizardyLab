<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileSystemHelper;
use App\Helpers\SubdomainHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\GlobalConfiguration\StoreRequest;
use App\Models\Client;
use App\Models\GlobalConfiguration;
use App\Models\Invoice;
use App\Models\Portal;
use App\Models\Project;
use App\Models\Status;
use App\Models\Task;
use App\UseCases\Portals\UpdateUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GlobalConfigurationController extends Controller
{
    public function index(Request $request)
    {
        $request->session()->forget('dropzone_logo_temp_paths');
        $globalConfigurations = GlobalConfiguration::all();
        $taskStatuses = Status::where('morphable', Task::class)->get();
        $projectStatuses = Status::where('morphable', Project::class)->get();
        $invoiceStatuses = Status::where('morphable', Invoice::class)->get();
        $clients = Client::where('active', 1)->get();
        $portal = SubdomainHelper::getPortal($request);

        return view('dashboard.globalConfigurations.index', compact(
            'globalConfigurations',
            'taskStatuses',
            'projectStatuses',
            'invoiceStatuses',
            'portal',
            'clients'
        ));
    }

    public function store(StoreRequest $request)
    {
        $portal = SubdomainHelper::getPortal($request);

        $portal = (new UpdateUseCase(
            $portal,
            $request->input('name'),
            $request->input('subdomain'),
            [
                'primary_color' => $request->input('primary_color'),
                'secondary_color' => $request->input('secondary_color'),
                'btn_text_color' => $request->input('btn_text_color'),
                'menu_text_color' => $request->input('menu_text_color'),
            ],
        ))->action();

        $this->saveFiles($portal, $request);

        foreach ($request->input('keys', []) as $key => $value) {
            $globalConfig = GlobalConfiguration::where('key', $value)->first();

            if($globalConfig) {
                $globalConfig->update(['value' => $request->values[$key]]);
            }
        }

        toast('Global Configurations updated', 'success');
        return redirect()->route('dashboard.global-configurations.index');
    }

    public function uploadFile(Request $request)
    {
        return FileSystemHelper::uploadFile($request, 'dropzone_logo_temp_paths');
    }

    private function saveFiles(Portal $portal, Request $request)
    {
        $extensions = ['jpg', 'jpeg', 'png', 'gif'];

        if ($request->session()->has('dropzone_logo_temp_paths')) {
            foreach ($request->session()->get('dropzone_logo_temp_paths', []) as $idx => $tempPath) {
                [$permanentPath, $originalName, $storaged] = FileSystemHelper::saveFile(
                    $request,
                    $tempPath,
                    'dropzone_logo_temp_paths',
                    'portal_logos/' . $portal->id . '/',
                    $extensions
                );

                if ($storaged) {
                    if($portal->data['logo']) {
                        Storage::disk('public')->delete($portal->data['logo']);
                    }

                    $portal->data = array_merge($portal->data, ['logo' => $permanentPath]);
                    $portal->save();
                }
            }
        }
    }
}
