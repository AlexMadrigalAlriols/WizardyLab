<?php

namespace App\Http\DataTables;

interface DataTableInterface {
    public function dataTable($table): mixed;
    public function query();
}
