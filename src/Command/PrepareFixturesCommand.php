<?php

namespace App\Command;

use App\Entity\Author;
use App\Entity\Book;
use App\Entity\BookName;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class PrepareFixturesCommand extends Command
{
    protected static $defaultName = 'app:prepare-fixtures';
    protected static $defaultDescription = 'Подготовка данных для фикстур из БД';

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();
        $this->em = $em;
    }

    protected function configure(): void
    {
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        /** @var Author[] $authors */
        $authors = $this->em->getRepository(Author::class)->findBy([], ['id' => 'ASC']);
        $authorsArray = [];
        foreach ($authors as $author) {
            $authorsArray[$author->getId()] = $author->getName();
        }
        $json = json_encode($authorsArray);
        file_put_contents(dirname(__FILE__) . '/../DataFixtures/authors.json', $json);

        /** @var Book[] $books */
        $books = $this->em->getRepository(Book::class)->findBy([], ['id' => 'ASC']);
        $booksArray = [];
        foreach ($books as $book) {
            $booksArray[] = [
                'authors' => (function() use ($book) {
                    $ret = [];
                    foreach ($book->getAuthors() as $author) {
                        $ret[] = ['id' => $author->getId()];
                    }
                    return $ret;
            })(),
                'names' => (function() use ($book) {
                    $ret = [];
                    foreach ($book->getBookNames() as $bookName) {
                        $ret[] = ['name' => $bookName->getName(), 'locale' => $bookName->getLocale()->getLocale()];
                    }
                    return $ret;
                })(),

            ];
        }
        $json = json_encode($booksArray);
        file_put_contents(dirname(__FILE__) . '/../DataFixtures/books.json', $json);

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
