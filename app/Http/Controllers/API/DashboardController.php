<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;

class DashboardController extends Controller
{
    public function index() {
        return response([
            'total_active_users' => User:: whereRelation('status', 'name','active')->count(),
            'total_inactive_users' => User:: whereRelation('status', 'name', 'inactive')->count(),
            'total_blocked_users' => User:: whereRelation('status', 'name', 'blocked')->count(),
            'total_products' => Product::count(),
            'total_orders' => Order::count()
        ]);
    }

}
