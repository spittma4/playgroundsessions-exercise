<?php
declare(strict_types=1);

namespace App\ViewModels\GetStudentProgress;

class CurrentLesson
{


    public function __construct(
        public int $id,
        public string $name,
        public int $difficulty,
        public bool $isComplete
    )
    {
    }

    public function getLessonId(): int {
        return $this->id;
    }

    public function getLessonName(): string {
        return $this->name;
    }

    public function getLessonDifficulty(): int {
        return $this->difficulty;
    }

    public function getLessonCompletionStatus(): bool {
        return $this->isComplete;
    }
}

