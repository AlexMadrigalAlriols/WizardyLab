<?php

namespace App\Helpers;

use App\Models\Leave;
use App\Models\Notification;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class FileSystemHelper {
    public static function uploadFile(Request $request, string $session_name = 'DROPZONE_FILES_TEMP_PATHS') {
        if ($request->hasFile('dropzone_image')) {
            $files = is_array($request->file('dropzone_image')) ? $request->file('dropzone_image') : [$request->file('dropzone_image')];

            foreach ($files as $idx => $file) {
                $tempPath = $file->storeAs('temp', $file->getClientOriginalName());

                $dropzoneTasksTempPaths = $request->session()->get($session_name, []);
                $dropzoneTasksTempPaths[] = $tempPath;
                $request->session()->put($session_name, $dropzoneTasksTempPaths);
            }

            return response()->json(['path' => $tempPath ?? ''], 200);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }

    public static function saveFile(
        Request $request,
        string $tempPath,
        string $session_name = 'DROPZONE_FILES_TEMP_PATHS',
        $permanentPath = 'files/',
        ?array $extensions = null
    ) {
        $originalName = pathinfo($tempPath, PATHINFO_BASENAME);
        $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
        $fileName = uniqid() . '.' . $extension;
        $permanentPath = $permanentPath . $fileName;

        if(!is_null($extensions) && !in_array($extension, $extensions)) {
            return [null, null, false];
        }

        $storaged = Storage::disk('public')->put($permanentPath, Storage::disk('local')->get($tempPath));
        Storage::disk('local')->delete($tempPath);
        $request->session()->forget($session_name);

        return [$permanentPath, $originalName, $storaged];
    }
}
