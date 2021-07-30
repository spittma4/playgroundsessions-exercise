<?php
declare(strict_types=1);

namespace App\ViewModels\LessonCsv;

use App\UseCases\DependencyInterfaces\LessonCsvInterface;

class LessonCsv implements LessonCsvInterface
{
    const LARAVEL_DEFAULT_DATE_FORMAT = 'Y-m-d\TH:i:s\.uT';

    private $fp;

    public function __construct()
    {
        $this->fp = fopen('../data.csv', 'r');
        $this->discardRow(); // The header has column names, not data
    }

    private function discardRow(): void
    {
        fgetcsv($this->fp);
    }

    public function getNextRow(): ?LessonCsvRow
    {
        $row = fgetcsv($this->fp);

        if ($row === false) {
            return null;
        }

        return new LessonCsvRow(
            lessonId: (int)$row[0],
            lessonName: (string)$row[1],
            lessonDescription: (string)$row[2],
            lessonDifficulty: (int)$row[3],
            lessonCreatedAt: \DateTime::createFromFormat(self::LARAVEL_DEFAULT_DATE_FORMAT, (string)$row[4]),
            lessonUpdatedAt: \DateTime::createFromFormat(self::LARAVEL_DEFAULT_DATE_FORMAT, (string)$row[5]),
            isLessonPublished: (bool)$row[6],
            segmentId: (int)$row[7],
            segmentLessonId: (int)$row[8],
            segmentName: (string)$row[9],
            segmentOrder: (int)$row[10],
            segmentCreatedAt: \DateTime::createFromFormat(self::LARAVEL_DEFAULT_DATE_FORMAT, (string)$row[11]),
            segmentUpdatedAt: \DateTime::createFromFormat(self::LARAVEL_DEFAULT_DATE_FORMAT, (string)$row[12]),
            practiceRecordId: (int)$row[13],
            practiceRecordSegmentId: (int)$row[14],
            practiceRecordUserId: (int)$row[15],
            practiceRecordSessionUuid: (string)$row[16],
            practiceRecordTempoMultiplier: (float)$row[17],
            practiceRecordCreatedAt: \DateTime::createFromFormat(self::LARAVEL_DEFAULT_DATE_FORMAT, (string)$row[18]),
            practiceRecordUpdatedAt: \DateTime::createFromFormat(self::LARAVEL_DEFAULT_DATE_FORMAT, (string)$row[19]),
            practiceRecordScore: (int)$row[20],
        );
    }
}
