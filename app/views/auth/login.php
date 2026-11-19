<?php
session_start();
require_once __DIR__ . '/../../../app/helpers/security.php';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion — UATM GASA</title>
    <link rel="stylesheet" href="/public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>
<body>

<div class="login-page">
    <div class="login-box">

        <div class="login-logo"><i class="fa-solid fa-graduation-cap" aria-hidden="true"></i></div>
        <h1 class="login-title">UATM GASA</h1>
        <p class="login-sub">Système de gestion des soutenances</p>

        <?php if (isset($_GET['error'])): ?>
        <div class="alert alert-error">
            <i class="fa-solid fa-circle-exclamation" aria-hidden="true"></i> Login ou mot de passe incorrect. Veuillez réessayer.
        </div>
        <?php endif; ?>

        <form action="/app/controllers/AuthController.php" method="POST">
            <input type="hidden" name="csrf_token" value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

            <div class="form-group">
                <label for="login">Identifiant</label>
                <input type="text"
                       id="login"
                       name="login"
                       placeholder="Votre identifiant"
                       autocomplete="username"
                       required
                       autofocus>
            </div>

            <div class="form-group">
                <label for="pwd">Mot de passe</label>
                <input type="password"
                       id="pwd"
                       name="pwd"
                       placeholder="••••••••"
                       autocomplete="current-password"
                       required>
            </div>

            <button type="submit" class="btn">
                <i class="fa-solid fa-right-to-bracket" aria-hidden="true"></i>
                Se connecter
            </button>

        </form>

        <p class="login-copyright">
            UATM GASA &copy; <?= date('Y') ?>
        </p>

    </div>
</div>

</body>
</html>
