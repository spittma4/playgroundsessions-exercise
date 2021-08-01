<?php
declare(strict_types=1);

namespace App\ViewModels\GetStudentProgress;

class CurrentSegment
{

    public function __construct(
        public int $id,
        public int $lessonId,
        public bool $hasPassingPracticeRecord
    )
    {
    }
}
