<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Helpers\SubdomainHelper;
use App\Helpers\TaskAttendanceHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\DocumentFolder\StoreRequest;
use App\Http\Requests\DocumentFolder\UpdateRequest;
use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\Task;
use Illuminate\Http\Request;

class DocumentFolderController extends Controller
{
    public function index(Request $request)
    {
        $portal = SubdomainHelper::getPortal($request);
        $folders = DocumentFolder::orderBy('order')->get();

        if($request->ajax()) {
            return view('partials.documentFolders.list', compact('folders'));
        }

        return view('dashboard.documents.index', compact('folders', 'portal'));
    }

    public function store(StoreRequest $request)
    {
        $name = $request->input('name');
        $counter = 1;
        if(DocumentFolder::where('name', $name)->exists()) {
            do {
                $name = $request->name . ' (' . $counter . ')';
                $counter++;
            } while (DocumentFolder::where('name', $name)->exists());
        }

        DocumentFolder::create([
            'name' => $name
        ]);

        return ApiResponse::done('Folder created successfully');
    }

    public function updateOrder(UpdateRequest $request, DocumentFolder $folder)
    {
        $folder->update([
            'order' => $request->order
        ]);

        return ApiResponse::done('Folder order updated successfully');
    }

    public function destroy(DocumentFolder $document)
    {
        $document->delete();

        return back();
    }
}
