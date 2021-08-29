<?php

declare(strict_types=1);

namespace App\Doctrine;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryCollectionExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Extension\QueryItemExtensionInterface;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Util\QueryNameGeneratorInterface;
use App\Entity\Book;
use App\Entity\Locale;
use Doctrine\ORM\QueryBuilder;
use Symfony\Component\HttpFoundation\RequestStack;

final class CurrentLocaleBookNameExtension implements QueryCollectionExtensionInterface, QueryItemExtensionInterface
{
    protected RequestStack $requestStack;

    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    public function applyToCollection(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, string $operationName = null)
    {
        $this->addQuery($queryBuilder, $resourceClass);
    }

    public function applyToItem(QueryBuilder $queryBuilder, QueryNameGeneratorInterface $queryNameGenerator, string $resourceClass, array $identifiers, string $operationName = null, array $context = [])
    {
        $this->addQuery($queryBuilder, $resourceClass);
    }

    private function addQuery(QueryBuilder $queryBuilder, string $resourceClass): void
    {
        if (Book::class !== $resourceClass) {
            return;
        }
        $request = $this->requestStack->getCurrentRequest();
        $locale = $request->getLocale();
//        $locale = 'ru';

        $em = $queryBuilder->getEntityManager();
        $locale = $em->getRepository(Locale::class)->findOneByLocale($locale);

        $rootAlias = $queryBuilder->getRootAliases()[0];

        $queryBuilder
            ->leftJoin(sprintf('%s.bookNames', $rootAlias), 'bn', \Doctrine\ORM\Query\Expr\Join::WITH, 'bn.locale = :locale')
            ->addSelect('bn')
            ->setParameter('locale', $locale);
    }
}