<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Helpers\FileSystemHelper;
use App\Helpers\SubdomainHelper;
use App\Helpers\TaskAttendanceHelper;
use App\Http\Controllers\Controller;
use App\Http\Requests\Documents\StoreRequest;
use App\Http\Requests\Documents\UploadFileRequest;
use App\Models\Document;
use App\Models\DocumentFolder;
use App\Models\Task;
use App\Models\User;
use App\UseCases\Documents\StoreUseCase;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Spatie\FlareClient\Api;

class DocumentController extends Controller
{
    public function index(Request $request, string $folder)
    {
        $request->session()->forget('dropzone_document_files_temp_paths');
        $portal = SubdomainHelper::getPortal($request);

        if(is_string($folder)) {
            $fsearch = str_replace('+', ' ', $folder);
            $fsearch = DocumentFolder::where('name', $fsearch)->first();

            if(!$fsearch) {
                $fsearch = DocumentFolder::findOrFail($folder);
            }

            $folder = $fsearch;
        }

        $query = $folder->documents()->where(function($query) {
            $query->where('user_id', auth()->user()->id)
                ->orWhere('user_id', null);
        });

        if($request->has('search')) {
            $query->where('title', 'like', '%' . $request->input('search') . '%');
        }
        $documents = $query->orderBy('created_at', 'desc')->get();

        if($request->ajax()) {
            return view('partials.documents.list', compact('folder', 'documents', 'portal'));
        }

        $users = User::all();
        return view('dashboard.documents.list', compact('folder', 'documents', 'users', 'portal'));
    }

    public function show(DocumentFolder $folder, Document $document)
    {
        $folders = DocumentFolder::all();

        return view('partials.documents.viewDocument', compact('folder', 'document', 'folders'));
    }

    public function store(StoreRequest $request, DocumentFolder $folder)
    {
        $extensions = ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'doc', 'docx', 'xls', 'xlsx', 'ppt', 'pptx', 'txt', 'zip', 'rar', '7z'];

        if($request->session()->has('dropzone_document_files_temp_paths')) {
            $portal = SubdomainHelper::getPortal($request);

            foreach ($request->session()->get('dropzone_document_files_temp_paths', []) as $idx => $tempPath) {
                if(Storage::size($tempPath) <= $portal->remaining_storage) {
                    [$permanentPath, $originalName, $storaged] = FileSystemHelper::saveFile(
                        $request,
                        $tempPath,
                        'dropzone_document_files_temp_paths',
                        'documents/' . $portal->id . '/',
                        $extensions
                    );

                    if($storaged) {
                        $document = (new StoreUseCase(
                            $folder,
                            auth()->user(),
                            $request->input('user_id') ? User::findOrFail($request->input('user_id')) : null,
                            $originalName,
                            $permanentPath,
                            Storage::disk('public')->size($permanentPath),
                            Storage::disk('public')->mimeType($permanentPath),
                            ['needSigned' => $request->input('needSigned') == '1' ? true : false]
                        ))->action();
                    }
                } else {
                    return redirect()->route('dashboard.documents.index', $folder->name)->with('error', 'You have exceeded your storage limit');
                }
            }
        }

        return redirect()->route('dashboard.documents.index', $folder->name)->with('success', 'Document uploaded successfully');
    }

    public function update(Request $request, DocumentFolder $folder, Document $document)
    {
        $document->update($request->only('title', 'document_folder_id'));

        return ApiResponse::done('Document updated successfully');
    }

    public function viewSignFile(DocumentFolder $folder, Document $document)
    {
        if($document->signed) {
            toast('This document has already been signed', 'error');
            return back();
        }

        if($document->data['needSigned'] != true || $document->mime_type !== 'application/pdf') {
            toast('This document does not need to be signed', 'error');

            return back();
        }

        if($document->user_id != auth()->user()->id && $document->user_upload_id != auth()->user()->id) {
            toast("You don't have permissions to see this.", 'error');

            return back();
        }

        return view('dashboard.documents.viewSign', compact('folder', 'document'));
    }

    public function signDocument(Request $request, Document $document)
    {
        if($request->pdf) {
            $filePath = $document->path;
            $fileName = basename($filePath);
            $directoryPath = dirname($filePath);

            Storage::disk('public')->delete($document->path);
            Storage::disk('public')->put($directoryPath . '/' . $fileName, $request->file('pdf')->get());
        }

        $document->update([
            'data' => array_merge($document->data, ['signed' => true, 'signed_user_id' => auth()->user()->id])
        ]);

        toast('Document signed successfully', 'success');
        return ApiResponse::done('Document signed successfully');
    }

    public function destroy(DocumentFolder $folder, Document $document)
    {
        $document->delete();

        return back();
    }

    public function uploadFile(UploadFileRequest $request, DocumentFolder $folder)
    {
        return FileSystemHelper::uploadFile($request, 'dropzone_document_files_temp_paths');
    }

    public function download(DocumentFolder $folder, Document $document)
    {
        return Storage::disk('public')->download($document->path, $document->title);
    }
}
