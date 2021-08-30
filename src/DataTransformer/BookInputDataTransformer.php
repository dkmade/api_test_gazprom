<?php

declare(strict_types=1);

namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Dto\BookOutput;
use App\Entity\Author;
use App\Entity\Book;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;

class BookInputDataTransformer implements DataTransformerInterface
{
    private $em;
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function transform($object, string $to, array $context = [])
    {
        $book = new Book();

        dump($object);

        foreach ($object->names as $bookName)
        {
            $book->addBookName($bookName);
        }
        foreach ($object->authors as $author)
        {
            $authorEntity = $this->em->getRepository(Author::class)->createQueryBuilder('a')
                ->andWhere('LOWER(a.name) = LOWER(:name)')
                ->setParameter('name', $author->getName())
                ->getQuery()
                ->getOneOrNullResult()
            ;
            if (!$authorEntity) {
                $authorEntity = $author;
            }
            dump($author);
            $book->addAuthor($authorEntity);
        }
dump($book);
        return $book;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        if ($data instanceof Book) {
            return false;
        }

        return Book::class === $to && null !== ($context['input']['class'] ?? null);
    }
}