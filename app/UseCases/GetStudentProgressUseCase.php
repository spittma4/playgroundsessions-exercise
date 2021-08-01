<?php
declare(strict_types=1);

namespace App\UseCases;

use App\UseCases\DependencyInterfaces\LessonCsvInterface;
use App\UseCases\DependencyInterfaces\LessonCsvRowInterface;
use App\ViewModels\GetStudentProgress\CurrentLesson;
use App\ViewModels\GetStudentProgress\CurrentSegment;
use App\ViewModels\GetStudentProgress\LessonResponse;
use App\ViewModels\GetStudentProgress\PracticeRecord;
use App\ViewModels\GetStudentProgress\Segment;

use Illuminate\Support\Facades\Log;

class GetStudentProgressUseCase
{
    public function __construct(private LessonCsvInterface $csv)
    {
    }

    /**
     * This function processes the given data csv file and returns lessons with difficulty ratings and completion status' for a given $userId
     *
     * @param int $userId
     *
     * @return array of \App\ViewModels\GetStudentProgress\LessonResponse[]
    */
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

            // If a CurrentLesson object has not been instantiated yet (first row), create one
            if (!isset($currentLesson) || !isset($currentSegment)) {
                // Initialize the new lesson
                $currentLesson = new CurrentLesson(
                    id: $currentRow->getLessonId(),
                    name: $currentRow->getLessonName(),
                    difficulty: self::difficultyIntegerToCategory($currentRow->getLessonDifficulty()),
                    // Assuming it was complete unless proven otherwise (makes processing easier)
                    isComplete: true
                );

                // Initialize the new segment for the new lesson
                $currentSegment = new CurrentSegment(
                    id: $currentRow->getSegmentId(),
                    lessonId: $currentLesson->id,
                    hasPassingPracticeRecord: false
                );

            }
            // Otherwise, check if we're processing PracticeRecords for the same lesson, if not, we need to do a few things:
            // 1. Push this record onto the $lessons array since we're done processing progress for the current lesson
            // 2. Update CurrentSegment to represent the new lesson/segment (do this before updating lesson)
            // 3. Update the CurrentLesson to represent the new lesson this row identifies
            else {

                // Check if we're processing PracticeRecords for the same segment as $currentSegment
                // TODO: Convert to use functions below made for this
                if ($currentSegment->id !== $currentRow->getSegmentId()) {
                    // Since we're done processing this segment, if the currentSegment doesn't have a passing score, update $currentLessons flag (1 failing segment fails the lesson)
                    if ($currentSegment->hasPassingPracticeRecord === false) {
                        $currentLesson->isComplete = false;
                    }

                    // Update CurrentSegment to represent the new lesson/segment
                    $currentSegment->id = $currentRow->getSegmentId();
                    $currentSegment->lessonId = $currentLesson->id;
                    $currentSegment->hasPassingPracticeRecord = false;
                }

                // Check if we're still processing PracticeRecords for the same lesson as $currentLesson
                // If not, we need to push $currentLesson onto $lessons for the response
                // TODO: Convert to use functions below made for this
                if ($currentLesson->id !== $currentRow->getLessonId()) {
                    $lessons[] = self::createLessonResponseFromCurrentLesson($currentLesson);

                    // Update the CurrentLesson to represent the new lesson this row identifies
                    $currentLesson->id = $currentRow->getLessonId();
                    $currentLesson->name = $currentRow->getLessonName();
                    $currentLesson->difficulty = self::difficultyIntegerToCategory($currentRow->getLessonDifficulty());
                    $currentLesson->isComplete = true;
                }
            }

            // Check if the current PracticeRecord has a passing score
            if ($currentRow->getPracticeRecordScore() >= 80) {
                $currentSegment->hasPassingPracticeRecord = true;
            }

//            $isSameLesson = self::isRowForLessonId($currentRow, $previousRow->getLessonId());
//            $isSameSegment = self::isRowForSegmentId($currentRow, $previousRow->getSegmentId());
//
//            $practiceRecords[] = self::createPracticeRecordFromRow($previousRow);
//
//            if (!$isSameSegment) {
//                $segments[] = self::createSegmentFromRow($previousRow, $practiceRecords ?? []);
//                $practiceRecords = [];
//            }
//
//            if (!$isSameLesson) {
//                $lessons[] = self::createLessonResponseFromRow($previousRow, $segments ?? []);
//                $segments = [];
//            }
//
//            $secondToLastRow = $previousRow ?? null;
//            $previousRow = $currentRow;

        }

//        $isZeroRows = is_null($previousRow);
//
//        if ($isZeroRows) {
//            return [];
//        }
//
//        $lastRow = $previousRow;
//
//        $isOnlyOneRow = is_null($secondToLastRow);
//
//        $lastPracticeRecord = self::createPracticeRecordFromRow($lastRow);
//
//        if ($isOnlyOneRow) {
//            $segment = self::createSegmentFromRow($lastRow, [$lastPracticeRecord]);
//            return [self::createLessonResponseFromRow($lastRow, [$segment])];
//        }
//
//        $isTheLastRowForTheSameSegmentAsItsPreviousRow = self::isForSameSegment($lastRow, $secondToLastRow);
//
//        if ($isTheLastRowForTheSameSegmentAsItsPreviousRow) {
//            $practiceRecords[] = $lastPracticeRecord;
//            $segments[] = self::createSegmentFromRow($lastRow, $practiceRecords);
//            $lessons[] = self::createLessonResponseFromRow($lastRow, $segments);
//        } else {
//            if (self::isForSameLesson($lastRow, $secondToLastRow)) {
//                $segments[] = self::createSegmentFromRow($lastRow, [$lastPracticeRecord]);
//                $lessons[] = self::createLessonResponseFromRow($lastRow, $segments);
//            } else {
//                $segment = self::createSegmentFromRow($lastRow, [$lastPracticeRecord]);
//                $lessons[] = self::createLessonResponseFromRow($lastRow, [$segment]);
//            }
//        }

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

    /**
     * This function creates a representation of a lesson for display on the front end when given a row from the data csv
     * @param LessonCsvRowInterface $row
     *
     * @return LessonResponse
    */
    public static function createLessonResponseFromCurrentLesson(CurrentLesson $currentLesson): LessonResponse
    {
        return new LessonResponse (
            id: $currentLesson->getLessonId(),
            name: $currentLesson->getLessonName(),
            difficulty: $currentLesson->getLessonDifficulty(),
            isComplete: $currentLesson->getLessonCompletionStatus(),
        );
    }


    /**
     * This function creates a representation of a segment for display on the front end when given a row from the data csv
     * @param LessonCsvRowInterface $row
     * @param array $practiceRecords
     *
     * @return Segment
     */
    public static function createSegmentFromRow(LessonCsvRowInterface $row, array $practiceRecords): Segment
    {
        return new Segment (
            id: $row->getSegmentId(),
            lessonId: $row->getSegmentLessonId(),
            name: $row->getSegmentName(),
            order: $row->getSegmentOrder(),
            createdAt: $row->getSegmentCreatedAt(),
            updatedAt: $row->getSegmentUpdatedAt(),
            practiceRecords: $practiceRecords,
        );
    }

    /**
     * This function creates a representation of a practice record for display on the front end when given a row from the data csv
     * @param LessonCsvRowInterface $row
     *
     * @return PracticeRecord
     */
    public static function createPracticeRecordFromRow(LessonCsvRowInterface $row): PracticeRecord
    {
        return new PracticeRecord (
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
