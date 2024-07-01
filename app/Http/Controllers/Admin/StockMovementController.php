<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StockMovements\StoreRequest;
use App\Models\Item;
use App\Traits\MiddlewareTrait;
use App\UseCases\StockMovements\StoreUseCase;

class StockMovementController extends Controller
{
    use MiddlewareTrait;

    public function __construct()
    {
        $this->setMiddleware('item');
    }

    public function store(StoreRequest $request, Item $item) {
        $stock_movement = (new StoreUseCase(
            $item,
            auth()->user(),
            $request->input('quantity'),
            $request->input('type'),
            $request->input('reason', '')
        ))->action();

        toast('Stock Inserted', 'success');
        return back();
    }
}
