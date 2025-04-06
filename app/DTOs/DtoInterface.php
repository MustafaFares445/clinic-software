<?php

namespace App\DTOs;

interface DtoInterface
{
    /**
     * Creates a new DTO instance from an array of data
     *
     * @param array $data Associative array containing data
     * @return self New DTO instance
     */
    public static function fromArray(array $data): self;

    /**
     * Converts the DTO instance to an array
     *
     * @return array Associative array containing data
     */
    public function toArray(): array;
}