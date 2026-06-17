<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>UATM GASA — Système de gestion des soutenances</title>
    <style>
        *, *::before, *::after { margin:0; padding:0; box-sizing:border-box; }

        :root {
            --primary: #2563eb;
            --primary-dk: #1d4ed8;
            --slate: #1e293b;
            --slate-lt: #64748b;
            --white: #fff;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            background: #f1f5f9;
            color: var(--slate);
        }

        /* ── NAV ── */
        nav {
            background: var(--white);
            box-shadow: 0 1px 4px rgba(0,0,0,0.08);
            padding: 0 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            height: 64px;
            position: sticky;
            top: 0;
            z-index: 10;
        }

        .nav-brand {
            font-size: 17px;
            font-weight: 700;
            color: var(--slate);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .nav-brand span { color: var(--primary); }

        .nav-btn {
            background: var(--primary);
            color: #fff;
            padding: 9px 22px;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            transition: background 0.15s;
        }
        .nav-btn:hover { background: var(--primary-dk); }

        /* ── HERO ── */
        .hero {
            background: linear-gradient(135deg, #1e293b 0%, #2563eb 60%, #1d4ed8 100%);
            color: white;
            text-align: center;
            padding: 90px 5% 80px;
        }

        .hero-badge {
            display: inline-block;
            background: rgba(255,255,255,0.15);
            border: 1px solid rgba(255,255,255,0.25);
            padding: 6px 18px;
            border-radius: 20px;
            font-size: 13px;
            margin-bottom: 24px;
            letter-spacing: 0.5px;
        }

        .hero h1 {
            font-size: clamp(28px, 5vw, 48px);
            font-weight: 800;
            line-height: 1.2;
            margin-bottom: 18px;
        }

        .hero p {
            font-size: clamp(15px, 2vw, 18px);
            color: #bfdbfe;
            max-width: 560px;
            margin: 0 auto 36px;
            line-height: 1.7;
        }

        .hero-actions {
            display: flex;
            gap: 14px;
            justify-content: center;
            flex-wrap: wrap;
        }

        .btn-hero {
            padding: 13px 28px;
            border-radius: 10px;
            font-size: 15px;
            font-weight: 600;
            text-decoration: none;
            transition: all 0.15s;
        }

        .btn-hero-primary {
            background: #fff;
            color: var(--primary);
        }
        .btn-hero-primary:hover { background: #e0e7ff; }

        .btn-hero-outline {
            background: transparent;
            color: #fff;
            border: 2px solid rgba(255,255,255,0.4);
        }
        .btn-hero-outline:hover { border-color: #fff; background: rgba(255,255,255,0.1); }

        /* ── STATS ── */
        .stats {
            display: flex;
            justify-content: center;
            gap: 40px;
            flex-wrap: wrap;
            padding: 36px 5%;
            background: var(--white);
            border-bottom: 1px solid #e2e8f0;
        }

        .stat { text-align: center; }
        .stat strong { display: block; font-size: 28px; font-weight: 800; color: var(--primary); }
        .stat span { font-size: 13px; color: var(--slate-lt); }

        /* ── FEATURES ── */
        .features {
            padding: 70px 5%;
            max-width: 1100px;
            margin: 0 auto;
        }

        .features h2 {
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .features-sub {
            text-align: center;
            color: var(--slate-lt);
            margin-bottom: 44px;
            font-size: 15px;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
        }

        .feature-card {
            background: var(--white);
            border-radius: 12px;
            padding: 28px 24px;
            box-shadow: 0 1px 6px rgba(0,0,0,0.07);
            transition: transform 0.15s, box-shadow 0.15s;
            border-top: 3px solid var(--primary);
        }

        .feature-card:hover {
            transform: translateY(-3px);
            box-shadow: 0 6px 20px rgba(37,99,235,0.12);
        }

        .feature-icon { font-size: 32px; margin-bottom: 14px; }
        .feature-card h3 { font-size: 16px; margin-bottom: 8px; }
        .feature-card p { font-size: 13.5px; color: var(--slate-lt); line-height: 1.6; }

        /* ── ACTEURS ── */
        .acteurs {
            background: var(--white);
            padding: 70px 5%;
        }

        .acteurs h2 {
            text-align: center;
            font-size: 26px;
            font-weight: 700;
            margin-bottom: 8px;
        }

        .acteurs-sub {
            text-align: center;
            color: var(--slate-lt);
            margin-bottom: 44px;
            font-size: 15px;
        }

        .acteurs-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 16px;
            max-width: 900px;
            margin: 0 auto;
        }

        .acteur-card {
            border: 1.5px solid #e2e8f0;
            border-radius: 12px;
            padding: 24px;
            text-align: center;
            transition: border-color 0.15s;
        }

        .acteur-card:hover { border-color: var(--primary); }
        .acteur-card .ico { font-size: 36px; margin-bottom: 10px; }
        .acteur-card h3 { font-size: 15px; font-weight: 700; margin-bottom: 8px; }
        .acteur-card ul { list-style: none; text-align: left; }
        .acteur-card ul li {
            font-size: 13px;
            color: var(--slate-lt);
            padding: 3px 0;
        }
        .acteur-card ul li::before { content: '→ '; color: var(--primary); font-weight: bold; }

        /* ── CTA ── */
        .cta {
            background: linear-gradient(135deg, #1e293b, #2563eb);
            color: white;
            text-align: center;
            padding: 70px 5%;
        }

        .cta h2 { font-size: 28px; font-weight: 700; margin-bottom: 12px; }
        .cta p { color: #bfdbfe; font-size: 16px; margin-bottom: 32px; }

        /* ── FOOTER ── */
        footer {
            background: var(--slate);
            color: #94a3b8;
            text-align: center;
            padding: 24px 5%;
            font-size: 13px;
        }

        @media (max-width: 600px) {
            .stats { gap: 24px; }
            .hero { padding: 60px 5%; }
        }
    </style>
</head>
<body>

<!-- NAV -->
<nav>
    <div class="nav-brand">🎓 <span>UATM</span> GASA</div>
    <a href="/app/views/auth/login.php" class="nav-btn">Se connecter →</a>
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
            🔐 Accéder à la plateforme
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
            <div class="feature-icon">📄</div>
            <h3>Dépôt de mémoire</h3>
            <p>Les étudiants déposent leur mémoire en PDF directement sur la plateforme avec leur binôme.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">✅</div>
            <h3>Validation encadreur</h3>
            <p>L'encadreur consulte le document et valide ou rejette le mémoire en un clic.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📅</div>
            <h3>Planification</h3>
            <p>L'administrateur planifie les soutenances et compose les jurys de 3 membres avec leurs rôles.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">⚖️</div>
            <h3>Évaluation et délibération</h3>
            <p>Le jury consulte le mémoire, attribue une note et une mention. La décision s'affiche automatiquement.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📊</div>
            <h3>Tableau de bord</h3>
            <p>Chaque acteur dispose d'un espace personnalisé selon son rôle dans le système.</p>
        </div>
        <div class="feature-card">
            <div class="feature-icon">📱</div>
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
            <div class="ico">🎓</div>
            <h3>Etudiant</h3>
            <ul>
                <li>Déposer son mémoire</li>
                <li>Choisir son binôme</li>
                <li>Consulter le planning</li>
                <li>Voir sa note finale</li>
            </ul>
        </div>
        <div class="acteur-card">
            <div class="ico">👨‍🏫</div>
            <h3>Encadreur</h3>
            <ul>
                <li>Consulter les mémoires</li>
                <li>Télécharger le PDF</li>
                <li>Valider ou rejeter</li>
            </ul>
        </div>
        <div class="acteur-card">
            <div class="ico">⚖️</div>
            <h3>Jury</h3>
            <ul>
                <li>Voir le planning</li>
                <li>Lire le mémoire PDF</li>
                <li>Attribuer une note</li>
                <li>Délibérer</li>
            </ul>
        </div>
        <div class="acteur-card">
            <div class="ico">🛠️</div>
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
        🔐 Se connecter à la plateforme
    </a>
</section>

<!-- FOOTER -->
<footer>
    <p>© <?= date('Y') ?> UATM GASA — Système de gestion des soutenances. Tous droits réservés.</p>
</footer>

</body>
</html>