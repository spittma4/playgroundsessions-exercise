<?php
declare(strict_types=1);

namespace App\UseCases\DependencyInterfaces;

interface LessonCsvRowInterface
{
    public function getLessonId(): int;
    public function getLessonName(): string;
    public function getLessonDescription(): string;
    public function getLessonDifficulty(): int;
    public function getLessonCreatedAt(): \DateTime;
    public function getLessonUpdatedAt(): \DateTime;
    public function isLessonPublished(): bool;
    public function getSegmentId(): int;
    public function getSegmentLessonId(): int;
    public function getSegmentName(): string;
    public function getSegmentOrder(): int;
    public function getSegmentCreatedAt(): \DateTime;
    public function getSegmentUpdatedAt(): \DateTime;
    public function getPracticeRecordId(): int;
    public function getPracticeRecordSegmentId(): int;
    public function getPracticeRecordUserId(): int;
    public function getPracticeRecordSessionUuid(): string;
    public function getPracticeRecordTempoMultiplier(): float;
    public function getPracticeRecordCreatedAt(): \DateTime;
    public function getPracticeRecordUpdatedAt(): \DateTime;
    public function getPracticeRecordScore(): int;
}
