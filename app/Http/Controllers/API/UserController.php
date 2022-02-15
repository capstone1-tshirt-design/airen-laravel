<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Http\Resources\User\Collection as UserCollection;
use Illuminate\Support\Facades\App;
use App\Http\Requests\User\Store;
use App\Http\Requests\User\UpdateStatus;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $users = User::with(['roles', 'roles.permissions', 'status', 'image']);

        if ($request->has('role')) {
            $users->where(function ($query) use ($request) {
                $query->whereRelation('roles', 'name', $request->role);
                if ($request->has('picker') && $request->boolean('picker') && $request->role === 'administrator') {
                    $query->orWhereRelation('roles', 'name', 'super ' . $request->role);
                }
            });
        }

        if ($request->has('status')) {
            $users->whereRelation('status', 'id', $request->status);
        }

        if ($request->has('q')) {
            $q = trim($request->q) . '%';

            $users->where('last_name', 'like', $q);
        }

        if ($request->has('filter')) {
            $filter = '%' . trim($request->filter) . '%';
            $users->where(function ($query) use ($filter) {
                $query
                    ->where('first_name', 'like', $filter)
                    ->orWhere('last_name', 'like', $filter)
                    ->orWhere('phone', 'like',  $filter)
                    ->orWhere('email', 'like', $filter)
                    ->orWhere('username', 'like', $filter)
                    ->orWhereRelation('status', 'name', 'like', $filter);
            });
        }

        if ($request->has(['sortBy', 'sortDirection'])) {
            $users->orderBy($request->sortBy, $request->sortDirection);
        }

        if ($request->has('picker') && $request->boolean('picker')) {
            $users->limit(10);
            return new UserCollection($users->get());
        } else {
            return new UserCollection($users->paginate($request->perPage));
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Store $request)
    {
        $request->createUser();

        return response(null, 201);
    }

    /**
     * Update the specified resource status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function updateStatus(UpdateStatus $request, User $user)
    {
        extract($request->validated());

        $user->status()->associate($userStatus);

        $user->save();

        return response(null, 202);
    }

    /**
     * Reset the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function reset(Request $request, User $user)
    {
        $user->password = bcrypt('123456');
        $user->last_login_at = null;

        $user->save();

        return response(null, 202);
    }

    /**
     * Miscellaneous
     */
    public function checkUniqueField(Request $request, $field)
    {
        $users = User::query();
        if ($request->has($field)) {
            $users
                ->where($field, $request->input($field));

            if ($request->has('user')) {
                $users
                    ->where('id', '<>', $request->user);
            }

            return response(($users->count() === 0 ? true : false), 200);
        }
        return response(false, 200);
    }
}
