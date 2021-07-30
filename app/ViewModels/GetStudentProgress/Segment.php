<?php
declare(strict_types=1);

namespace App\ViewModels\GetStudentProgress;

class Segment
{
    /** @param PracticeRecord[] $practiceRecords */
    public function __construct(
        public int $id,
        public int $lessonId,
        public string $name,
        public int $order,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public array $practiceRecords,
    )
    {
    }
}
