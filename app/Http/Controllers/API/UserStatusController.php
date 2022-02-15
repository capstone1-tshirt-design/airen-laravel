<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserStatus;
use App\Http\Resources\UserStatus\Collection as UserStatusCollection;

class UserStatusController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $userStatuses = UserStatus::query();

        if ($request->has('q')) {
            $q = trim($request->q) . '%';

            $userStatuses->where('name', 'like', $q);
        }

        if ($request->has('filter')) {
            $filter = trim($request->filter) . '%';
            $userStatuses->where(function ($query) use ($filter) {
                $query
                    ->where('name', 'like', $filter);
            });
        }

        if ($request->has('role')) {
            $userStatuses->where('name', '<>', 'blocked');
        }

        $userStatuses->limit(10);
        return new UserStatusCollection($userStatuses->get());
    }
}
