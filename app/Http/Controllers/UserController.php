<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use App\Helpers\ApiResponse;

class UserController extends Controller
{
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id = null)
    {
        return $id ? ApiResponse::success(data: User::find($id)) : ApiResponse::success(data: User::paginate());
    }

    public function showUser()
    {
        return ApiResponse::success(data: Auth::user());
    }
}
