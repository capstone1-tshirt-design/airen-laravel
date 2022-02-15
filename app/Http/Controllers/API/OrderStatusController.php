<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\OrderStatus;
use App\Http\Resources\OrderStatus\Collection as OrderStatusCollection;

class OrderStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orderStatuses = OrderStatus::query();

        if ($request->has('q')) {
            $q = trim($request->q) . '%';

            $orderStatuses->where('name', 'like', $q);
        }

        if ($request->has('filter')) {
            $filter = trim($request->filter) . '%';
            $orderStatuses->where(function ($query) use ($filter) {
                $query
                    ->where('name', 'like', $filter);
            });
        }

        $orderStatuses->limit(10);
        return new OrderStatusCollection($orderStatuses->get());
    }
}
