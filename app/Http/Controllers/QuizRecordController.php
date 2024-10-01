<?php

namespace App\Http\Controllers;

use App\Helpers\ApiResponse;
use App\Http\Requests\StoreQuizRecordRequest;
use App\Http\Requests\UpdateQuizRecordRequest;
use App\Models\QuizRecord;
use App\DTOs\QuizRecordDTO;
use App\Http\Requests\UrlQuizRecordRequest;
use Illuminate\Support\Carbon;
use App\Services\QuizRecordService;

class QuizRecordController extends Controller
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreQuizRecordRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreQuizRecordRequest $request)
    {
        $validatedData = $request->validated();
        $file = $validatedData['recording'];
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $timestamp = Carbon::now()->format('YmdHs');

        // dd($file->getMimeType());
        // Check if file is a Video and less than 2GB and mime type
        if (!in_array($file->getMimeType(), [
            'video/mp4',
            'video/avi',
            'video/mpeg',
            'video/quicktime',
            'video/x-ms-wmv',
            'video/x-flv',
            'application/x-mpegURL', // for HLS (HTTP Live Streaming)
            'video/webm',
            'video/x-matroska'
        ]) || $file->getSize() > 2 * 1024 * 1024 * 1024) { // corrected file size check
            return ApiResponse::error(error: 'Upload error, expecting file type mp4, avi, mpeg, quicktime, x-ms-wmv,x-flv,x-mpegURL, webp, x-matroska under size 2GB', statusCode: 415);
        }

        // Sanitize filename and store file
        $safeName = preg_replace('/[^a-zA-Z0-9]+/', '-', $name);
        $filename = "$timestamp-$safeName.$extension";
        $file->storeAs('recordings', $filename);

        $validatedData['recording'] = $filename;
        $quizRecord = QuizRecord::create((new QuizRecordDTO($validatedData))->toArray());
        return ApiResponse::success(data: $quizRecord);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\QuizRecord  $quizRecord
     * @return \Illuminate\Http\Response
     */
    public function show($studentId = null)
    {
        $quizInstance = $studentId
            ? QuizRecord::with('quizInstance')->get()->map(function ($record) use ($studentId) {
                if ($record->quizInstance->assigned_to == $studentId) {
                    return [
                        'id' => $record->id,
                        'quiz_id' => $record->quizInstance->quiz_id,
                        'quiz_instance_id' => $record->quizInstance->id,
                        'student_id' => $record->quizInstance->assigned_to,
                        'score' => $record->score,
                    ];
                }
            })->filter()->values() : QuizRecord::with('quizInstance')->latest()->get()->map(function ($record) {
                return [
                    'id' => $record->id,
                    'quiz_id' => $record->quizInstance->quiz_id,
                    'quiz_instance_id' => $record->quizInstance->id,
                    'student_id' => $record->quizInstance->assigned_to,
                    'recording' => $record->recording,
                    'score' => $record->score,
                ];
            });

        if ($quizInstance->isEmpty()) {
            return ApiResponse::error('Quiz is not assigned to this user.');
        }

        return ApiResponse::success($quizInstance);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Application  $application
     * @return \Illuminate\Http\Response
     */
    public function url(UrlQuizRecordRequest $request, QuizRecordService $quizRecordService)
    {
        $validatedData = $request->safe()->only(['filename']);
        return $quizRecordService->download($validatedData['filename']);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\QuizRecord  $quizRecord
     * @return \Illuminate\Http\Response
     */
    public function edit(QuizRecord $quizRecord)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateQuizRecordRequest  $request
     * @param  \App\Models\QuizRecord  $quizRecord
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateQuizRecordRequest $request, QuizRecord $quizRecord)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\QuizRecord  $quizRecord
     * @return \Illuminate\Http\Response
     */
    public function destroy(QuizRecord $quizRecord)
    {
        //
    }
}
