# Course Online

Course Online is a platform of tutorials in internet with management of roles, users, qualifications, permissions, comments and enrollments. 

## Installation

use git command to install Course-Online.

```bash
git clone https://github.com/juanmac09/online-course.git
```

## Usage
In order to use the project you have to adjust the following environment variables.


```php
DB_CONNECTION=mysql
DB_HOST=mysql
DB_PORT=3306
DB_DATABASE=course-online
DB_USERNAME=username
DB_PASSWORD=passwordExample


REDIS_CLIENT=phpredis
REDIS_HOST=host
REDIS_PASSWORD=password
REDIS_PORT=12345
REDIS_USERNAME=userna,e


MAIL_MAILER=smtp
MAIL_HOST=host
MAIL_PORT=123
MAIL_USERNAME=username
MAIL_PASSWORD=password
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="email@gmail.com"
MAIL_FROM_NAME="${APP_NAME}"

AWS_ACCESS_KEY_ID=id
AWS_SECRET_ACCESS_KEY=secret_key
AWS_DEFAULT_REGION=region
AWS_BUCKET=cucket
AWS_USE_PATH_STYLE_ENDPOINT=false
PATH_CONTENT=path_content

VITE_APP_NAME="${APP_NAME}"


AUTH_GUARD=api

JWT_SECRET=jwt_secret

JWT_ALGO=HS256
```

```bash
composer install

php artisan serve
```

## Url

Access localhost on the port you directed for the app

```bash
Access the following route to view all Endpoints /api/documentation
```

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).