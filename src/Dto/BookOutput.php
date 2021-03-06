<?php

declare(strict_types=1);

namespace App\Dto;

use ApiPlatform\Core\Annotation\ApiProperty;
use App\Entity\Author;
use Doctrine\Common\Collections\Collection;
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
     * @var Collection<Author>
     */
    public $authors; //todo
}