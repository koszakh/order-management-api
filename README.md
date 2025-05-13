# API Управления Заказами и Исполнителями

RESTful API, разработанный на Laravel 11, для управления компаниями-партнерами, менеджерами, исполнителями, заказами и их типами. Включает аутентификацию через Laravel Passport и опциональную возможность real-time обновлений статусов заказов через Laravel Reverb (WebSockets).

## Оглавление

- [Требования](#требования)
- [Установка и Запуск](#установка-и-запуск)
  - [Настройка `.env`](#настройка-env)
  - [Запуск с Laravel Sail](#запуск-с-laravel-sail)
  - [Laravel Reverb (WebSockets)](#laravel-reverb-websockets)
- [Запуск Тестов](#запуск-тестов)
- [Документация по API Эндпоинтам](#документация-по-api-эндпоинтам)
  - [Аутентификация (Laravel Passport)](#аутентификация-laravel-passport)
  - [Заказы (Orders)](#заказы-orders)
  - [Исполнители (Workers)](#исполнители-workers)
- [Использование Laravel Sail](#использование-laravel-sail)
- [Клиентская часть (JavaScript)](#клиентская-часть-javascript)

## Требования

- Windows 10/11 с включенным WSL2 (Windows Subsystem for Linux 2)
- Docker Desktop установленный и настроенный для использования WSL2
- Git
- Node.js и NPM (для сборки фронтенд-ассетов, если будете использовать Echo)

## Установка и Запуск

1.  **Клонируйте репозиторий (если он уже существует):**
    ```bash
    git clone <URL_вашего_репозитория>
    cd <имя_директории_проекта>
    ```
    Или **создайте новый проект Laravel с нуля (если вы следовали инструкциям ранее):**
    ```bash
    curl -s "[https://laravel.build/order-management-api](https://laravel.build/order-management-api)" | bash
    cd order-management-api
    ```

2.  **Настройте алиас для Sail в WSL2 (рекомендуется):**
    Добавьте в ваш `~/.bashrc` или `~/.zshrc` (внутри WSL2):
    ```bash
    echo "alias sail='bash vendor/bin/sail'" >> ~/.bashrc && source ~/.bashrc
    ```

3.  **Настройка `.env`:**
    * Скопируйте файл `.env.example` в `.env`, если он не был создан автоматически:
        ```bash
        cp .env.example .env
        ```
    * **Laravel Sail обычно автоматически настраивает `.env` для работы с Docker.** Убедитесь, что следующие переменные корректны для вашей среды (особенно если вы не использовали `laravel.build`):
        ```dotenv
        APP_NAME="Order Management API"
        APP_ENV=local
        APP_KEY= # Будет сгенерирован
        APP_DEBUG=true
        APP_URL=http://localhost

        DB_CONNECTION=mysql
        DB_HOST=mysql
        DB_PORT=3306
        DB_DATABASE=order_management_api # Или ваше имя БД
        DB_USERNAME=sail
        DB_PASSWORD=password

        # Настройки для Reverb (если используете)
        BROADCAST_CONNECTION=reverb
        REVERB_APP_ID= # Заполнится после reverb:install
        REVERB_APP_KEY= # Заполнится после reverb:install
        REVERB_SECRET= # Заполнится после reverb:install
        REVERB_HOST=localhost
        REVERB_PORT=8080
        REVERB_SCHEME=http

        # Переменные для Vite (используются в resources/js/bootstrap.js)
        VITE_REVERB_APP_KEY="${REVERB_APP_KEY}"
        VITE_REVERB_HOST="${REVERB_HOST}"
        VITE_REVERB_PORT="${REVERB_PORT}"
        VITE_REVERB_SCHEME="${REVERB_SCHEME}"
        ```

4.  **Запуск с Laravel Sail:**
    * Запустите Docker Desktop на Windows.
    * В терминале WSL2, в директории проекта, выполните:
        ```bash
        sail up -d
        ```
    * Установите зависимости Composer:
        ```bash
        sail composer install
        ```
    * Сгенерируйте ключ приложения Laravel (если еще не сгенерирован):
        ```bash
        sail artisan key:generate
        ```
    * Выполните миграции базы данных и наполните ее начальными данными (сидеры):
        ```bash
        sail artisan migrate --seed
        ```
    * Установите Laravel Passport (создаст ключи и клиентов):
        ```bash
        sail artisan passport:install
        ```

5.  **Приложение будет доступно по адресу:** `http://localhost` (или другой порт, если 80-й занят, Sail выведет актуальный адрес).

6.  **Laravel Reverb (WebSockets) - если используется:**
    * Убедитесь, что Reverb установлен:
        ```bash
        sail composer require laravel/reverb
        sail artisan reverb:install
        sail artisan migrate
        ```
    * Запустите сервер Reverb в отдельном терминале WSL2:
        ```bash
        sail artisan reverb:start --host=0.0.0.0 --port=8080
        ```
        (Убедитесь, что порт 8080 проброшен из контейнера на хост-машину. Sail обычно делает это по умолчанию для стандартных сервисов.)

## Запуск Тестов

Выполните команду для запуска PHPUnit тестов:
```bash
sail artisan test