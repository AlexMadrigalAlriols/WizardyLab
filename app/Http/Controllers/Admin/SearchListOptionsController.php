<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\SearchListOption\InvokeRequest;
use App\UseCases\SearchListOptionsUseCase;
use Illuminate\Http\JsonResponse;

class SearchListOptionsController extends Controller
{
    public function __invoke(InvokeRequest $request): JsonResponse
    {
        $useCase = new SearchListOptionsUseCase(
            $request->input('search'),
            $request->input('model'),
            $request->input('field'),
            $request->input('method')
        );

        return response()->json($useCase->action());
    }
}
