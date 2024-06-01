<?php

return [
    'companies' => [
        'title' => 'Empresas',
        'title_singular' => 'Empresa',
        'fields' => [
            'id' => 'ID',
            'name' => 'Nombre',
            'active' => 'Active',
            'logo' => 'Logo',
            'created_at' => 'Creado el',
            'updated_at' => 'Actualizado el',
        ],
    ],
    'leaves' => [
        'title' => 'Salidas',
        'title_singular' => 'Salida',
        'fields' => [
            'id' => 'ID',
            'employee' => 'Emopleado',
            'type' => 'Tipo',
            'date' => 'Fecha',
            'status' => 'Estado',
            'reason' => 'Razón',
            'duration' => 'Dureación',
            'user' => 'Usuario',
            'created_at' => 'Creado El',
            'updated_at' => 'Actualizado El',
        ],
    ],
    'status' => [
        'title' => 'Estados',
        'title_singular' => 'Estado',
        'fields' => [
            'id' => 'ID',
            'name' => 'Nombre',
            'type' => 'Tipo',
            'created_at' => 'Creado El',
            'updated_at' => 'Actualizado El',
        ],
    ],
    'labels' => [
        'title' => 'Etiquetas',
        'title_singular' => 'Etiqueta',
        'fields' => [
            'id' => 'ID',
            'name' => 'Nombre',
            'created_at' => 'Creado El',
            'updated_at' => 'Actualizado El',
        ],
    ],
    'leaveTypes' => [
        'title' => 'Tipos de Salida',
        'title_singular' => 'Tipo de Salida',
        'fields' => [
            'id' => 'ID',
            'name' => 'Nombre',
            'max_days' => 'Días Máximos',
            'created_at' => 'Creado el',
            'updated_at' => 'Actualizado el',
        ],
    ],
    'notes' => [
        'title' => 'Notes',
        'title_singular' => 'Note',
        'fields' => [
            'id' => 'ID',
            'title' => 'Title',
            'content' => 'Content',
            'date' => 'Fecha',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
        ],
    ],
];
