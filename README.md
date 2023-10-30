# Teams and Players
The project is using symfony 6.3


## Minimum requirements
* php 8.1
* Mariadb 10.6
* Node v18
* composer
* npm or yarn

## After cloning the repo

After cloning, run `composer install`

Then run `yarn install`

Then set up all your ENV configs, such as database infos, ... in `.env.local`

### Run migrations
After you run all your services and daemon :

Run `php bin/console doctrine:database:create` if the database is not created yet

Run `php bin/console doctrine:migrations:migrate` to create the database structure

### Build frontend
After running the `yarn install` above,
run `yarn dev` to compile frontend assets.

### Create user to access the app
TO access the app, you'll need user to be connected.

To create one, run the command `php bin/console app:create-user` and you will be prompted for the creation