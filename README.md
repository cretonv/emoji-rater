# 🥧 G-RATIN -- API de Notes et de système de vote

Notre API va permettre à des sites web d'enregistrer des avis sur leurs produits. Ces avis auront eux même un système de vote ("upvote") qui permettront de donner un aperçu de la pertinence de la note.

## Dépendances notables

- Doctrine
- Api Platform
- Validator

## Lancer le projet

Premièrement, il vous faudra lancer la commande `composer install` à la racine du projet pour installer les dépendances citées au-dessus. 
Ensuite pour démarrer le projet on va lancer la commande `symfony server:start`.

On va ensuite mettre en place la BDD avec les fixtures, pour se faire commencez par lancer la commande `php bin/console doctrine:schema:validate` à la racine du projet puis la commande `php bin/console doctrine:schema:update --force`. Cela va permettre de créer une BDD avec le bon modèle, celle-ci est retrouvable dans `var/data.db`.

Pour remplir la BDD des fixtures, lancer la commande `php bin/console hautelook:fixtures:load`.

Une fois le projet lancé et la BDD mise en place, vous pouvez accéder aux différentes routes questionnables sur l'api à l'adresse suivante [localhost:8000/api](localhost:8000/api).

Pour faire vos tests sur l'API, vous allez pouvoir avoir besoin d'un token valide. Pour récupérer celui-ci, choisissez un url (comme par exemple www.toto.fr), puis interroger l'api sur la route custom get_token de la sorte :
```
http://localhost:8000/api/get_token?website_url={url}
```

// TODO




