
<?php
session_start();

require_once __DIR__ . '/../../config/config.php';
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../helpers/security.php';
require_once __DIR__ . '/../models/Soutenance.php';
require_once __DIR__ . '/../models/Memoire.php';
require_once __DIR__ . '/../models/Jury.php';
require_once __DIR__ . '/../models/Utilisateur.php';

// Vérification des droits d'administration.
if (
    !isset($_SESSION['idUtilisateur'])
    || ($_SESSION['role'] ?? null) !== 'administrateur'
) {
    header('Location: /app/views/auth/login.php');
    exit();
}

$action = $_GET['action'] ?? 'dashboard';

// Valider un identifiant entier positif.
$validerId = static function ($value): int|false {
    if (!is_string($value) && !is_int($value)) {
        return false;
    }

    $id = filter_var($value, FILTER_VALIDATE_INT);

    return ($id !== false && $id > 0) ? $id : false;
};

// Récupérer une chaîne POST propre.
$postTexte = static function (string $key): string|false {
    $value = $_POST[$key] ?? null;

    if (!is_string($value)) {
        return false;
    }

    return trim($value);
};

switch ($action) {

    case 'dashboard':
        $soutenances = getAllSoutenances($db);
        $memoires = getMemoiresEnAttente($db);

        require_once VIEWS_PATH . '/admin/dashboard.php';
        break;

    case 'planifier':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf_token();

            $idMemoire = $validerId($_POST['idMemoire'] ?? null);
            $president = $validerId($_POST['president'] ?? null);
            $rapporteur = $validerId($_POST['rapporteur'] ?? null);
            $examinateur = $validerId($_POST['examinateur'] ?? null);

            $date = $postTexte('date');
            $heure = $postTexte('heure');
            $salle = $postTexte('salle');

            $dateValide = false;

            if ($date !== false) {
                $dateObjet = DateTime::createFromFormat('!Y-m-d', $date);
                $dateValide = $dateObjet
                    && $dateObjet->format('Y-m-d') === $date;
            }

            $heureValide = $heure !== false
                && preg_match('/^(?:[01]\d|2[0-3]):[0-5]\d$/', $heure);

            if (
                !$idMemoire
                || !$president
                || !$rapporteur
                || !$examinateur
                || count(array_unique([
                    $president,
                    $rapporteur,
                    $examinateur
                ])) !== 3
                || !$dateValide
                || !$heureValide
                || $salle === false
                || $salle === ''
                || mb_strlen($salle) > 100
            ) {
                $_SESSION['erreur'] = 'Veuillez vérifier les informations de la soutenance et les trois membres du jury.';
                header('Location: /app/controllers/AdminController.php?action=planifier');
                exit();
            }

            try {
                // Vérifier que le mémoire est validé et pas encore planifié.
                $checkMemoire = $db->prepare("
                    SELECT m.idMemoire
                    FROM Memoire m
                    WHERE m.idMemoire = ?
                      AND m.statut = 'valide'
                      AND NOT EXISTS (
                          SELECT 1
                          FROM Soutenance s
                          WHERE s.idMemoire = m.idMemoire
                      )
                ");
                $checkMemoire->execute([$idMemoire]);

                if (!$checkMemoire->fetch()) {
                    throw new RuntimeException(
                        'Ce mémoire est introuvable, non validé ou déjà planifié.'
                    );
                }

                // Vérifier l'existence des trois membres.
                $checkMembres = $db->prepare("
                    SELECT idMembreJury
                    FROM MembreJury
                    WHERE idMembreJury IN (?, ?, ?)
                ");
                $checkMembres->execute([
                    $president,
                    $rapporteur,
                    $examinateur
                ]);

                if ($checkMembres->rowCount() !== 3) {
                    throw new RuntimeException(
                        'Un ou plusieurs membres du jury sont introuvables.'
                    );
                }

                // Création complète dans une seule transaction.
                $db->beginTransaction();

                $idJury = createJury($db);

                addMembreToJury($db, $idJury, $president, 'president');
                addMembreToJury($db, $idJury, $rapporteur, 'rapporteur');
                addMembreToJury($db, $idJury, $examinateur, 'examinateur');

                createSoutenance($db, [
                    'idMemoire' => $idMemoire,
                    'idAdministrateur' => $_SESSION['idUtilisateur'],
                    'idJury' => $idJury,
                    'date' => $date,
                    'heure' => $heure,
                    'salle' => $salle
                ]);

                $db->commit();

                $_SESSION['succes'] = 'Soutenance planifiée avec succès.';

            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }

                $_SESSION['erreur'] = 'Impossible de planifier cette soutenance. Vérifiez les données et réessayez.';
            }

            header('Location: /app/controllers/AdminController.php?action=dashboard');
            exit();
        }

        $memoires = getMemoiresValides($db);
        $membres = getAllMembresJury($db);

        require_once VIEWS_PATH . '/admin/planifier_soutenance.php';
        break;

    case 'affecter_jury':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf_token();

            $idSoutenance = $validerId($_POST['idSoutenance'] ?? null);
            $idJury = $validerId($_POST['idJury'] ?? null);

            if (!$idSoutenance || !$idJury) {
                $_SESSION['erreur'] = 'Identifiants invalides.';
            } else {
                $check = $db->prepare("
                    SELECT s.idSoutenance, j.idJury
                    FROM Soutenance s
                    CROSS JOIN Jury j
                    WHERE s.idSoutenance = ?
                      AND j.idJury = ?
                ");
                $check->execute([$idSoutenance, $idJury]);

                if ($check->fetch()) {
                    affecterJury($db, $idSoutenance, $idJury);
                    $_SESSION['succes'] = 'Jury affecté avec succès.';
                } else {
                    $_SESSION['erreur'] = 'Soutenance ou jury introuvable.';
                }
            }

            header('Location: /app/controllers/AdminController.php?action=dashboard');
            exit();
        }

        $soutenances = getAllSoutenances($db);
        $jurys = getAllJury($db);

        require_once VIEWS_PATH . '/admin/affecter_jury.php';
        break;

    case 'utilisateurs':

        $type = $_GET['type'] ?? 'etudiant';

        if (!in_array($type, ['etudiant', 'encadreur', 'membre_jury'], true)) {
            $type = 'etudiant';
        }

        $etudiants = getAllEtudiants($db);
        $encadreurs = getAllEncadreurs($db);
        $jurys_m = getAllMembresJury($db);

        require_once VIEWS_PATH . '/admin/utilisateurs.php';
        break;

    case 'creer_utilisateur':

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf_token();

            $nom = $postTexte('nom');
            $prenom = $postTexte('prenom');
            $login = $postTexte('login');
            $motDePasse = $postTexte('motDePasse');
            $role = $postTexte('role');
            $extra = $postTexte('extra');

            $rolesAutorises = [
                'etudiant',
                'encadreur',
                'membre_jury'
            ];

            if (
                $nom === false || $nom === ''
                || $prenom === false || $prenom === ''
                || $login === false || $login === ''
                || $motDePasse === false || strlen($motDePasse) < 8
                || !in_array($role, $rolesAutorises, true)
                || (
                    in_array($role, ['etudiant', 'encadreur'], true)
                    && ($extra === false || $extra === '')
                )
                || mb_strlen($nom) > 255
                || mb_strlen($prenom) > 255
                || mb_strlen($login) > 50
                || ($extra !== false && mb_strlen($extra) > 100)
            ) {
                $_SESSION['erreur'] = 'Veuillez vérifier les informations saisies. Le mot de passe doit contenir au moins 8 caractères.';
                header('Location: /app/controllers/AdminController.php?action=creer_utilisateur');
                exit();
            }

            try {
                $db->beginTransaction();

                $idNouvel = createUtilisateur($db, [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'login' => $login,
                    'motDePasse' => $motDePasse,
                    'role' => $role
                ]);

                if ($idNouvel === false) {
                    throw new RuntimeException('Ce login est déjà utilisé.');
                }

                if ($role === 'etudiant') {
                    createEtudiant($db, $idNouvel, $extra);
                } elseif ($role === 'encadreur') {
                    createEncadreur($db, $idNouvel, $extra);
                } elseif ($role === 'membre_jury') {
                    createMembreJury($db, $idNouvel);
                }

                $db->commit();

                $_SESSION['succes'] = 'Utilisateur créé avec succès.';

                header('Location: /app/controllers/AdminController.php?action=utilisateurs');
                exit();

            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }

                $_SESSION['erreur'] = 'Création impossible. Vérifiez les informations et assurez-vous que le login est disponible.';
            }
        }

        require_once VIEWS_PATH . '/admin/creer_utilisateur.php';
        break;

    case 'modifier_utilisateur':

        $id = $validerId($_GET['id'] ?? null);

        if (!$id) {
            header('Location: /app/controllers/AdminController.php?action=utilisateurs');
            exit();
        }

        $user = getUserById($db, $id);

        if (!$user || $user['role'] === 'administrateur') {
            header('Location: /app/controllers/AdminController.php?action=utilisateurs');
            exit();
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            verify_csrf_token();

            $nom = $postTexte('nom');
            $prenom = $postTexte('prenom');
            $login = $postTexte('login');
            $motDePasse = $postTexte('motDePasse');

            $extra = null;

            if (in_array($user['role'], ['etudiant', 'encadreur'], true)) {
                $extra = $postTexte('extra');
            }

            if (
                $nom === false || $nom === ''
                || $prenom === false || $prenom === ''
                || $login === false || $login === ''
                || mb_strlen($nom) > 255
                || mb_strlen($prenom) > 255
                || mb_strlen($login) > 50
                || (
                    $motDePasse === false
                    || ($motDePasse !== '' && strlen($motDePasse) < 8)
                )
                || (
                    in_array($user['role'], ['etudiant', 'encadreur'], true)
                    && ($extra === false || $extra === '')
                )
            ) {
                $_SESSION['erreur'] = 'Veuillez vérifier les informations saisies.';
                header("Location: /app/controllers/AdminController.php?action=modifier_utilisateur&id=$id");
                exit();
            }

            try {
                // Empêcher l'utilisation du login d'un autre compte.
                $checkLogin = $db->prepare("
                    SELECT idUtilisateur
                    FROM Utilisateur
                    WHERE login = ?
                      AND idUtilisateur <> ?
                ");
                $checkLogin->execute([$login, $id]);

                if ($checkLogin->fetch()) {
                    throw new RuntimeException('Login déjà utilisé.');
                }

                $db->beginTransaction();

                updateUtilisateur($db, $id, [
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'login' => $login
                ]);

                if ($user['role'] === 'etudiant') {
                    updateEtudiant($db, $id, $extra);
                } elseif ($user['role'] === 'encadreur') {
                    updateEncadreur($db, $id, $extra);
                }

                if ($motDePasse !== '') {
                    updateMotDePasse($db, $id, $motDePasse);
                }

                $db->commit();

                $_SESSION['succes'] = 'Utilisateur modifié avec succès.';

                header('Location: /app/controllers/AdminController.php?action=utilisateurs');
                exit();

            } catch (Throwable $e) {
                if ($db->inTransaction()) {
                    $db->rollBack();
                }

                $_SESSION['erreur'] = 'Modification impossible. Vérifiez les informations et le login.';
                header("Location: /app/controllers/AdminController.php?action=modifier_utilisateur&id=$id");
                exit();
            }
        }

        require_once VIEWS_PATH . '/admin/modifier_utilisateur.php';
        break;

    case 'supprimer_utilisateur':

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        verify_csrf_token();

        $id = $validerId($_POST['id'] ?? null);
        $role = $postTexte('role');

        if (!$id || !in_array($role, [
            'etudiant',
            'encadreur',
            'membre_jury'
        ], true)) {
            $_SESSION['erreur'] = 'Informations de suppression invalides.';
        } else {
            try {
                if (deleteUtilisateur($db, $id, $role)) {
                    $_SESSION['succes'] = 'Utilisateur supprimé.';
                } else {
                    $_SESSION['erreur'] = 'Utilisateur introuvable ou rôle incorrect.';
                }
            } catch (Throwable $e) {
                $_SESSION['erreur'] = 'Suppression impossible : cet utilisateur possède probablement des données liées.';
            }
        }

        header('Location: /app/controllers/AdminController.php?action=utilisateurs');
        exit();

    case 'reinitialiser':

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Méthode non autorisée.');
        }

        verify_csrf_token();

        $confirmation = $postTexte('confirmation');
        $motDePasse = $postTexte('motDePasse');

        if (
            $confirmation !== 'REINITIALISER'
            || $motDePasse === false
            || strlen($motDePasse) < 8
        ) {
            $_SESSION['erreur'] = 'Saisissez REINITIALISER et un mot de passe d’au moins 8 caractères.';
            header('Location: /app/controllers/AdminController.php?action=dashboard');
            exit();
        }

        try {
            $idAdministrateur = reinitialiserApplication($db, $motDePasse);

            $_SESSION['idUtilisateur'] = $idAdministrateur;
            $_SESSION['role'] = 'administrateur';
            $_SESSION['nom'] = 'Administrateur';
            $_SESSION['prenom'] = 'Système';

            // Renouveler le jeton CSRF après la réinitialisation.
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));

            $_SESSION['succes'] = 'Réinitialisation terminée. Vous pouvez recréer les comptes.';

        } catch (Throwable $e) {
            $_SESSION['erreur'] = 'La réinitialisation a échoué.';
        }

        header('Location: /app/controllers/AdminController.php?action=dashboard');
        exit();

    default:
        http_response_code(404);
        exit('Action introuvable.');
}
