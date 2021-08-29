<?php

declare(strict_types=1);

namespace App\Dto;

use App\Entity\Author;
use Symfony\Component\Serializer\Annotation\Groups;

final class BookOutput
{
    /**
     * @Groups({"book:read"})
     */
    public int $id;
    /**
     * @Groups({"book:read"})
     */
    public string $name;
    /**
     * @Groups({"book:read"})
     */
    public $authors; //todo
}