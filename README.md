# Challenge stack 1
> https://challenge-stack-1.pidok-server.fr
## Documentation
### Exigences
* PHP ``8.1``
* Node ``16``
### Installation
Copier le fichier `.env` en `.env.local` et odifier la ligne `DATABASE_URL="mysql://root:root@127.0.0.1:3306/forum"` pour correspondre a vos infos de BDD
```shell
composer install
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate       # ou :  php bin/console doc:sch:up -f   
php bin/console doctrine:fixtures:load            # Peut être un peu long à cause du hash des passwords et de la masse de données généré
yarn install
yarn build
```
### Commandes
#### Outils
```shell
php bin/console user:create # Create user
```
### MLD
> https://dbdiagram.io/d/63ff7fd7296d97641d84b184

## Utilisateurs
- Super admin
    - email : admin@mail.fr
    - mot de passe : password
    - Lien de connexion : https://challenge-stack-1.pidok-server.fr/admin/login
- Client
    - email : client1@mail.fr
    - mot de passe : password
    - Lien de connexion : https://challenge-stack-1.pidok-server.fr/connexion
