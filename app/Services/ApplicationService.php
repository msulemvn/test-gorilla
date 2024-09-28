<?php

namespace App\Services;

use App\DTOs\AcceptApplicationDTO;
use App\Interfaces\ApplicationServiceInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Carbon;
use Illuminate\Http\Response;
use App\DTOs\ApplicationDTO;
use App\Helpers\ApiResponse;
use App\Models\Application;
use App\Models\Student;
use PhpParser\Node\Stmt\TryCatch;
use Illuminate\Support\Facades\Schema;
use App\Models\User;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;

class ApplicationService implements ApplicationServiceInterface
{
    public function store($data)
    {
        $file = $data['attachment'];
        $name = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $extension = $file->getClientOriginalExtension();
        $timestamp = Carbon::now()->format('YmdHs');



        // Check if file is a PDF and less than 4MB and mime type
        if (!in_array($file->getMimeType(), [
            'application/pdf',
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
        ]) || $file->getSize() > 4 * 1024 * 1024) {
            return ApiResponse::error(error: 'Upload error, expecting file type pdf under size 4MB', statusCode: 415);
        }

        // Sanitize filename and store file
        $safeName = preg_replace('/[^a-zA-Z0-9]+/', '-', $name);
        $filename = "$timestamp-$safeName.$extension";
        $file->storeAs('uploads', $filename);


        /**
         ____            _       _              _       _        _                    
        |  _ \ _   _ ___| |__   | |_ ___     __| | __ _| |_ __ _| |__   __ _ ___  ___ 
        | |_) | | | / __| '_ \  | __/ _ \   / _` |/ _` | __/ _` | '_ \ / _` / __|/ _ \
        |  __/| |_| \__ \ | | | | || (_) | | (_| | (_| | || (_| | |_) | (_| \__ \  __/
        |_|    \__,_|___/_| |_|  \__\___/   \__,_|\__,_|\__\__,_|_.__/ \__,_|___/\___|
                                                                                    
         */

        $data['attachment'] = Storage::path($filename);
        $saveApplication = Application::create((new ApplicationDTO($data))->toArray());

        $data = [
            'filename' => $filename,
            'path' => Storage::path($filename),
            'size' => $file->getSize()
        ];

        return ApiResponse::success(message: 'Your form has been submitted successfully, you would be notified through email');
    }

    public function acceptApplication($validatedData)
    {
        try {
            Application::whereId($validatedData['applicationId'])->update((new AcceptApplicationDTO($validatedData['status']))->toArray());
        } catch (Exception $e) {
            return ApiResponse::error('Failed to update application');
        }

        // creating student
        $columnNames = Schema::getColumnListing('students');
        foreach ($columnNames as $value) {
            $student[$value] = "";
        }
        unset($student['created_at']);
        unset($student['updated_at']);
        unset($student['id']);

        $application = Application::find($validatedData['applicationId']);
        // $saveApplication = Application::create((new ApplicationDTO($data))->toArray());
        $newStudent = Student::create(array_intersect_key($application->toArray(), $student));

        // creating user
        unset($student['phone']);
        $student = array_intersect_key($application->toArray(), $student);
        $student['password'] = bcrypt(Str::random(16));

        // $saveApplication = Application::create((new ApplicationDTO($data))->toArray());
        $newUser = User::create($student);
        $newUser->assignRole('student');

        if ($newUser) {
            $status = Password::sendResetLink(["email" => $newUser['email']]);
            if ($status === Password::RESET_LINK_SENT) {
                return ApiResponse::success(message: 'Student application accepted, set user credentials from email');
            }
        }
        return ApiResponse::error(error: 'Student registration failed');
    }


    public function rejectApplication($validatedData)
    {
        try {
            Application::whereId($validatedData['applicationId'])->update((new AcceptApplicationDTO($validatedData['status']))->toArray());
        } catch (Exception $e) {
            return ApiResponse::error('Failed to update application');
        }
        return ApiResponse::success(message: 'Successfully updated application status');
    }

    public function download($filename)
    {
        if (Storage::disk('uploads')->exists($filename)) {
            $headers = [
                "Content-Type" => Storage::mimeType("uploads/$filename"),
                "Content-Disposition" => "attachment; filename*=UTF-8''$filename'",
                "Content-Length" => Storage::disk("uploads")->size($filename),
            ];

            return Storage::download("uploads/$filename", $filename, $headers);
        }
    }

    public function list()
    {
        $list = array_map('basename', Storage::allFiles('uploads'));
        return view('dashboard', compact('list'));
    }

    public function delete(Request $request): Response
    {
        $filename = $request->getContent(false);
        if (Storage::disk('uploads')->exists($filename)) {
            if (Storage::disk('uploads')->delete($filename)) {
                return new Response(['message' => 'File deleted successfully'], 200);
            }
            return new Response(['message' => 'Failed, something went wrong'], 500);
        } else {
            return new Response(['message' => 'File not found'], 404);
        }
    }
}
