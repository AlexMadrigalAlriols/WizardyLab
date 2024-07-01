<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'client_view',
            'client_create',
            'client_edit',
            'client_delete',
            'company_view',
            'company_create',
            'company_edit',
            'company_delete',
            'invoice_view',
            'invoice_create',
            'invoice_edit',
            'invoice_delete',
            'deliveryNote_view',
            'deliveryNote_create',
            'deliveryNote_edit',
            'deliveryNote_delete',
            'leave_view',
            'leave_create',
            'leave_edit',
            'leave_delete',
            'leave_approve',
            'attendance_view',
            'attendance_create',
            'attendance_edit',
            'attendance_delete',
            'attendance_seeAll',
            'holiday_view',
            'holiday_create',
            'holiday_edit',
            'holiday_delete',
            'document_view',
            'document_create',
            'document_edit',
            'document_delete',
            'user_view',
            'user_create',
            'user_edit',
            'user_delete',
            'project_view',
            'project_create',
            'project_edit',
            'project_delete',
            'task_view',
            'task_create',
            'task_edit',
            'task_delete',
            'item_view',
            'item_create',
            'item_edit',
            'item_delete',
            'assignment_view',
            'assignment_create',
            'assignment_edit',
            'assignment_delete',
            'expense_view',
            'expense_create',
            'expense_edit',
            'expense_delete',
            'configuration_view',
            'configuration_create',
            'configuration_edit',
            'configuration_delete',
            'status_view',
            'status_create',
            'status_edit',
            'status_delete',
            'role_view',
            'role_create',
            'role_edit',
            'role_delete',
            'department_view',
            'department_create',
            'department_edit',
            'department_delete',
            'label_view',
            'label_create',
            'label_edit',
            'label_delete',
            'leaveType_view',
            'leaveType_create',
            'leaveType_edit',
            'leaveType_delete',
            'attendanceTemplate_view',
            'attendanceTemplate_create',
            'attendanceTemplate_edit',
            'attendanceTemplate_delete'
        ];

        DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        DB::table('permissions')->truncate();
        foreach ($permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        DB::statement('SET FOREIGN_KEY_CHECKS=1;');
    }
}
