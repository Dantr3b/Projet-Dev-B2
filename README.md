# Projet Dev B2

## Description

**Projet Dev B2** est une API RESTful développée avec [Laravel](https://laravel.com/).  
Elle gère l’authentification, les utilisateurs, les produits, les paniers, les wishlists, les commandes, les avis, et propose une documentation interactive via Swagger.

---

## Fonctionnalités principales

- **Authentification & Utilisateurs** : Inscription, connexion, gestion de profil (Sanctum).
- **Gestion des Produits** : CRUD complet.
- **Panier & Wishlist** : Ajout, modification, suppression d’articles.
- **Commandes** : Création, consultation, paiement (Stripe test), suivi.
- **Avis** : Ajout et consultation d’avis sur les produits.
- **Documentation API** : Swagger UI générée automatiquement.
- **Validation & Gestion des erreurs** : Validation des entrées et gestion des erreurs HTTP.

---

## Prérequis

- PHP >= 8.2
- Composer
- MySQL ou autre SGBD compatible Laravel
- Node.js (pour le frontend ou les assets)
- [Stripe](https://stripe.com/) (pour le paiement test)

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
   # Modifier .env selon vos paramètres (DB, mail, Stripe, etc.)
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

La documentation interactive de l’API est accessible à :

```
http://localhost:8000/api/documentation
```

Pour régénérer la documentation Swagger après modification des annotations :
```bash
php artisan l5-swagger:generate
```

---

## Paiement fictif (Stripe)

- Utilise la carte de test Stripe : `4242 4242 4242 4242`
- Date d’expiration : n’importe quelle date future (ex: 12/34)
- CVC : n’importe quel code à 3 chiffres

Configure tes clés Stripe dans `.env` :
```
STRIPE_KEY=pk_test_xxxxxxxxxxxxxxxxxxxxx
STRIPE_SECRET=sk_test_xxxxxxxxxxxxxxxxxxxxx
```

---

## Sécurité

L’API utilise [Laravel Sanctum](https://laravel.com/docs/12.x/sanctum) pour l’authentification par token.  
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
- `app/Swagger` : Schémas Swagger (OpenAPI)
- `routes/api.php` : Définition des routes API
- `config/l5-swagger.php` : Configuration Swagger

---

## License

Distribué sous licence MIT.