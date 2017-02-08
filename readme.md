# Masterstream Deploy Application

### Installation 
##### Complete following steps in order 
(If errors occur at any step, stop and troubleshoot error before continuing to next step)

1. Run Command: 

```
    git clone https://github.com/mwitt9999/masterstream-deploy.git /path/to/clone/location
```

2. Run Commands (from repo base directory):
```
    mkdir -p frontend/storage/logs
    mkdir -p frontend/storage/framework
    mkdir -p frontend/storage/cache
    mkdir -p frontend/storage/views
    chown -R www-data:www-data frontend/storage   
```

3. Run Command (from repo base directory):

```
    docker-compose up --build
```

4. SSH Into Container:

```
    docker exec -it masterstream_deploy_app /bin/bash
```

5. Run Commands:

```
    cd frontend
    composer install 
    nodejs install
    php artisan migrate
```

6. Copy .env.example to .env

```
    cp .env.example .env
```

7. Edit .env and update these variables

```
    MAIN_DOCKER_IP_ADDRESS=172.19.0.4

    GITHUB_USERNAME=your_github_username
    GITHUB_PASSWORD=your_github_password
```

8. Exit docker container:

```
    exit
```

9. Run Command:

```
    sudo vim /etc/hosts
```

10. Paste to /etc/hosts:

```
    127.0.0.1 masterstream-deploy.app
```

11. visit masterstream-deploy.app in browser

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Laravel attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, yet powerful, providing tools needed for large, robust applications. A superb combination of simplicity, elegance, and innovation give you tools you need to build any application with which you are tasked.

## Learning Laravel

Laravel has the most extensive and thorough documentation and video tutorial library of any modern web application framework. The [Laravel documentation](https://laravel.com/docs) is thorough, complete, and makes it a breeze to get started learning the framework.

If you're not in the mood to read, [Laracasts](https://laracasts.com) contains over 900 video tutorials on a range of topics including Laravel, modern PHP, unit testing, JavaScript, and more. Boost the skill level of yourself and your entire team by digging into our comprehensive video library.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](http://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT).
