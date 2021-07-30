<?php
declare(strict_types=1);

use App\UseCases\DependencyInterfaces\LessonCsvInterface;
use App\UseCases\DependencyInterfaces\LessonCsvRowInterface;
use App\UseCases\GetStudentProgressUseCase;
use App\ViewModels\LessonCsv\LessonCsvRow;

class GetStudentUseCaseTest extends \PHPUnit\Framework\TestCase
{
    public function testSingleLessonAndSegmentAndPracticeRecord(): void
    {
        $result = (new GetStudentProgressUseCase(new MockCsvWithOneRow()))
            ->getAllLessons(1);

        $this->assertCount(1, $result);
        $this->assertCount(1, $result[0]->segments);
        $this->assertCount(1, $result[0]->segments[0]->practiceRecords);
    }

    public function testOneLessonWithOneSegmentAndTwoPracticeRecords(): void
    {
        $result = (new GetStudentProgressUseCase(new MockCsvWithOneLessonAndOneSegmentAndTwoPracticeRecords()))
            ->getAllLessons(1);

        $this->assertCount(1, $result);
        $this->assertCount(1, $result[0]->segments);
        $this->assertCount(2, $result[0]->segments[0]->practiceRecords);
    }

    public function testOneLessonWithOneSegmentAndThreePracticeRecords(): void
    {
        $result = (new GetStudentProgressUseCase(new MockCsvWithOneLessonAndOneSegmentAndThreePracticeRecords()))
            ->getAllLessons(1);

        $this->assertCount(1, $result);
        $this->assertCount(1, $result[0]->segments);
        $this->assertCount(3, $result[0]->segments[0]->practiceRecords);
    }

    public function testOneLessonWithTwoSegmentsEachWithOnePracticeRecord(): void
    {
        $result = (new GetStudentProgressUseCase(new MockCsvWithOneLessonAndTwoSegmentsAndOnePracticeRecordInEachSegment()))
            ->getAllLessons(1);

        $this->assertCount(1, $result);
        $this->assertCount(2, $result[0]->segments);
        $this->assertCount(1, $result[0]->segments[0]->practiceRecords);
        $this->assertCount(1, $result[0]->segments[1]->practiceRecords);
    }

    public function testTwoLessonsEachWithOneSegmentAndOnePracticeRecord(): void
    {
        $result = (new GetStudentProgressUseCase(new MockCsvWithTwoLessonsEachWithOneSegmentsAndOnePracticeRecord()))
            ->getAllLessons(1);

        $this->assertCount(2, $result);
        $this->assertCount(1, $result[0]->segments);
        $this->assertCount(1, $result[0]->segments[0]->practiceRecords);
        $this->assertCount(1, $result[1]->segments);
        $this->assertCount(1, $result[1]->segments[0]->practiceRecords);
    }

    public function testTwoLessonsEachWithTwoSegmentsEachWithOnePracticeRecord(): void
    {
        $result = (new GetStudentProgressUseCase(new MockCsvWithTwoLessonsEachWithTwoSegmentsEachWithOnePracticeRecord()))
            ->getAllLessons(1);

        $this->assertCount(2, $result);
        $this->assertCount(2, $result[0]->segments);
        $this->assertCount(2, $result[1]->segments);
        $this->assertCount(1, $result[0]->segments[0]->practiceRecords);
        $this->assertCount(1, $result[0]->segments[1]->practiceRecords);
        $this->assertCount(1, $result[1]->segments[0]->practiceRecords);
        $this->assertCount(1, $result[1]->segments[1]->practiceRecords);
    }

    public function testTwoLessonsEachWithOneSegmentsEachWithTwoPracticeRecords(): void
    {
        $result = (new GetStudentProgressUseCase(new MockCsvWithTwoLessonsEachWithOneSegmentsEachWithTwoPracticeRecord()))
            ->getAllLessons(1);

        $this->assertCount(2, $result);
        $this->assertCount(1, $result[0]->segments);
        $this->assertCount(1, $result[1]->segments);
        $this->assertCount(2, $result[0]->segments[0]->practiceRecords);
        $this->assertCount(2, $result[1]->segments[0]->practiceRecords);
    }

    public function testThreeLessonsEachWithOneSegmentAndOnePracticeRecord(): void
    {
        $result = (new GetStudentProgressUseCase(new MockCsvWithThreeLessonsEachWithOneSegmentsAndOnePracticeRecord()))
            ->getAllLessons(1);

        $this->assertCount(3, $result);
        $this->assertCount(1, $result[0]->segments);
        $this->assertCount(1, $result[0]->segments[0]->practiceRecords);
        $this->assertCount(1, $result[1]->segments);
        $this->assertCount(1, $result[1]->segments[0]->practiceRecords);
        $this->assertCount(1, $result[2]->segments);
        $this->assertCount(1, $result[2]->segments[0]->practiceRecords);
    }
}

class MockCsvWithOneRow implements LessonCsvInterface
{
    private int $row = 0;

    public function getNextRow(): ?LessonCsvRowInterface
    {
        $this->row++;

        if ($this->row > 1) {
            return null;
        }

        return MockCsvRowFactory::create(
            lessonId: 1,
            segmentId: 1,
            practiceRecordId: 1,
        );
    }
}

class MockCsvWithOneLessonAndOneSegmentAndTwoPracticeRecords implements LessonCsvInterface
{
    private int $row = 0;

    public function getNextRow(): ?LessonCsvRowInterface
    {
        $this->row++;

        if ($this->row > 2) {
            return null;
        }

        if ($this->row == 1) {
            return MockCsvRowFactory::create(
                lessonId: 1,
                segmentId: 1,
                practiceRecordId: 1,
            );
        }

        return MockCsvRowFactory::create(
            lessonId: 1,
            segmentId: 1,
            practiceRecordId: 2,
        );
    }
}

class MockCsvWithOneLessonAndOneSegmentAndThreePracticeRecords implements LessonCsvInterface
{
    private int $row = 0;

    public function getNextRow(): ?LessonCsvRowInterface
    {
        $this->row++;

        if ($this->row > 3) {
            return null;
        }

        if ($this->row == 1) {
            return MockCsvRowFactory::create(
                lessonId: 1,
                segmentId: 1,
                practiceRecordId: 1,
            );
        }

        if ($this->row == 2) {
            return MockCsvRowFactory::create(
                lessonId: 1,
                segmentId: 1,
                practiceRecordId: 2,
            );
        }

        return MockCsvRowFactory::create(
            lessonId: 1,
            segmentId: 1,
            practiceRecordId: 3,
        );
    }
}

class MockCsvWithOneLessonAndTwoSegmentsAndOnePracticeRecordInEachSegment implements LessonCsvInterface
{
    private int $row = 0;

    public function getNextRow(): ?LessonCsvRowInterface
    {
        $this->row++;

        if ($this->row > 2) {
            return null;
        }

        if ($this->row == 1) {
            return MockCsvRowFactory::create(
                lessonId: 1,
                segmentId: 1,
                practiceRecordId: 1,
            );
        }

        return MockCsvRowFactory::create(
            lessonId: 1,
            segmentId: 2,
            practiceRecordId: 2,
        );
    }
}

class MockCsvWithTwoLessonsEachWithOneSegmentsAndOnePracticeRecord implements LessonCsvInterface
{
    private int $row = 0;

    public function getNextRow(): ?LessonCsvRowInterface
    {
        $this->row++;

        if ($this->row > 2) {
            return null;
        }

        if ($this->row == 1) {
            return MockCsvRowFactory::create(
                lessonId: 1,
                segmentId: 1,
                practiceRecordId: 1,
            );
        }

        return MockCsvRowFactory::create(
            lessonId: 2,
            segmentId: 2,
            practiceRecordId: 2,
        );
    }
}

class MockCsvWithTwoLessonsEachWithTwoSegmentsEachWithOnePracticeRecord implements LessonCsvInterface
{
    private int $row = 0;

    public function getNextRow(): ?LessonCsvRowInterface
    {
        $this->row++;

        if ($this->row > 4) {
            return null;
        }

        if ($this->row == 1) {
            return MockCsvRowFactory::create(
                lessonId: 1,
                segmentId: 1,
                practiceRecordId: 1,
            );
        }

        if ($this->row == 2) {
            return MockCsvRowFactory::create(
                lessonId: 1,
                segmentId: 2,
                practiceRecordId: 2,
            );
        }

        if ($this->row == 3) {
            return MockCsvRowFactory::create(
                lessonId: 2,
                segmentId: 3,
                practiceRecordId: 3,
            );
        }

        return MockCsvRowFactory::create(
            lessonId: 2,
            segmentId: 4,
            practiceRecordId: 4,
        );
    }
}

class MockCsvWithTwoLessonsEachWithOneSegmentsEachWithTwoPracticeRecord implements LessonCsvInterface
{
    private int $row = 0;

    public function getNextRow(): ?LessonCsvRowInterface
    {
        $this->row++;

        if ($this->row > 4) {
            return null;
        }

        if ($this->row == 1) {
            return MockCsvRowFactory::create(
                lessonId: 1,
                segmentId: 1,
                practiceRecordId: 1,
            );
        }

        if ($this->row == 2) {
            return MockCsvRowFactory::create(
                lessonId: 1,
                segmentId: 1,
                practiceRecordId: 2,
            );
        }

        if ($this->row == 3) {
            return MockCsvRowFactory::create(
                lessonId: 2,
                segmentId: 2,
                practiceRecordId: 3,
            );
        }

        return MockCsvRowFactory::create(
            lessonId: 2,
            segmentId: 2,
            practiceRecordId: 4,
        );
    }
}

class MockCsvWithThreeLessonsEachWithOneSegmentsAndOnePracticeRecord implements LessonCsvInterface
{
    private int $row = 0;

    public function getNextRow(): ?LessonCsvRowInterface
    {
        $this->row++;

        if ($this->row > 3) {
            return null;
        }

        if ($this->row == 1) {
            return MockCsvRowFactory::create(
                lessonId: 1,
                segmentId: 1,
                practiceRecordId: 1,
            );
        }

        if ($this->row == 2) {
            return MockCsvRowFactory::create(
                lessonId: 2,
                segmentId: 2,
                practiceRecordId: 2,
            );
        }

        return MockCsvRowFactory::create(
            lessonId: 3,
            segmentId: 3,
            practiceRecordId: 3,
        );
    }
}

class MockCsvRowFactory
{
    public static function create(
        int $lessonId,
        int $segmentId,
        int $practiceRecordId,
    ): LessonCsvRow
    {
        $userId = 1;

        return new LessonCsvRow(
            lessonId: $lessonId,
            lessonName: '',
            lessonDescription: '',
            lessonDifficulty: 1,
            lessonCreatedAt: new DateTime(),
            lessonUpdatedAt: new DateTime(),
            isLessonPublished: false,
            segmentId: $segmentId,
            segmentLessonId: $lessonId,
            segmentName: '',
            segmentOrder: 1,
            segmentCreatedAt: new DateTime(),
            segmentUpdatedAt: new DateTime(),
            practiceRecordId: $practiceRecordId,
            practiceRecordSegmentId: $segmentId,
            practiceRecordUserId: $userId,
            practiceRecordSessionUuid: '',
            practiceRecordTempoMultiplier: 0,
            practiceRecordCreatedAt: new DateTime(),
            practiceRecordUpdatedAt: new DateTime(),
            practiceRecordScore: 0,
        );
    }
}
