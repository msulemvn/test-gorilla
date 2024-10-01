<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreSupervisorRequest;
use App\Http\Requests\UpdateSupervisorRequest;
use App\Models\Supervisor;
use App\Helpers\ApiResponse;
use App\DTOs\UserDTO;
use App\DTOs\SupervisorDTO;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class SupervisorController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSupervisorRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSupervisorRequest $request)
    {
        $validatedData = $request->validated();
        try {
            $validatedData['password'] = bcrypt(Str::random(16));
            $newUser = User::create((new UserDTO($validatedData))->toArray());
            $newSupervisor = Supervisor::create((new SupervisorDTO(['account_id' => $newUser->id]))->toArray());
            $newUser->assignRole('supervisor');
            if ($newSupervisor) {
                $status = Password::sendResetLink(["email" => $newUser['email']]);
                if ($status === Password::RESET_LINK_SENT) {
                    return ApiResponse::success(message: 'Adding supervisor successful, email notification sent.');
                }
            }
        } catch (\Exception $e) {
            return ApiResponse::error(error: 'An error occurred while adding Supervisor.');
        }
        return ApiResponse::error(error: 'Adding supervisor failed.', statusCode: 500);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Supervisor  $supervisor
     * @return \Illuminate\Http\Response
     */
    public function show(Supervisor $supervisor)
    {
        // return ApiResponse::success(data: Supervisor::with('user')->paginate()->through(function ($supervisor) {
        //     return [
        //         'id' => $supervisor->id,
        //         'name' => $supervisor->user->name,
        //         'email' => $supervisor->user->email,
        //     ];
        // }));
        return ApiResponse::success(data: Supervisor::with('user')->get()->map(function ($supervisor) {
            return [
                'id' => $supervisor->id,
                'name' => $supervisor->user->name,
                'email' => $supervisor->user->email,
            ];
        }));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSupervisorRequest  $request
     * @param  \App\Models\Supervisor  $supervisor
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSupervisorRequest $request, Supervisor $supervisor)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Supervisor  $supervisor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Supervisor $supervisor)
    {
        try {
            $supervisor->user->delete();
            $supervisor->delete();
        } catch (\Throwable $th) {
            return ApiResponse::success(message: 'An error occured while deleting supervisor.');
        }

        return ApiResponse::success(message: 'Successfully deleted supervisor.');
    }
}
