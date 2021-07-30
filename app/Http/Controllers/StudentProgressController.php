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
        return response()->json((array)$progress);
    }
}
