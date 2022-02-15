<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use App\Http\Resources\Order\Collection as OrderCollection;
use App\Models\OrderStatus;
use App\Http\Requests\Order\UpdateStatus;
use Illuminate\Database\Eloquent\Builder;

class OrderController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $orders = Order::with([
            'customer',
            'customer.image',
            'customer.status',
            'customer.roles',
            'orderItems',
            'orderItems.product',
            'orderItems.product.categories',
            'orderItems.product.images',
            'orderItems.shirtOption',
            'orderItems.product.createdBy',
            'orderItems.product.createdBy.roles',
            'orderItems.product.updatedBy',
            'orderItems.product.updatedBy.roles',
            'orderItems.product.deletedBy',
            'orderItems.product.updatedBy.roles',
            'status'
        ]);

        if ($request->has('customer')) {
            $orders->whereRelation('customer', 'id', $request->customer);
        }

        if ($request->has('status')) {
            $orders->whereRelation('status', 'id', $request->status);
        }

        if ($request->has('filter')) {
            $filter = '%' . trim($request->filter) . '%';
            $orders->where(function (Builder $query) use ($filter) {
                $query
                    ->where('id', 'like', $filter)
                    ->orWhereRelation('customer', 'first_name', 'like', $filter)
                    ->orWhereRelation('customer', 'last_name', 'like', $filter)
                    ->orWhereRelation('status', 'name', 'like', $filter);
            });
        }

        if ($request->has(['sortBy', 'sortDirection'])) {
            $orders->orderBy($request->sortBy, $request->sortDirection);
        }
        return new OrderCollection($orders->paginate($request->has('perPage') ? $request->perPage : 10));
    }

    /**
     * Update the specified resource status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(UpdateStatus $request, Order $order)
    {
        extract($request->validated());

        $order->status()->associate($orderStatus);

        $order->save();

        return response(null, 202);
    }

    /**
     * Cancel the specified resource status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Order  $order
     * @return \Illuminate\Http\Response
     */
    public function cancel(Request $request, Order $order)
    {
        $orderStatus = OrderStatus::where('name', 'cancelled')->first();

        $order->status()->associate($orderStatus);

        $order->save();

        return response(null, 202);
    }
}
