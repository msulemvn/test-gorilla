<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreQuizInstanceRequest;
use App\Http\Requests\UpdateQuizInstanceRequest;
use App\Models\QuizInstance;
use App\DTOs\QuizInstanceDTO;
use Throwable;

class QuizInstanceController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreQuizInstanceRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuizInstanceRequest $request)
    {
        $validatedData = $request->validated();

        try {
            $newQuizInstance = QuizInstance::create((new QuizInstanceDTO($validatedData))->toArray());
        } catch (\Throwable $throwable) {
            // Check if the error is a duplicate entry QueryException
            if ($throwable->getCode() === '23000') {
                return ApiResponse::error('Quiz already assigned to this user.', 409);
            }
            return ApiResponse::error('An error occurred while scheduling quiz.', 500);
        }

        return ApiResponse::success(message: 'Quiz scheduled successfully.', data: QuizInstance::find($newQuizInstance->id));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuizInstance  $quizInstance
     * @return \Illuminate\Http\Response
     */
    public function show($roleId = null)
    {
        $quizInstance = $roleId
            ? QuizInstance::where('assigned_to', $roleId)->get()
            : QuizInstance::latest()->get();

        if ($quizInstance->isEmpty()) {
            return ApiResponse::error('Quiz is not assigned to this user.');
        }

        return ApiResponse::success($quizInstance);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuizInstance  $quizInstance
     * @return \Illuminate\Http\Response
     */
    public function edit(QuizInstance $quizInstance)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQuizInstanceRequest  $request
     * @param  \App\Models\QuizInstance  $quizInstance
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuizInstanceRequest $request, QuizInstance $quizInstance)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuizInstance  $quizInstance
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuizInstance $quizInstance)
    {
        //
    }
}
