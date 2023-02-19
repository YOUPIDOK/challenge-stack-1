
# Challenge stack 1
> Symfony 6.1
## Documentation
### Requirement
* PHP ``8.1``
* Node ``16``
### Install
```shell
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
yarn install
yarn build
```
### Commands
#### Tool
```shell
php bin/console user:create # Create user
```