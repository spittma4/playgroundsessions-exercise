<?php
declare(strict_types=1);

namespace App\ViewModels\GetStudentProgress;

class LessonResponse implements \IteratorAggregate
{
    /** @param Segment[] $segments */
    public function __construct(
        public int $id,
        public string $name,
        public $difficulty,
        public bool $isComplete,
    )
    {
        $this->difficulty = $this->difficultyIntegerToCategory($this->difficulty);
    }

    public function getIterator()
    {
        return new \ArrayIterator($this);
    }

    /**
     * This function converts an integer difficult rating into a string phrase representation
     *
     * @param int $difficultyInteger
     * @return string
     */
    public function difficultyIntegerToCategory(int $difficultyInteger): string {

        return match ($difficultyInteger) {
            1,2,3 => 'Rookie',
            4,5,6 => 'Intermediate',
            7,8,9,10 => 'Advanced',
            default => 'God Mode'
        };

    }
}
