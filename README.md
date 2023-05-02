# Symfony API Demo

Простое Demo API приложение с использованием фреймворка Symfony.

Документация к апи находится по [ссылке](http://172.28.0.2/api/doc/v1) после запуска проекта

### Как пользоваться runner.sh

- Запуск:
```shell
sh runner.sh start
```

- Анализ кода (phpstan):
```shell
sh runner.sh cs:analyse
```

- Код стайл фиксер (php-cs-fixer):
```shell
sh runner.sh cs:fix
```

## TODO

* Доделать тесты
* Добавить пользователей + авторизацию
