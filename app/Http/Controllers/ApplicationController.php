<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\AcceptApplicationRequest;
use App\Http\Requests\StoreApplicationRequest;
use App\Http\Requests\UpdateApplicationRequest;
use App\Http\Requests\UrlApplicationRequest;
use App\Models\Application;
use App\Services\ApplicationService;



class ApplicationController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreApplicationRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreApplicationRequest $request, ApplicationService $applicationService)
    {
        $validatedData = $request->safe()->only(['name', 'email', 'phone', 'attachment']);
        return $applicationService->store($validatedData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        return ApiResponse::success(data: Application::where('status', 'pending')->latest()->paginate());
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function url(UrlApplicationRequest $request, ApplicationService $applicationService)
    {
        $validatedData = $request->safe()->only(['filename']);
        return $applicationService->download($validatedData['filename']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function acceptApplication(AcceptApplicationRequest $request, ApplicationService $applicationService)
    {
        $validatedData = $request->validated();
        $validatedData['status'] = 'accepted';
        return $applicationService->acceptApplication($validatedData);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function rejectApplication(AcceptApplicationRequest $request, ApplicationService $applicationService)
    {
        $validatedData = $request->validated();
        $validatedData['status'] = 'rejected';
        return $applicationService->rejectApplication($validatedData);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function edit(Application $application)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateApplicationRequest  $request
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateApplicationRequest $request, Application $application)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function destroy(Application $application)
    {
        //
    }
}
