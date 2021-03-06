# helloSymfony Как развернуть проект:

1) База данных.
Используется PostgreSQL. Для работы необходимо установить postgresql, а также пакеты php: pgsql и pdo_pgsql.

2) Подключаем зависимости с помощью composer и создаём конфигурационный файл parameters.yml, как указано в Официальной документации Symfony: https://symfony.com/doc/current/setup.html#installing-an-existing-symfony-application
 
3) Создаём виртуальный хост в соответствии с официальной документацией Symfony: symfony.com/doc/current/setup/web_server_configuration.html
   
4) Заходим на http://myLocalhostName/config.php и следуем увиденным рекомендациям, если необходимо делаем поправки.

5) Редактируем настройки подключения к БД и разворачиваем схему:
   Как настроить: http://fkn.ktu10.com/?q=node/9469
   Как запустить автоматическую генерацию таблиц в БД: `php bin/console doctrine:schema:update --force`

6) Главная страница находится по адресу http://localhost/article/

# Что вы можете найти в данном проекте. 

Проект учебный, создан для изучения возможностей Symfony. Итак, что есть внутри:

1) Работа с сущностями и БД PostgreSQL. Миграции. (AppBundle/Entity)
2) Ассоциации (связанные таблицы по внешнему ключу) (AppBundle/Entity, IFFChatBundle/Entity)
3) Реистрация и аутентификация пользователя с помощью FOSUserBundle (AppBundle/Controller/RegistrationController.php и AppBundle/Controller/SecurityController.php)
4) Регистрация и аутентификация пользователя по смс (ajax) (AppBundle/Controller/RegistrationController.php ("/generateSmsCode") и web/js/sendSms.js)
5) Чат на ajax-e. На базе пользовательского пакета. (IFFChatBundle)

Symfony Standard Edition
========================

Welcome to the Symfony Standard Edition - a fully-functional Symfony
application that you can use as the skeleton for your new applications.

For details on how to download and get started with Symfony, see the
[Installation][1] chapter of the Symfony Documentation.

What's inside?
--------------

The Symfony Standard Edition is configured with the following defaults:

  * An AppBundle you can use to start coding;

  * Twig as the only configured template engine;

  * Doctrine ORM/DBAL;

  * Swiftmailer;

  * Annotations enabled for everything.

It comes pre-configured with the following bundles:

  * **FrameworkBundle** - The core Symfony framework bundle

  * [**SensioFrameworkExtraBundle**][6] - Adds several enhancements, including
    template and routing annotation capability

  * [**DoctrineBundle**][7] - Adds support for the Doctrine ORM

  * [**TwigBundle**][8] - Adds support for the Twig templating engine

  * [**SecurityBundle**][9] - Adds security by integrating Symfony's security
    component

  * [**SwiftmailerBundle**][10] - Adds support for Swiftmailer, a library for
    sending emails

  * [**MonologBundle**][11] - Adds support for Monolog, a logging library

  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar

  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

  * [**SensioGeneratorBundle**][13] (in dev env) - Adds code generation
    capabilities

  * [**WebServerBundle**][14] (in dev env) - Adds commands for running applications
    using the PHP built-in web server

  * **DebugBundle** (in dev/test env) - Adds Debug and VarDumper component
    integration

All libraries and bundles included in the Symfony Standard Edition are
released under the MIT or BSD license.

Enjoy!

[1]:  https://symfony.com/doc/3.3/setup.html
[6]:  https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
[7]:  https://symfony.com/doc/3.3/doctrine.html
[8]:  https://symfony.com/doc/3.3/templating.html
[9]:  https://symfony.com/doc/3.3/security.html
[10]: https://symfony.com/doc/3.3/email.html
[11]: https://symfony.com/doc/3.3/logging.html
[13]: https://symfony.com/doc/current/bundles/SensioGeneratorBundle/index.html
[14]: https://symfony.com/doc/current/setup/built_in_web_server.html

# helloSymfony
-----------

# Как развернуть проект:

1) База данных.
Используется PostgreSQL. Для работы необходимо установить ракеты pgsql и pdo_pgsql.

2) Подключаем зависимости с помощью composer

3) Редактируем настройки подключения к БД и разворачиваем схему:
   Как настроить: http://fkn.ktu10.com/?q=node/9469
   Как запустить автоматическую генерацию таблиц в БД: php bin/console doctrine:schema:update --force
