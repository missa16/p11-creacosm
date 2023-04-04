#### Utilisation de feuilles de style css et js
* Charger celle-ci
* ``` npm run dev```

#### La base de donnée
* Utilisation de PostgresSQL
* username :  p11  
* password : 11
* nom de la BDD : creacosm 
* `symfony console make:migration`
* `symfony console doctrine:migrations:migrate`
* `symfony console dotrine:fixtures:load`

#### Packages pour le projet
* Mise en place de boostrap ``npm i bootstrap``
* Mise en place de chart.js `npm i chart.js`
* Mise en place de php-spreadsheet  `composer require phpoffice/phpspreadsheet`

#### Quelques dépendances composer
* ``composer install``
* ``composer require vich/uploader-bundle``

#### Créations de deux services
* L'un permet de generer des tableurs (xls, csv ..)
* L'autre permet d'enregistrer des images en deux formats : original et 300x300 (ceux-ci sont enregistrer dans public/assets)


#### Résumé des users
* ROLE_SONDE -> mail: bob.leponge@carre.com & password : password
* ROLE_SONDEUR -> mail: james.bond@skyfall.com  & password : password
* ROLE_ADMIN -> mail: admin@creacosm.com & password : password
* Donc se rendre à /login pour la connexion



