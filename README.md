# Sym64ENEZ - Plateforme de Gestion de Contenu

![Version](https://img.shields.io/badge/version-1.0.0-blue.svg)
![PHP](https://img.shields.io/badge/PHP-8.1+-777BB4.svg)
![Symfony](https://img.shields.io/badge/Symfony-6.4-000000.svg)
![Docker](https://img.shields.io/badge/Docker-Ready-2496ED.svg)

Une plateforme moderne de gestion de contenu développée avec Symfony 6.4, permettant la création et la gestion d'articles avec une interface administrative intuitive.

## 🚀 Fonctionnalités

- ✨ Interface utilisateur moderne et responsive
- 👤 Système d'authentification sécurisé
- 📝 Gestion complète des articles
- 🗂️ Organisation par sections
- 💬 Système de commentaires
- 👑 Interface d'administration
- 🐳 Support Docker

## 🛠️ Technologies Utilisées

- **Framework**: Symfony 6.4
- **Base de données**: MySQL
- **Frontend**: 
  - Bootstrap 5
  - Twig
  - Font Awesome
- **ORM**: Doctrine
- **Authentification**: Symfony Security
- **Conteneurisation**: Docker

## ⚙️ Prérequis

### Installation Standard
- PHP 8.1 ou supérieur
- Composer
- MySQL 5.7 ou supérieur
- Node.js et npm (pour les assets)

### Installation Docker
- Docker
- Docker Compose

## 📥 Installation

### Méthode Standard

1. Cloner le repository
```bash
git clone https://github.com/EEnez/sym64ENEZ.git
cd sym64enez
```

2. Installer les dépendances
```bash
composer install
npm install
```

3. Configurer l'environnement
```bash
cp .env .env.local
# Modifier les variables d'environnement dans .env.local
```

4. Créer la base de données
```bash
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

5. Charger les fixtures (données de test)
```bash
php bin/console doctrine:fixtures:load
```

6. Lancer le serveur
```bash
symfony server:start
```

### 🐳 Installation avec Docker

1. Cloner le repository
```bash
git clone https://github.com/EEnez/sym64ENEZ.git
cd sym64enez
```

2. Lancer les conteneurs
```bash
docker compose up -d
```

3. Installer les dépendances
```bash
docker compose exec php composer install
docker compose exec php npm install
```

4. Configurer la base de données
```bash
docker compose exec php php bin/console doctrine:migrations:migrate
docker compose exec php php bin/console doctrine:fixtures:load
```

L'application sera accessible à l'adresse : `http://localhost:8080`

## 🔧 Configuration

La configuration principale se fait via le fichier `.env.local`. Vous devez configurer :

- La connexion à la base de données
- Les paramètres de mail (optionnel)
- Les variables d'environnement spécifiques

## 👥 Utilisation

1. Accéder à l'interface d'administration : `/admin`
2. Se connecter avec les identifiants administrateur
3. Gérer les articles, sections et utilisateurs
4. Publier du contenu

## 🔒 Sécurité

- Authentification sécurisée
- Protection CSRF
- Validation des formulaires
- Gestion des rôles utilisateur

## 🤝 Contribution

Les contributions sont les bienvenues ! Pour contribuer :

1. Forker le projet
2. Créer une branche pour votre fonctionnalité
3. Commiter vos changements
4. Pousser vers la branche
5. Ouvrir une Pull Request

## 📝 Licence

Ce projet est sous licence MIT. Voir le fichier `LICENSE` pour plus de détails.

## 📧 Contact

Pour toute question ou suggestion, n'hésitez pas à ouvrir une issue ou à me contacter directement.

---
Développé par Enez Gubeljic