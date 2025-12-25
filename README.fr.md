# TOnotDO - Application de Gestion des Tâches

Une application collaborative de gestion des tâches en temps réel construite avec PHP, MySQL et Node.js Socket.IO.

## Caractéristiques

- **Rôles d'Utilisateurs**: Client (crée les tâches), Travailleur (complète les tâches), Root/Admin (gère tout)
- **Chat en Temps Réel**: Chatbot alimenté par l'IA pour un support instantané
- **Mises à Jour des Tâches en Temps Réel**: Tous les utilisateurs voient les modifications instantanément via WebSockets
- **Gestion des Tâches**: Créer, assigner, mettre à jour et marquer les tâches comme terminées
- **Profils Utilisateur**: Gérer les informations de profil et modifier les mots de passe

## Pile Technologique

- **Backend**: PHP 7.4+
- **Base de Données**: MySQL 5.7+
- **Frontend**: HTML, CSS, JavaScript
- **Temps Réel**: Node.js + Socket.IO
- **Serveur**: Apache (XAMPP)

## Installation

### 1. Cloner le Répertoire

```bash
git clone <url-du-répertoire>
cd TOnotDO
```

### 2. Configurer les Variables d'Environnement

Copiez `.env.example` vers `.env` et mettez à jour avec vos paramètres:

```bash
cp .env.example .env
```

Modifiez `.env`:

```
DB_HOST=localhost
DB_USER=root
DB_PASS=votre_mot_de_passe
DB_NAME=tonotdo_app
NODE_SERVER_URL=http://localhost:3000
APP_URL=http://localhost/TOnotDO/public
```

### 3. Créer la Base de Données

Créez la base de données MySQL et importez le schéma:

```sql
CREATE DATABASE tonotdo_app CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE tonotdo_app;

CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(100) UNIQUE NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    role ENUM('client', 'worker', 'root') DEFAULT 'client',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE tasks (
    id INT PRIMARY KEY AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    client_id INT NOT NULL,
    worker_id INT,
    status ENUM('pending', 'in-progress', 'done') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (client_id) REFERENCES users(id),
    FOREIGN KEY (worker_id) REFERENCES users(id)
);
```

### 4. Démarrer le Serveur Node.js

Dans le répertoire `node-server/`:

```bash
cd node-server
npm install
node server.js
```

Le serveur devrait afficher: `✅ Serveur Node.js en cours d'exécution sur le port 3000`

### 5. Accéder à l'Application

Ouvrez votre navigateur et allez à:

```
http://localhost/TOnotDO/public/
```

## Utilisateurs de Test par Défaut

Après avoir exécuté le schéma de base de données, vous pouvez enregistrer de nouveaux utilisateurs ou utiliser:

- **Client**: Créer et suivre les demandes de tâches
- **Travailleur**: Complétez les tâches assignées
- **Root/Admin**: Gérer les utilisateurs et toutes les tâches

## Structure du Projet

```
TOnotDO/
├── app/
│   ├── controllers/       # Gestionnaires de routes
│   ├── core/              # Base de données, Routeur, Auth
│   ├── models/            # Modèles User et Task
│   └── views/             # Modèles HTML
├── node-server/           # Serveur Socket.IO
├── public/                # Point d'entrée (index.php)
├── .env.example           # Modèle d'environnement
├── .gitignore             # Exclusions Git
└── README.md              # Ce fichier
```

## Sécurité

- ✅ Variables d'environnement pour les identifiants
- ✅ Déclarations PDO préparées (protection contre l'injection SQL)
- ✅ Hachage de mot de passe avec `PASSWORD_DEFAULT`
- ✅ Authentification basée sur les sessions
- ✅ Contrôle d'accès basé sur les rôles

## Routes API

### Authentification

- `GET /auth/login` — Formulaire de connexion
- `POST /auth/login` — Traiter la connexion
- `GET /auth/register` — Formulaire d'enregistrement
- `POST /auth/register` — Créer un compte
- `GET /auth/logout` — Déconnexion

### Tâches

- `GET /tasks/myRequests` — Tâches créées par le client
- `GET /tasks/myTasks` — Tâches assignées au travailleur
- `GET /tasks/manage` — Toutes les tâches du Root
- `POST /tasks/create` — Créer une tâche
- `GET /tasks/edit/:id` — Formulaire d'édition de tâche
- `POST /tasks/update/:id` — Mettre à jour une tâche
- `GET /tasks/delete/:id` — Supprimer une tâche
- `GET /tasks/complete/:id` — Marquer comme terminée

### Chat

- `GET /chat/index` — Interface de chat

## Fonctionnalités en Temps Réel

### Événements Socket.IO

**Client → Serveur:**
- `chatMessage` — Envoyer un message de chat
- `taskUpdate` — Changement d'état de la tâche

**Serveur → Client:**
- `chatMessage` — Recevoir une réponse du chat/bot
- `taskUpdate` — Diffuser les changements de tâche

## Dépannage

### La connexion à la base de données échoue

Vérifiez que les identifiants du fichier `.env` correspondent à votre configuration MySQL:

```bash
mysql -u root -p tonotdo_app
```

### Connexion Socket.IO refusée

Assurez-vous que le serveur Node.js est en cours d'exécution sur le port 3000:

```bash
netstat -ano | findstr :3000
```

### Le chat ne fonctionne pas

- Vérifiez la console du navigateur pour les erreurs de connexion Socket.IO
- Vérifiez que le serveur Node.js est en cours d'exécution
- Vérifiez les paramètres CORS dans `node-server/server.js`

## Déploiement

Avant de déployer en production:

1. ✅ Copiez `.env.example` vers `.env`
2. ✅ Mettez à jour `.env` avec les identifiants de production
3. ✅ Définissez `APP_ENV=production` dans `.env`
4. ✅ Assurez-vous que `.env` est dans `.gitignore` (non suivi)
5. ✅ Exécutez le serveur Node.js en mode production (PM2, Docker, etc.)
6. ✅ Utilisez HTTPS pour la production
7. ✅ Définissez des drapeaux de cookie sécurisés en production

## Licence

MIT

## Support

Pour les problèmes, ouvrez une issue GitHub ou contactez le support.
