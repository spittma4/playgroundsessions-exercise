<?php
declare(strict_types=1);

namespace App\ViewModels\GetStudentProgress;

class LessonResponse implements \IteratorAggregate
{
    /** @param Segment[] $segments */
    public function __construct(
        public int $id,
        public string $name,
        public string $difficulty,
        public bool $isComplete,
    )
    {
    }

    public function getIterator()
    {
        return new \ArrayIterator($this);
    }
}
