CREATE DATABASE IF NOT EXISTS gestion_soutenances
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE gestion_soutenances;

CREATE TABLE Utilisateur (
  idUtilisateur INT          PRIMARY KEY AUTO_INCREMENT,
  nom           VARCHAR(255) NOT NULL,
  prenom        VARCHAR(255) NOT NULL,
  login         VARCHAR(50)  NOT NULL UNIQUE,
  motDePasse    VARCHAR(255) NOT NULL,
  role          ENUM('etudiant','encadreur','membre_jury','administrateur') NOT NULL
);

CREATE TABLE Etudiant (
  idEtudiant INT         PRIMARY KEY,
  filiere    VARCHAR(100) NOT NULL,
  FOREIGN KEY (idEtudiant) REFERENCES Utilisateur(idUtilisateur)
);

CREATE TABLE Encadreur (
  idEncadreur INT          PRIMARY KEY,
  specialite  VARCHAR(100) NOT NULL,
  FOREIGN KEY (idEncadreur) REFERENCES Utilisateur(idUtilisateur)
);

CREATE TABLE Administrateur (
  idAdministrateur INT PRIMARY KEY,
  FOREIGN KEY (idAdministrateur) REFERENCES Utilisateur(idUtilisateur)
);

CREATE TABLE MembreJury (
  idMembreJury INT PRIMARY KEY,
  FOREIGN KEY (idMembreJury) REFERENCES Utilisateur(idUtilisateur)
);

CREATE TABLE Jury (
  idJury INT PRIMARY KEY AUTO_INCREMENT
);

CREATE TABLE RoleJury (
  idRoleJury   INT          PRIMARY KEY AUTO_INCREMENT,
  idJury       INT          NOT NULL,
  idMembreJury INT          NOT NULL,
  role         ENUM('president','rapporteur','examinateur') NOT NULL,
  FOREIGN KEY (idJury)       REFERENCES Jury(idJury),
  FOREIGN KEY (idMembreJury) REFERENCES MembreJury(idMembreJury)
);

CREATE TABLE Memoire (
  idMemoire   INT          PRIMARY KEY AUTO_INCREMENT,
  idEtudiant  INT          NOT NULL,
  idEncadreur INT          NOT NULL,
  titre       VARCHAR(255) NOT NULL,
  dateDepot   DATE         NOT NULL,
  statut      ENUM('en_attente','valide','rejete') NOT NULL DEFAULT 'en_attente',
  FOREIGN KEY (idEtudiant)  REFERENCES Etudiant(idEtudiant),
  FOREIGN KEY (idEncadreur) REFERENCES Encadreur(idEncadreur)
);


CREATE TABLE Soutenance (
  idSoutenance     INT          PRIMARY KEY AUTO_INCREMENT,
  idMemoire        INT          NOT NULL UNIQUE,
  idAdministrateur INT          NOT NULL,
  idJury           INT          NOT NULL,
  date             DATE         NOT NULL,
  heure            TIME         NOT NULL,
  salle            VARCHAR(100) NOT NULL,
  note             INT          DEFAULT NULL,
  mention          ENUM('Passable','Assez Bien','Bien','Tres Bien','Excellent') DEFAULT NULL,
  FOREIGN KEY (idMemoire)        REFERENCES Memoire(idMemoire),
  FOREIGN KEY (idAdministrateur) REFERENCES Administrateur(idAdministrateur),
  FOREIGN KEY (idJury)           REFERENCES Jury(idJury)
);
