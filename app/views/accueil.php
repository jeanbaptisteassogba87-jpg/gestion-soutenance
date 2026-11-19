<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UATM GASA — Système de gestion des soutenances</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<!-- NAV -->
<nav class="landing-nav">
    <div class="nav-brand"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i><span>UATM</span> GASA</div>
    <a href="/app/views/auth/login.php" class="nav-btn"><i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i> Se connecter</a>
</nav>

<!-- HERO -->
<section class="hero">
    <div class="hero-badge">Université UATM GASA</div>
    <h1>Gestion des soutenances<br>en toute simplicité</h1>
    <p>
        Une plateforme centralisée pour planifier, suivre et évaluer
        les soutenances de mémoires. Du dépôt à la délibération,
        tout en un seul endroit.
    </p>
    <div class="hero-actions">
        <a href="/app/views/auth/login.php" class="btn-hero btn-hero-primary">
            <i class="fa-solid fa-arrow-right-to-bracket" aria-hidden="true"></i> Accéder à la plateforme
        </a>
        <a href="#fonctionnalites" class="btn-hero btn-hero-outline">
            Découvrir les fonctionnalités
        </a>
    </div>
</section>

<!-- STATS -->
<div class="stats">
    <div class="stat">
        <strong>4</strong>
        <span>Types d'acteurs</span>
    </div>
    <div class="stat">
        <strong>100%</strong>
        <span>Sécurisé</span>
    </div>
    <div class="stat">
        <strong>PDF</strong>
        <span>Dépôt numérique</span>
    </div>
    <div class="stat">
        <strong>Mobile</strong>
        <span>Compatible</span>
    </div>
</div>

<!-- FONCTIONNALITÉS -->
<section class="features" id="fonctionnalites">
    <h2>Tout ce dont vous avez besoin</h2>
    <p class="features-sub">Une plateforme complète pour gérer le processus de soutenance de A à Z.</p>

    <div class="features-grid">
        <div class="feature-card">
            <div class="feature-icon"><i class="fa-regular fa-file-lines" aria-hidden="true"></i></div>
            <h3>Dépôt de mémoire</h3>
            <p>Les étudiants déposent leur mémoire en PDF directement sur la plateforme avec leur binôme.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fa-solid fa-circle-check" aria-hidden="true"></i></div>
            <h3>Validation encadreur</h3>
            <p>L'encadreur consulte le document et valide ou rejette le mémoire en un clic.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fa-regular fa-calendar-check" aria-hidden="true"></i></div>
            <h3>Planification</h3>
            <p>L'administrateur planifie les soutenances et compose les jurys de 3 membres avec leurs rôles.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fa-solid fa-scale-balanced" aria-hidden="true"></i></div>
            <h3>Évaluation et délibération</h3>
            <p>Le jury consulte le mémoire, attribue une note et une mention. La décision s'affiche automatiquement.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fa-solid fa-chart-column" aria-hidden="true"></i></div>
            <h3>Tableau de bord</h3>
            <p>Chaque acteur dispose d'un espace personnalisé selon son rôle dans le système.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon"><i class="fa-solid fa-mobile-screen-button" aria-hidden="true"></i></div>
            <h3>Interface responsive</h3>
            <p>La plateforme s'adapte à tous les appareils : ordinateur, tablette et téléphone mobile.</p>
        </div>
    </div>
</section>

<!-- ACTEURS -->
<section class="acteurs">
    <h2>Un espace pour chaque acteur</h2>
    <p class="acteurs-sub">Chaque utilisateur accède uniquement aux fonctionnalités qui lui sont destinées.</p>

    <div class="acteurs-grid">
        <div class="acteur-card">
            <div class="ico"><i class="fa-solid fa-user-graduate" aria-hidden="true"></i></div>
            <h3>Etudiant</h3>
            <ul>
                <li>Déposer son mémoire</li>
                <li>Choisir son binôme</li>
                <li>Consulter le planning</li>
                <li>Voir sa note finale</li>
            </ul>
        </div>
        <div class="acteur-card">
            <div class="ico"><i class="fa-solid fa-chalkboard-user" aria-hidden="true"></i></div>
            <h3>Encadreur</h3>
            <ul>
                <li>Consulter les mémoires</li>
                <li>Télécharger le PDF</li>
                <li>Valider ou rejeter</li>
            </ul>
        </div>
        <div class="acteur-card">
            <div class="ico"><i class="fa-solid fa-scale-balanced" aria-hidden="true"></i></div>
            <h3>Jury</h3>
            <ul>
                <li>Voir le planning</li>
                <li>Lire le mémoire PDF</li>
                <li>Attribuer une note</li>
                <li>Délibérer</li>
            </ul>
        </div>
        <div class="acteur-card">
            <div class="ico"><i class="fa-solid fa-screwdriver-wrench" aria-hidden="true"></i></div>
            <h3>Administrateur</h3>
            <ul>
                <li>Planifier les soutenances</li>
                <li>Composer les jurys</li>
                <li>Gérer les utilisateurs</li>
                <li>Vue globale du système</li>
            </ul>
        </div>
    </div>
</section>

<!-- CTA -->
<section class="cta">
    <h2>Prêt à commencer ?</h2>
    <p>Connectez-vous avec votre identifiant pour accéder à votre espace.</p>
    <a href="/app/views/auth/login.php" class="btn-hero btn-hero-primary">
        <i class="fa-solid fa-arrow-right-to-bracket" aria-hidden="true"></i> Se connecter à la plateforme
    </a>
</section>

<!-- FOOTER -->
<footer class="landing-footer">
    <p>© <?= date('Y') ?> UATM GASA — Système de gestion des soutenances. Tous droits réservés.</p>
</footer>

</body>
</html>
