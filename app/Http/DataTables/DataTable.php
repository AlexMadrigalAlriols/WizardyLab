<?php

namespace App\Http\DataTables;

use Yajra\DataTables\Facades\DataTables;

abstract class DataTable implements DataTableInterface
{
    public function make()
    {
        $query = $this->query();
        $table = is_a($query, \Illuminate\Database\Eloquent\Builder::class)
            ? DataTables::of($query)
            : DataTables::eloquent($query);
        $table = $this->dataTable($table);
        return $table->make();
    }


    protected function decodeTranslatable(string $data): array
    {
        return json_decode($data, true, 512, JSON_THROW_ON_ERROR);
    }

    protected function getTranslationFromEncodeData(string $data, string $locale = null): ?string
    {
        if (is_null($locale)) {
            $locale = app()->getLocale();
        }

        try {
            $translations = $this->decodeTranslatable($data);
            return $translations[$locale] ?? null;
        } catch (\JsonException $e) {
            return null;
        }
    }
}
