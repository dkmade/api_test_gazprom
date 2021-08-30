Этот вариант реализован с использованием api platform

    $ git clone https://github.com/dkmade/api_test_gazprom.git
    $ cd api_test_gazprom
    $ docker-compose up -d
    $ docker-compose exec php symfony composer install
    $ docker-compose exec php symfony console d:m:m -n
    $ docker-compose exec php symfony console doctrine:fixtures:load --group=dev

http://127.0.0.1/docs

Для создания книги можно использовать такой json (поленился в документации реализовать)

    {
      "names": [
        {
          "locale": "/api/locales/1",
          "name": "руское название книги"
        },
        {
          "locale": "/api/locales/2",
          "name": "english name of book"
        }
      ],
      "authors": [
          "/api/authors/1",
          "/api/authors/2",
          {"name": "Новый автор, которого нет в базе"},
          {"name": "Братья Гримм"}
      ]
    }

"/api/authors/1" - это ссылки на существующие ресурсы
также реализовал возможность добавления автора на лету,
а если он уже существует, то будет отношение с существующим

тесты реализовывал только в другой версии, которая использует только JMS сериализатор 
