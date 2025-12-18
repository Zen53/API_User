# API REST Symfony - Gestion des Utilisateurs

Une API REST simple construite avec Symfony pour gérer des utilisateurs (CRUD).

##  Prérequis

- PHP >= 8.2
- Composer
- Symfony CLI (optionnel mais recommandé)

##  Installation

1. **Installer les dépendances**
```bash
composer install
```

2. **Créer la base de données**
```bash
php bin/console doctrine:database:create
php bin/console doctrine:schema:create
```

3. **Lancer le serveur**
```bash
symfony server:start
# ou
php -S localhost:8000 -t public
```

##  Endpoints de l'API

| Méthode | URL | Description |
|---------|-----|-------------|
| `GET` | `/api/users` | Liste tous les utilisateurs |
| `GET` | `/api/users/{id}` | Récupère un utilisateur par son ID |
| `POST` | `/api/users` | Crée un nouvel utilisateur |
| `PUT` | `/api/users/{id}` | Met à jour un utilisateur |
| `DELETE` | `/api/users/{id}` | Supprime un utilisateur |

##  Exemples d'utilisation

### Créer un utilisateur (POST)
```bash
curl -X POST http://localhost:8000/api/users \
  -H "Content-Type: application/json" \
  -d '{"name": "Jean Dupont", "email": "jean@example.com"}'
```

**Réponse:**
```json
{
  "id": 1,
  "name": "Jean Dupont",
  "email": "jean@example.com",
  "createdAt": "2025-12-18 10:30:00"
}
```

### Récupérer tous les utilisateurs (GET)
```bash
curl http://localhost:8000/api/users
```

### Récupérer un utilisateur (GET)
```bash
curl http://localhost:8000/api/users/1
```

### Mettre à jour un utilisateur (PUT)
```bash
curl -X PUT http://localhost:8000/api/users/1 \
  -H "Content-Type: application/json" \
  -d '{"name": "Jean-Pierre Dupont"}'
```

### Supprimer un utilisateur (DELETE)
```bash
curl -X DELETE http://localhost:8000/api/users/1
```

##  Structure du projet

```
├── config/
│   ├── packages/
│   │   ├── doctrine.yaml
│   │   ├── doctrine_migrations.yaml
│   │   └── framework.yaml
│   ├── bundles.php
│   ├── routes.yaml
│   └── services.yaml
├── public/
│   └── index.php
├── src/
│   ├── Controller/
│   │   └── UserController.php
│   ├── Entity/
│   │   └── User.php
│   ├── Repository/
│   │   └── UserRepository.php
│   └── Kernel.php
├── .env
├── composer.json
└── README.md
```

## Configuration de la base de données

Par défaut, l'API utilise SQLite. Pour changer de base de données, modifiez la variable `DATABASE_URL` dans le fichier `.env`:

**SQLite (par défaut):**
```
DATABASE_URL="sqlite:///%kernel.project_dir%/var/data.db"
```

**MySQL:**
```
DATABASE_URL="mysql://user:password@127.0.0.1:3306/api_users?serverVersion=8.0"
```

**PostgreSQL:**
```
DATABASE_URL="postgresql://user:password@127.0.0.1:5432/api_users?serverVersion=15&charset=utf8"
```

##  Codes de réponse HTTP

| Code | Signification |
|------|---------------|
| 200 | Succès |
| 201 | Créé avec succès |
| 400 | Requête invalide |
| 404 | Ressource non trouvée |
| 409 | Conflit (email déjà utilisé) |


