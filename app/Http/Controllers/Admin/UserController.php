<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\ApiResponse;
use App\Helpers\AttendanceHelper;
use App\Helpers\SubdomainHelper;
use App\Helpers\TaskAttendanceHelper;
use App\Http\Controllers\Controller;
use App\Http\DataTables\UserDataTable;
use App\Http\Requests\MassDestroyRequest;
use App\Http\Requests\Users\StoreRequest;
use App\Http\Requests\Users\UpdateRequest;
use App\Models\AttendanceTemplate;
use App\Models\Country;
use App\Models\Department;
use App\Models\Role;
use App\Models\User;
use App\UseCases\Users\StoreUseCase;
use App\UseCases\Users\UpdateUseCase;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $portal = auth()->user()->portal;

        if($request->ajax()) {
            $dataTable = new UserDataTable('users');
            return $dataTable->make();
        }

        $query = User::query();
        $total = $query->count();

        return view('dashboard.users.index', compact('total', 'portal'));
    }

    public function userAttendance(Request $request) {
        $attendance = AttendanceHelper::getTodayAttendanceOrCreate();

        return ApiResponse::ok(
            [
                'clock' => [
                    'in' => $attendance->check_in,
                    'out' => $attendance->check_out,
                    'timer' => AttendanceHelper::getTimer($attendance)
                ]
            ]
        );
    }

    public function userClockIn(Request $request) {
        $ubication = [
            'latitude' => $request->get('latitude'),
            'longitude' => $request->get('longitude')
        ];

        $attendance = AttendanceHelper::getTodayAttendanceOrCreate($ubication);

        if ($attendance->check_in) {
            toast('You have already clocked in', 'info');
        }

        if ($attendance->check_out) {
            $attendance = AttendanceHelper::createAttendance(auth()->user(), now()->format('Y-m-d'), $ubication);
        }

        $attendance->update([
            'check_in' => now()
        ]);

        toast('You have successfully clocked in', 'success');
        return redirect()->route('dashboard.index');
    }

    public function userClockOut(Request $request) {
        $attendance = AttendanceHelper::getTodayAttendanceOrCreate();

        if (!$attendance->check_in) {
            toast('You have not clocked in yet', 'info');
            return redirect()->route('dashboard.index');
        }

        if ($attendance->check_out) {
            toast('You have already clocked out', 'info');
            return redirect()->route('dashboard.index');
        }

        $attendance->update([
            'check_out' => now()
        ]);

        TaskAttendanceHelper::clockAllTaskTimers();
        toast('You have successfully clocked out', 'success');
        return redirect()->route('dashboard.index');
    }

    public function create(Request $request)
    {
        $portal = SubdomainHelper::getPortal($request);

        if($portal->users()->count() >= $portal->user_count) {
            toast('You have reached the maximum number of users', 'error');
            return redirect()->route('dashboard.users.index');
        }

        $user = new User();
        $departments = Department::all();
        $roles = Role::all();
        $countries = Country::all();
        $qusers = User::all();
        $attendanceTemplates = AttendanceTemplate::all();

        return view('dashboard.users.create', compact(
            'user',
            'departments',
            'roles',
            'countries',
            'qusers',
            'attendanceTemplates'
        ));
    }

    public function store(StoreRequest $request)
    {
        $portal = SubdomainHelper::getPortal($request);

        if($portal->users()->count() >= $portal->user_count) {
            toast('You have reached the maximum number of users', 'error');
            return redirect()->route('dashboard.users.index');
        }

        $user = (new StoreUseCase(
            $request->input('name'),
            Carbon::parse($request->input('birthday_date')),
            $request->input('email'),
            $request->input('reporting_user_id'),
            $request->input('gender'),
            $request->input('department_id'),
            $request->input('country_id'),
            $request->input('role_id'),
            $request->input('password'),
            $portal,
            $request->input('attendance_template_id')
        ))->action();

        $this->saveTaskFiles($user, $request);

        toast('User created', 'success');
        return redirect()->route('dashboard.users.index');
    }

    public function update(UpdateRequest $request, User $user)
    {
        $update = (new UpdateUseCase(
            $user,
            $request->input('name'),
            Carbon::parse($request->input('birthday_date')),
            $request->input('email'),
            $request->input('reporting_user_id'),
            $request->input('gender'),
            $request->input('department_id'),
            $request->input('country_id'),
            $request->input('role_id'),
            $request->input('attendance_template_id')
        ))->action();

        $this->saveTaskFiles($user, $request);

        toast('User updated', 'success');
        return redirect()->route('dashboard.users.index');
    }


    public function edit(User $user)
    {
        $departments = Department::all();
        $roles = Role::all();
        $countries = Country::all();
        $qusers = User::all();
        $attendanceTemplates = AttendanceTemplate::all();

        return view('dashboard.users.edit', compact(
            'user',
            'departments',
            'roles',
            'countries',
            'qusers',
            'attendanceTemplates'
        ));
    }

    public function destroy(User $user)
    {
        $user->delete();

        toast('User deleted', 'success');
        return redirect()->route('dashboard.users.index');
    }

    public function massDestroy(MassDestroyRequest $request)
    {
        $ids = $request->input('ids');
        User::whereIn('id', $ids)->delete();

        toast('Users deleted', 'success');
        return response(null, Response::HTTP_NO_CONTENT);
    }

    public function show(User $user)
    {
        return view('dashboard.users.show', compact('user'));
    }

    public function uploadFile(Request $request)
    {
        $request->session()->forget('dropzone_users_temp_paths');

        if ($request->hasFile('dropzone_image')) {
            $files = $request->file('dropzone_image');

            foreach ($files as $idx => $file) {
                $tempPath = $file->storeAs('temp', $file->getClientOriginalName());

                $dropzoneUsersTempPaths = $request->session()->get('dropzone_users_temp_paths', []);
                $dropzoneUsersTempPaths[] = $tempPath;
                $request->session()->put('dropzone_users_temp_paths', $dropzoneUsersTempPaths);
            }

            return response()->json(['path' => $tempPath], 200);
        }

        return response()->json(['error' => 'No file uploaded.'], 400);
    }

    private function saveTaskFiles(User $user, Request $request) {
        if ($request->session()->has('dropzone_users_temp_paths')) {
            foreach ($request->session()->get('dropzone_users_temp_paths', []) as $idx => $tempPath) {
                $originalName = pathinfo($tempPath, PATHINFO_BASENAME);
                $extension = pathinfo($tempPath, PATHINFO_EXTENSION);
                $fileName = uniqid() . '.' . $extension;
                $permanentPath = 'user_profiles/' . $user->id . '/' . $fileName;

                $storaged = Storage::disk('public')->put($permanentPath, Storage::disk('local')->get($tempPath));
                Storage::disk('local')->delete($tempPath);
                $request->session()->forget('dropzone_users_temp_paths');

                if ($storaged) {
                    $user->profile_img = $permanentPath;
                    $user->save();
                }
            }
        }
    }

}
