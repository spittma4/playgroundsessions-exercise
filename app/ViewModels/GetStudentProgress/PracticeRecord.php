<?php
declare(strict_types=1);

namespace App\ViewModels\GetStudentProgress;

use App\UseCases\DependencyInterfaces\LessonCsvRowInterface;

class PracticeRecord
{
    public function __construct(
        public int $id,
        public int $segmentId,
        public int $userId,
        public string $sessionUuid,
        public float $tempoMultiplier,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public int $score,
    )
    {
    }

}
