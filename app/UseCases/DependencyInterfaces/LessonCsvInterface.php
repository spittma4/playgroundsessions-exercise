<?php
declare(strict_types=1);

namespace App\UseCases\DependencyInterfaces;

interface LessonCsvInterface
{
    public function getNextRow(): ?LessonCsvRowInterface;
}
