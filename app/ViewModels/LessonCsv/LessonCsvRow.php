<?php
declare(strict_types=1);

namespace App\ViewModels\LessonCsv;

use App\UseCases\DependencyInterfaces\LessonCsvRowInterface;

class LessonCsvRow implements LessonCsvRowInterface
{
    public function __construct(
        private int $lessonId,
        private string $lessonName,
        private string $lessonDescription,
        private int $lessonDifficulty,
        private \DateTime $lessonCreatedAt,
        private \DateTime $lessonUpdatedAt,
        private bool $isLessonPublished,
        private int $segmentId,
        private int $segmentLessonId,
        private string $segmentName,
        private int $segmentOrder,
        private \DateTime $segmentCreatedAt,
        private \DateTime $segmentUpdatedAt,
        private int $practiceRecordId,
        private int $practiceRecordSegmentId,
        private int $practiceRecordUserId,
        private string $practiceRecordSessionUuid,
        private float $practiceRecordTempoMultiplier,
        private \DateTime $practiceRecordCreatedAt,
        private \DateTime $practiceRecordUpdatedAt,
        private int $practiceRecordScore,
    )
    {
    }

    public function getLessonId(): int
    {
        return $this->lessonId;
    }

    public function getLessonName(): string
    {
        return $this->lessonName;
    }

    public function getLessonDescription(): string
    {
        return $this->lessonDescription;
    }

    public function getLessonDifficulty(): int
    {
        return $this->lessonDifficulty;
    }

    public function getLessonCreatedAt(): \DateTime
    {
        return $this->lessonCreatedAt;
    }

    public function getLessonUpdatedAt(): \DateTime
    {
        return $this->lessonUpdatedAt;
    }

    public function isLessonPublished(): bool
    {
        return $this->isLessonPublished;
    }

    public function getSegmentId(): int
    {
        return $this->segmentId;
    }

    public function getSegmentLessonId(): int
    {
        return $this->segmentLessonId;
    }

    public function getSegmentName(): string
    {
        return $this->segmentName;
    }

    public function getSegmentOrder(): int
    {
        return $this->segmentOrder;
    }

    public function getSegmentCreatedAt(): \DateTime
    {
        return $this->segmentCreatedAt;
    }

    public function getSegmentUpdatedAt(): \DateTime
    {
        return $this->segmentUpdatedAt;
    }

    public function getPracticeRecordId(): int
    {
        return $this->practiceRecordId;
    }

    public function getPracticeRecordSegmentId(): int
    {
        return $this->practiceRecordSegmentId;
    }

    public function getPracticeRecordUserId(): int
    {
        return $this->practiceRecordUserId;
    }

    public function getPracticeRecordSessionUuid(): string
    {
        return $this->practiceRecordSessionUuid;
    }

    public function getPracticeRecordTempoMultiplier(): float
    {
        return $this->practiceRecordTempoMultiplier;
    }

    public function getPracticeRecordCreatedAt(): \DateTime
    {
        return $this->practiceRecordCreatedAt;
    }

    public function getPracticeRecordUpdatedAt(): \DateTime
    {
        return $this->practiceRecordUpdatedAt;
    }

    public function getPracticeRecordScore(): int
    {
        return $this->practiceRecordScore;
    }
}
