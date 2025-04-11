# Projet Dev B2

## Description

**Projet Dev B2** est une API RESTful développée avec **Laravel**. L'API permet la gestion des utilisateurs, l'authentification, et comprend une interface **Swagger UI** pour la documentation interactive.

### Fonctionnalités principales :

- Inscription des utilisateurs
- Authentification avec tokens
- Confirmer le mot de passe
- Vérification d'email
- Déconnexion

---

## Prérequis

Avant de commencer, assurez-vous d'avoir installé :

- PHP (>= 7.4)
- Composer
- MySQL (ou une autre base de données compatible Laravel)
- Node.js (si vous avez besoin d'installer les dépendances frontend)

---

## Installation

1. **Clonez le projet**

   ```bash
   git clone https://github.com/Dantr3b/Projet-Dev-B2.git
   cd Projet-Dev-B2
   ```

2. Installez les dépendances avec Composer

```bash
composer install
```

3. Configurez votre environnement

Dupliquez le fichier .env.example et modifiez-le selon vos paramètres :

```bash
cp .env.example .env
```
4. Générez la clé d'application

```bash
php artisan key:generate
```

5. Exécutez les migrations de la base de données

```bash
php artisan migrate
```
6. Installez les dépendances front-end (optionnel)

```bash
npm install
npm run dev
```


