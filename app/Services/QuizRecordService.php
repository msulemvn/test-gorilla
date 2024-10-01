<?php

namespace App\Services;

use App\Interfaces\QuizRecordServiceInterface;
use Illuminate\Support\Facades\Storage;

class QuizRecordService implements QuizRecordServiceInterface
{
    public function download($filename)
    {
        if (Storage::disk('recordings')->exists($filename)) {
            $headers = [
                "Content-Type" => Storage::mimeType("recordings/$filename"),
                "Content-Disposition" => "attachment; filename*=UTF-8''$filename'",
                "Content-Length" => Storage::disk("recordings")->size($filename),
            ];
            return Storage::download("recordings/$filename", $filename, $headers);
        }
    }
}
