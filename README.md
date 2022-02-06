# 🥧 G-RATIN -- API de Notes et de système de vote

Notre API va permettre à des sites web d'enregistrer des notations sur leurs produits. Ces avis auront eux même un système de vote ("upvote") qui permettront de donner un aperçu de la pertinence de la note.

## Structure des données

- Un développeur s'inscrit avec son site, et récupère un token.

- Chaque site comporte des produits

- Chaque produit peut recevoir des notations

- Chaque notation peut recevoir des *upvotes* ou *downvotes*, comme sur Reddit ou Stackoverflow.

## Dépendances notables

- PHP >= 8.0
- Doctrine
- Api Platform
- Validator

## Lancer le projet

Premièrement, il vous faudra lancer la commande `composer install` à la racine du projet pour installer les dépendances citées au-dessus. 

On va ensuite mettre en place la BDD avec les fixtures, pour ce faire, commencez par lancer la commande `php bin/console doctrine:database:create` à la racine du projet puis la commande `php bin/console doctrine:schema:validate` et enfin la commande `php bin/console doctrine:schema:update --force`. Cela va permettre de créer une BDD avec le bon modèle, celle-ci est retrouvable dans `var/data.db`.

Pour remplir la BDD des fixtures, lancer la commande `php bin/console hautelook:fixtures:load`.

Enfin pour démarrer le projet on va lancer la commande `symfony server:start`.

Une fois le projet lancé et la BDD mise en place, vous pouvez accéder aux différentes routes questionnables sur l'api à l'adresse suivante [localhost:8000/api](localhost:8000/api).

Pour faire vos tests sur l'API, vous allez pouvoir avoir besoin d'un token valide. Pour récupérer celui-ci, choisissez un url (comme par exemple www.toto.fr), puis interroger l'api sur la route custom get_token de la sorte :

```
http://localhost:8000/api/get_token?website_url={url}
```

Ça simule une inscription et renvoie un token en créant une entrée de site.

Pour tester avec un site qui a déjà des données, utiliser le token `1976d16a487d446b86a0f7b0cb199291`.



## Usage de l'API

Chaque requête doit être effectuée avec le token pour s'authentifier. On renseigne pour cela un header HTTP:

`Authorization: <token>`

> ⚠️ Il ne s'agit pas d'un *Bearer Token* donc pas de "Bearer"

### Produits

**POST** | `/api/products`

Crée un produit. Exemple de données:

```json
{
    "reference": "my-own-reference",
}
```

avec:

- `reference`: [**Obligatoire**] Une référence de produit propre au client. Le format est libre afin de pouvoir correspondre aussi bien à des ID SQL qu'à des ObjectID NoSQL ou encore à des références de produits.



**GET** | `/api/products`

Renvoie tous les produits du site

Pour chaque produit, une clef `averageMark` est automatiquement ajoutée indiquant sa notation moyenne



**GET** | `/api/products/<id>`

Récupère un produit par son ID



**PUT** | `/api/products/<id>`

Modifie un produit, mêmes paramètres que pour la création



**DELETE** | `/api/products/<id>`

Supprime un produit



### Notations

**POST** | `/api/ratings`

Crée ue notation sur un produit. Exemple de données:

```json
{
	"mark": 4.8,
	"authorUserEmail": "beier.marc@ullrich.com",
    "product": "\/api\/products\/13",
	"metadata": []
}
```

avec:

- `mark`: [**Obligatoire**] La note donnée par l'utilisateur
- `authorUserEmail`: [**Obligatoire**] L'email de l'utilisateur

- `product`: [**Obligatoire**] La référence du produit

- `metadata`: Un tableau de `string` libres permettant au client d'associer des métadonnées à une notation



**GET** | `/api/ratings`

Renvoie toutes les notations du site

Pour chaque notation, une clef `upVoteScore` est automatiquement ajoutée indiquant son score d'upvotes (ex: 5 upvotes et 2 downvotes donnent un score de 3)

**Paramètres possibles**:

- `product`: ID du produit pour récupérer les notations associées à un produit uniquement

- `authorUserEmail`: Email d'utilisateur pour récupérer uniquement ses notations



**GET** | `/api/ratings/<id>`

Récupère une notation par son ID



**PUT** | `/api/ratings/<id>`

Modifie une notation, mêmes paramètres que pour la création



**DELETE** | `/api/ratings/<id>`

Supprime une notation



### Votes

**POST** | `/api/votes`

Crée un vote sur une notation. Exemple de données:

```json
{
	"isUp": true,
	"authorUserEmail": "celia68@bosco.biz",
	"rating": "\/api\/ratings\/9",
	"metadata": []
}
```

avec:

- `isUp`: [**Obligatoire**] Booléen, si vrai, ce sera un +1, si faux ce sera un -1

- `authorUserEmail`: [**Obligatoire**] L'email de l'utilisateur

- `rating`: [**Obligatoire**] La référence de la notation sur laquelle l'utilisateur a voté

- `metadata`: Un tableau de `string` libres permettant au client d'associer des métadonnées à un vote



**GET** | `/api/votes`

Renvoie toutes les notations du site



**Paramètres possibles**:

- `rating`: ID de la notation pour récupérer les votes associées à une notation uniquement

- `authorUserEmail`: Email d'utilisateur pour récupérer uniquement ses votes



**GET** | `/api/votes/<id>`

Récupère un vote par son ID



**PUT** | `/api/votes/<id>`

Modifie un vote, mêmes paramètres que pour la création



**DELETE** | `/api/votes/<id>`

Supprime un vote
