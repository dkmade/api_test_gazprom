<?php

declare(strict_types=1);

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\BookOutput;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class BookOutputDataTransformer implements DataTransformerInterface
{
    public function transform($object, string $to, array $context = [])
    {
        $output = new BookOutput();
        $output->name = isset($object->getBookNames()[0]) ? $object->getBookNames()[0]->getName() : 'Нет перевода для этой локализации';
        $output->id = $object->getId();
        $output->authors = $object->getAuthors();
        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return BookOutput::class === $to && $data instanceof Book;
    }
}