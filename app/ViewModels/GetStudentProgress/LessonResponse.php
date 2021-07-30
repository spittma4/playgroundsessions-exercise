<?php
declare(strict_types=1);

namespace App\ViewModels\GetStudentProgress;

class LessonResponse implements \IteratorAggregate
{
    /** @param Segment[] $segments */
    public function __construct(
        public int $id,
        public string $name,
        public string $description,
        public int $difficulty,
        public \DateTime $createdAt,
        public \DateTime $updatedAt,
        public bool $isPublished,
        public array $segments,
    )
    {
    }

    public function getIterator()
    {
        return new \ArrayIterator($this);
    }
}
