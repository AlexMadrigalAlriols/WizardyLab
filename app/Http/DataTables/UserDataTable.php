<?php

namespace App\Http\DataTables;

use App\Models\Client;
use App\Models\User;
use App\Services\QueryBuilderService;

class UserDataTable extends DataTable
{
    public function __construct(protected ?string $name = 'users') {}

    public function dataTable($table): mixed
    {
        $table->addColumn('placeholder', '&nbsp;');
        $table->addColumn('actions', '&nbsp;');

        $table->editColumn('actions', function($row) {
            $crudRoutePart = 'users';
            $model = 'user';
            $viewGate = 'user_view';
            $editGate = 'user_edit';
            $deleteGate = 'user_delete';
            $links = [];

            if(!$row->approved) {
                $links[] = [
                    'href' => route('dashboard.attendance.index') . '?user=' . $row->code,
                    'icon' => 'bx bx-timer',
                    'text' => 'Attendances',
                    'permission' => 'attendance_seeAll'
                ];
            }

            return view('partials.datatables.actions', compact(
                'row',
                'crudRoutePart',
                'model',
                'viewGate',
                'editGate',
                'deleteGate',
                'links'
            ));
        });

        $table->editColumn('profile_img', function($row) {
            return $row->profile_img ? '<img src="'. $row->profile_url .'" class="rounded-circle" width="60px" height="60px">' : '';
        });

        $table->editColumn('name', function($row) {
            return $row->name ?: '';
        });

        $table->editColumn('email', function($row) {
            return $row->email ?: '-';
        });

        $table->editColumn('department', function($row) {
            return $row->department?->name;
        });

        $table->editColumn('role', function($row) {
            return $row->role?->name;
        });

        $table->rawColumns(['placeholder', 'profile_img', 'actions']);

        return $table;
    }

    public function query()
    {
        $query = User::query();

        $email = request()?->get('email');
        $created_at_range = request()?->get('created_at_range');

        $query = (new QueryBuilderService())->advancedQuery(
            User::class,
            [
                'conditions' => request()?->get('conditions') ?? '',
                'fields' => request()?->get('fields') ?? '',
                'operators' => request()?->get('operators') ?? '',
                'values' => request()?->get('values') ?? '',
            ],
            ['department', 'role', 'reportingUser']
        );

        if($email) {
            $query->where('email', 'like', "%{$email}%");
        }

        if(!empty($created_at_range)) {
            $range = str_replace(' a ', ' - ', $created_at_range);
            $range = explode(' - ', $range);

            $query->whereDate('created_at', '>=', $range[0]);

            if(isset($range[1])) {
                $query->whereDate('created_at', '<=', $range[1]);
            }
        }

        return $query;
    }
}
