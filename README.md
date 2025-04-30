# Projet Dev B2

## Description

**Projet Dev B2** est une API RESTful développée avec [Laravel](https://laravel.com/).  
Elle permet la gestion des utilisateurs, des produits, des commandes, des paniers, des wishlists, des avis, etc.  
La documentation interactive de l’API est disponible via Swagger UI.

---

## Fonctionnalités principales

- **Authentification & Utilisateurs** : Inscription, connexion, gestion de profil, sécurité avec Sanctum.
- **Gestion des Produits** : CRUD complet sur les produits.
- **Panier & Wishlist** : Ajout, modification, suppression d’articles.
- **Commandes** : Création, consultation, mise à jour, suppression de commandes.
- **Avis** : Ajout et consultation d’avis sur les produits.
- **Documentation API** : Générée automatiquement avec Swagger UI.
- **Validation & Gestion des Erreurs** : Validation des entrées et gestion des erreurs HTTP.

---

## Prérequis

- PHP >= 7.4
- Composer
- MySQL ou autre SGBD compatible Laravel
- Node.js (pour les assets frontend, optionnel)

---

## Installation

1. **Cloner le projet**
   ```bash
   git clone https://github.com/Dantr3b/Projet-Dev-B2.git
   cd Projet-Dev-B2
   ```

2. **Installer les dépendances backend**
   ```bash
   composer install
   ```

3. **Configurer l’environnement**
   ```bash
   cp .env.example .env
   # Modifier .env selon vos paramètres (DB, mail, etc.)
   ```

4. **Générer la clé d’application**
   ```bash
   php artisan key:generate
   ```

5. **Exécuter les migrations**
   ```bash
   php artisan migrate
   ```

6. **Installer les dépendances frontend (optionnel)**
   ```bash
   npm install
   npm run dev
   ```

---

## Démarrage du projet

1. **Lancer le serveur local**
   ```bash
   php artisan serve
   ```

2. **Accéder à l’application**
   ```
   http://localhost:8000
   ```

---

## Documentation Swagger

La documentation interactive de l’API est accessible à l’adresse suivante :

```
http://localhost:8000/api/documentation
```

Pour régénérer la documentation Swagger après modification des annotations :
```bash
php artisan l5-swagger:generate
```

---

## Sécurité

L’API utilise [Laravel Sanctum](https://laravel.com/docs/10.x/sanctum) pour l’authentification par token.  
Inclure le token Bearer dans les headers pour accéder aux routes protégées.

---

## Tests

Pour lancer les tests automatisés :
```bash
php artisan test
```

---

## Structure du projet

- `app/Http/Controllers` : Contrôleurs de l’API
- `app/Models` : Modèles Eloquent
- `app/Swagger` : Fichiers de schémas Swagger (OpenAPI)
- `routes/api.php` : Définition des routes API
- `config/l5-swagger.php` : Configuration Swagger

---

## License

Distribué sous licence MIT.