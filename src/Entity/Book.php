<?php

declare(strict_types=1);

namespace App\Entity;

use ApiPlatform\Core\Annotation\ApiResource;
use App\Repository\BookRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use App\Dto\BookOutput;
use App\Dto\BookInput;
use ApiPlatform\Core\Bridge\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Core\Annotation\ApiFilter;
use Doctrine\ORM\PersistentCollection;

/**
 * @ORM\Entity(repositoryClass=BookRepository::class)
 * @ApiResource(
 *     normalizationContext={"groups"={"book:read"}},
 *     denormalizationContext={"groups"={"book:write"}},
 *     collectionOperations={
 *          "get"={
 *              "path"="{_locale}/api/books/",
 *              "method"="GET",
 *              "openapi_context" = {
 *                  "parameters" = {
 *                      {
 *                          "name" = "_locale",
 *                          "in" = "path",
 *                          "description" = "Язык",
 *                          "required" = true,
 *                          "schema" = {"type" = "string", "enum"={"ru", "en"}, "example"="ru"}
 *                      }
 *                  }
 *               }
 *          },
 *          "post"={
 *              "method"="POST",
 *              "path"="/api/books/",
 *              "openapi_context" = {
 *                   "requestBody" = {
 *                        "content" = {
 *                              "application/json" = {
 *                                  "schema" = {
 *                                       "type" = "object",
 *                                       "properties" = {
 *                                            "names" = {"type" = "string"}
 *                                        }
 *                                   },
 *                                  "examle" = {

 *                                            "names" = "eee"

 *                                   }
 *                               }
 *                         }
 *                   }
 *               }
 *          }
 *     },
 *     itemOperations={
 *          "get"={
 *              "path"="{_locale}/api/books/{id}",
 *              "method"="GET",
 *              "openapi_context" = {
 *                  "parameters" = {
 *                      {
 *                          "name" = "_locale",
 *                          "in" = "path",
 *                          "description" = "Язык",
 *                          "required" = true,
 *                          "schema" = {"type" = "string", "enum"={"ru", "en"}, "example"="ru"}
 *                      }
 *                  }
 *               }
 *          },
 *     },
 *     output=BookOutput::class,
 *     input=BookInput::class,
 * )
 * @ApiFilter(SearchFilter::class, properties={"bookNames.name":"ipartial"})
 */
final class Book
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToMany(targetEntity=Author::class, inversedBy="books", cascade={"persist"})
     */
    private Collection $authors;

    /**
     * @ORM\OneToMany(targetEntity=BookName::class, mappedBy="book", orphanRemoval=true, cascade={"persist"})
     */
    private Collection $bookNames;

    public function __construct()
    {
        $this->authors = new ArrayCollection();
        $this->bookNames = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection|Author[]
     */
    public function getAuthors(): Collection
    {
        return $this->authors;
    }

    public function addAuthor(Author $author): self
    {
        if (!$this->authors->contains($author)) {
            $this->authors[] = $author;
        }

        return $this;
    }

    public function removeAuthor(Author $author): self
    {
        $this->authors->removeElement($author);

        return $this;
    }

    /**
     * @return Collection|BookName[]
     */
    public function getBookNames(): Collection
    {
        return $this->bookNames;
    }

    public function addBookName(BookName $bookName): self
    {
        if (!$this->bookNames->contains($bookName)) {
            $this->bookNames[] = $bookName;
            $bookName->setBook($this);
        }

        return $this;
    }

    public function removeBookName(BookName $bookName): self
    {
        if ($this->bookNames->removeElement($bookName)) {
            // set the owning side to null (unless already changed)
            if ($bookName->getBook() === $this) {
                $bookName->setBook(null);
            }
        }

        return $this;
    }

}
