<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — UATM GASA</title>
    <link rel="stylesheet" href="/public/css/style.css">
</head>
<body>

<div class="login-page">
    <div class="login-box">

        <div class="login-logo">🎓</div>
        <h1 class="login-title">UATM GASA</h1>
        <p class="login-sub">Système de gestion des soutenances</p>

        <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            ⚠️ Login ou mot de passe incorrect. Veuillez réessayer.
        </div>
        <?php endif; ?>

        <form action="/app/controllers/AuthController.php" method="POST">

            <div class="form-group" style="margin-bottom:14px">
                <label for="login">Identifiant</label>
                <input type="text"
                       id="login"
                       name="login"
                       placeholder="Votre identifiant"
                       autocomplete="username"
                       required
                       autofocus>
            </div>

            <div class="form-group" style="margin-bottom:20px">
                <label for="pwd">Mot de passe</label>
                <input type="password"
                       id="pwd"
                       name="pwd"
                       placeholder="••••••••"
                       autocomplete="current-password"
                       required>
            </div>

            <button type="submit" class="btn" style="width:100%;justify-content:center;padding:11px;">
                Se connecter
            </button>

        </form>

        <p style="text-align:center;font-size:12px;color:#94a3b8;margin-top:24px;">
            UATM GASA &copy; <?= date('Y') ?>
        </p>

    </div>
</div>

</body>
</html>