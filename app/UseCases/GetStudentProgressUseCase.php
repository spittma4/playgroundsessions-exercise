<?php
declare(strict_types=1);

namespace App\UseCases;

use App\UseCases\DependencyInterfaces\LessonCsvInterface;
use App\UseCases\DependencyInterfaces\LessonCsvRowInterface;
use App\ViewModels\GetStudentProgress\LessonResponse;
use App\ViewModels\GetStudentProgress\PracticeRecord;
use App\ViewModels\GetStudentProgress\Segment;

class GetStudentProgressUseCase
{
    public function __construct(private LessonCsvInterface $csv)
    {
    }

    /** @return \App\ViewModels\GetStudentProgress\LessonResponse[] */
    public function getAllLessons(int $userId): array
    {
        $previousRow = null;
        $secondToLastRow = null;

        /** @var LessonResponse[] $lessons */
        $lessons = [];
        $segments = [];
        $practiceRecords = [];

        while ($currentRow = $this->csv->getNextRow()) {
            $isForCurrentUser = $currentRow->getPracticeRecordUserId() === $userId;

            if (!$isForCurrentUser) {
                continue;
            }

            if (is_null($previousRow)) {
                $previousRow = $currentRow;
                continue;
            }

            $isSameLesson = self::isRowForLessonId($currentRow, $previousRow->getLessonId());
            $isSameSegment = self::isRowForSegmentId($currentRow, $previousRow->getSegmentId());

            $practiceRecords[] = self::createPracticeRecordFromRow($previousRow);

            if (!$isSameSegment) {
                $segments[] = self::createSegmentFromRow($previousRow, $practiceRecords ?? []);
                $practiceRecords = [];
            }

            if (!$isSameLesson) {
                $lessons[] = self::createLessonResponseFromRow($previousRow, $segments ?? []);
                $segments = [];
            }

            $secondToLastRow = $previousRow ?? null;
            $previousRow = $currentRow;

        }

        $isZeroRows = is_null($previousRow);

        if ($isZeroRows) {
            return [];
        }

        $lastRow = $previousRow;

        $isOnlyOneRow = is_null($secondToLastRow);

        $lastPracticeRecord = self::createPracticeRecordFromRow($lastRow);

        if ($isOnlyOneRow) {
            $segment = self::createSegmentFromRow($lastRow, [$lastPracticeRecord]);
            return [self::createLessonResponseFromRow($lastRow, [$segment])];
        }

        $isTheLastRowForTheSameSegmentAsItsPreviousRow = self::isForSameSegment($lastRow, $secondToLastRow);

        if ($isTheLastRowForTheSameSegmentAsItsPreviousRow) {
            $practiceRecords[] = $lastPracticeRecord;
            $segments[] = self::createSegmentFromRow($lastRow, $practiceRecords);
            $lessons[] = self::createLessonResponseFromRow($lastRow, $segments);
        } else {
            if (self::isForSameLesson($lastRow, $secondToLastRow)) {
                $segments[] = self::createSegmentFromRow($lastRow, [$lastPracticeRecord]);
                $lessons[] = self::createLessonResponseFromRow($lastRow, $segments);
            } else {
                $segment = self::createSegmentFromRow($lastRow, [$lastPracticeRecord]);
                $lessons[] = self::createLessonResponseFromRow($lastRow, [$segment]);
            }
        }

        return $lessons;
    }

    public static function isRowForLessonId(LessonCsvRowInterface $row, int $lessonId): bool
    {
        return $row->getLessonId() === $lessonId;
    }

    public static function isRowForSegmentId(LessonCsvRowInterface $row, int $segmentId): bool
    {
        return $row->getSegmentId() === $segmentId;
    }

    public static function isForSameLesson(LessonCsvRowInterface $rowA, LessonCsvRowInterface $rowB): bool
    {
        return $rowA->getLessonId() === $rowB->getLessonId();
    }

    public static function isForSameSegment(LessonCsvRowInterface $rowA, LessonCsvRowInterface $rowB): bool
    {
        return $rowA->getSegmentId() === $rowB->getSegmentId();
    }

    public static function createLessonResponseFromRow(LessonCsvRowInterface $row, array $segments): LessonResponse
    {
        return new LessonResponse(
            id: $row->getLessonId(),
            name: $row->getLessonName(),
            difficulty: self::difficultyIntegerToCategory($row->getLessonDifficulty()),
            isComplete: false
        );
    }

    public static function createSegmentFromRow(LessonCsvRowInterface $row, array $practiceRecords): Segment
    {
        return new Segment(
            id: $row->getSegmentId(),
            lessonId: $row->getSegmentLessonId(),
            name: $row->getSegmentName(),
            order: $row->getSegmentOrder(),
            createdAt: $row->getSegmentCreatedAt(),
            updatedAt: $row->getSegmentUpdatedAt(),
            practiceRecords: $practiceRecords,
        );
    }

    public static function createPracticeRecordFromRow(LessonCsvRowInterface $row): PracticeRecord
    {
        return new PracticeRecord(
            id: $row->getPracticeRecordId(),
            segmentId: $row->getPracticeRecordSegmentId(),
            userId: $row->getPracticeRecordUserId(),
            sessionUuid: $row->getPracticeRecordSessionUuid(),
            tempoMultiplier: $row->getPracticeRecordTempoMultiplier(),
            createdAt: $row->getPracticeRecordCreatedAt(),
            updatedAt: $row->getPracticeRecordUpdatedAt(),
            score: $row->getPracticeRecordScore(),
        );
    }

    /**
     * This function converts an integer difficult rating into a string phrase representation
     *
     * @param int $difficultyInteger
     * @return string
     */
    public static function difficultyIntegerToCategory(int $difficultyInteger): string {

        return match ($difficultyInteger) {
            1,2,3 => 'Rookie',
            4,5,6 => 'Intermediate',
            7,8,9,10 => 'Advanced',
            default => 'God Mode'
        };

    }
}
