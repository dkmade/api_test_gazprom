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
//    private $em;
//    private $request;
//    public function __construct(EntityManagerInterface $em, Request $request)
//    {
//        $this->em = $em;
//        $this->request = $request;
//    }

    public function transform($object, string $to, array $context = [])
    {
        $output = new BookOutput();

//        dump($object);

        $request = Request::createFromGlobals();
//        dump($request->getLocale());

        $output->name = $object->getBookNames()[0]->getName() .

            '  ----  ' . $object->getBookNames()[0]->getLocale()->getName();
        $output->id = $object->getId();
        $output->authors = $object->getAuthors();
//        dump($object);
        return $output;
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        return BookOutput::class === $to && $data instanceof Book;
    }
}