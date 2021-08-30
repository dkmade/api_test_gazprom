<?php

declare(strict_types=1);

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Entity\Author;
use App\Entity\BookName;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Serializer\Annotation\Groups;

final class BookInput
{
    public int $id;
    /**
     * @Groups({"book:write"})
     * @var Collection<BookName>
     */
    public $names;
    /**
     * @Groups({"book:write"})
     * @var Collection<Author>
     */
    public $authors;
}