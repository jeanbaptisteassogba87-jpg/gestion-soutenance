
<?php
session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../models/Utilisateur.php';
require_once __DIR__ . '/../helpers/security.php';

// DECONNEXION
if (($_GET['action'] ?? '') === 'logout') {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        http_response_code(405);
        exit('Méthode non autorisée.');
    }

    verify_csrf_token();

    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();

        setcookie(
            session_name(),
            '',
            time() - 42000,
            $params['path'],
            $params['domain'],
            $params['secure'],
            $params['httponly']
        );
    }

    session_destroy();

    header('Location: /app/views/auth/login.php');
    exit();
}

// CONNEXION
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    verify_csrf_token();

    $login = trim($_POST['login'] ?? '');
    $pwd = $_POST['pwd'] ?? '';

    if ($login === '' || !is_string($pwd) || $pwd === '') {
        header('Location: /app/views/auth/login.php?error=1');
        exit();
    }

    $user = getUserByLogin($db, $login);

    if (!$user || !password_verify($pwd, $user['motDePasse'])) {
        header('Location: /app/views/auth/login.php?error=1');
        exit();
    }

    $destinations = [
        'administrateur' => '/app/controllers/AdminController.php',
        'etudiant' => '/app/controllers/EtudiantController.php',
        'encadreur' => '/app/controllers/EncadreurController.php',
        'membre_jury' => '/app/controllers/JuryController.php',
    ];

    if (!isset($destinations[$user['role']])) {
        header('Location: /app/views/auth/login.php?error=1');
        exit();
    }

    session_regenerate_id(true);

    $_SESSION['idUtilisateur'] = $user['idUtilisateur'];
    $_SESSION['role'] = $user['role'];
    $_SESSION['nom'] = $user['nom'];
    $_SESSION['prenom'] = $user['prenom'];

    // Nouveau jeton après authentification
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

    header('Location: ' . $destinations[$user['role']]);
    exit();
}

header('Location: /app/views/auth/login.php');
exit();
