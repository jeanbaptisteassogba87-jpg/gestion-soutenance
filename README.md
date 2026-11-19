# Gestion des soutenances

Application web de gestion des mémoires et de planification des soutenances académiques.

## Présentation

**Gestion des soutenances** est une application permettant de centraliser le suivi des mémoires, la gestion des étudiants, l'encadrement et l'organisation des soutenances.

Elle facilite le travail de l'administration, des étudiants, des encadreurs et des membres du jury grâce à des espaces adaptés à chaque rôle.

## Fonctionnalités

### Administrateur
- Gestion globale de la plateforme.
- Gestion des utilisateurs et des différents profils.
- Suivi des mémoires déposés.
- Organisation et planification des soutenances.
- Gestion des jurys.

### Étudiant
- Consultation de son espace personnel.
- Dépôt de mémoire au format PDF.
- Possibilité d'associer un binôme.
- Choix de l'encadreur.
- Suivi du statut de son mémoire.

### Encadreur
- Consultation des mémoires qui lui sont attribués.
- Vérification des travaux déposés.
- Validation ou rejet des mémoires.

### Membre du jury
- Consultation des soutenances auxquelles il est affecté.
- Accès aux mémoires concernés.
- Évaluation des étudiants.

## Technologies utilisées

- **PHP** : logique métier et traitement côté serveur.
- **MySQL / MariaDB** : gestion des données.
- **PDO** : accès à la base de données avec requêtes préparées.
- **HTML, CSS et JavaScript** : interface utilisateur.

## Structure du projet

```text
gestion_soutenance/
│
├── app/
│   ├── controllers/
│   ├── models/
│   ├── views/
│   │   ├── admin/
│   │   ├── etudiant/
│   │   ├── encadreur/
│   │   └── jury/
│   └── helpers/
│
├── config/
│   ├── config.php
│   ├── database.example.php
│   └── database.php
│
├── database/
│   └── gestion_soutenances.sql
│
├── public/
│   └── uploads/
│
├── storage/
│   └── memoires/
│
├── .gitignore
└── README.md
```

## Installation

### 1. Prérequis

- PHP.
- MySQL ou MariaDB.
- Apache ou un serveur web compatible PHP.
- Git.

### 2. Récupérer le projet

```bash
git clone git@github.com:jeanbaptisteassogba87-jpg/gestion-soutenance.git
cd gestion-soutenance
```

### 3. Configurer la base de données

Créer une base de données MySQL ou MariaDB, puis importer le fichier :

```text
database/gestion_soutenances.sql
```

### 4. Configurer la connexion

Créer le fichier local de configuration à partir du modèle :

```bash
cp config/database.example.php config/database.php
```

Renseigner les paramètres de connexion à la base de données dans `config/database.php`.

**Important :** le fichier `config/database.php` contient des informations sensibles. Il ne doit jamais être publié sur GitHub.

### 5. Configurer le serveur

Configurer Apache pour servir le projet depuis son répertoire approprié, puis accéder à l'application depuis le navigateur.

## Sécurité

Le projet intègre plusieurs mesures de sécurité :

- Utilisation de requêtes préparées avec PDO.
- Contrôle des accès selon les rôles.
- Protection des documents PDF.
- Vérification des fichiers déposés.
- Stockage des mémoires dans un répertoire privé `storage/memoires/`.
- Accès aux mémoires via un contrôleur dédié.
- Exclusion des fichiers sensibles de Git.

## Base de données

Les principales tables sont :

- `Utilisateur`
- `Etudiant`
- `Encadreur`
- `Administrateur`
- `MembreJury`
- `Jury`
- `RoleJury`
- `Memoire`
- `Soutenance`

## Auteur

**Jean-Baptiste ASSOGBA**

Étudiant en Licence 3 — Systèmes Informatiques et Logiciels (SIL).

## Licence

Projet académique réalisé dans le cadre d'un travail de formation.