# Challenge stack 1
> https://challenge-stack-1.pidok-server.fr
## Documentation
### Requirement
* PHP ``8.1``
* Node ``16``
### Install
```shell
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load            # Peut être un peu long à cause du hash des passwords et de la masse de données généré
yarn install
yarn build
```
### Commands
#### Tool
```shell
php bin/console user:create # Create user
```
### MLD
> https://dbdiagram.io/d/63fc7977296d97641d83f681

## Utilisateurs
- Super admin
    - email : admin@mail.fr
    - mot de passe : password
- Admin
    - email : admin@mail.fr
    - mot de passe : password