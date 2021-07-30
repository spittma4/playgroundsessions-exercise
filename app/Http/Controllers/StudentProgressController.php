<?php
declare(strict_types=1);

namespace App\Http\Controllers;

use App\UseCases\GetStudentProgressUseCase;
use App\ViewModels\LessonCsv\LessonCsv;
use Illuminate\Http\JsonResponse;

class StudentProgressController extends Controller
{
    public function get(int $userId): JsonResponse
    {
        $progress = (new GetStudentProgressUseCase(new LessonCsv()))
            ->getAllLessons($userId);
        $response = response()->json((array)$progress);

        $response->setEncodingOptions( $response->getEncodingOptions() | JSON_PRETTY_PRINT );

        return $response;
    }
}
