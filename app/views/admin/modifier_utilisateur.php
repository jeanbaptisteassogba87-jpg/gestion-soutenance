
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Modifier un utilisateur</h1>
    <a href="/app/controllers/AdminController.php?action=utilisateurs" class="btn btn-outline">← Retour</a>
</div>

<?php if (isset($erreur)): ?>
    <div class="alert alert-error">
        ⚠️ <?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<form action="/app/controllers/AdminController.php?action=modifier_utilisateur&id=<?= (int)$user['idUtilisateur'] ?>"
      method="POST" class="form-card">

    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

    <h3>👤 Informations personnelles</h3>

    <div class="form-group">
        <label>Nom</label>
        <input type="text" name="nom"
               value="<?= htmlspecialchars($user['nom'], ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div class="form-group">
        <label>Prénom</label>
        <input type="text" name="prenom"
               value="<?= htmlspecialchars($user['prenom'], ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <div class="form-group">
        <label>Login</label>
        <input type="text" name="login"
               value="<?= htmlspecialchars($user['login'], ENT_QUOTES, 'UTF-8') ?>" required>
    </div>

    <h3>🔒 Changer le mot de passe</h3>

    <div class="form-group">
        <label>Nouveau mot de passe</label>
        <input type="password" name="motDePasse"
               placeholder="Laisser vide pour ne pas changer" minlength="8">
        <small>Minimum 8 caractères. Si vide, le mot de passe actuel est conservé.</small>
    </div>

    <?php if ($user['role'] === 'etudiant'): ?>
        <h3>🎓 Informations étudiant</h3>

        <?php
            $etudiant = $db->prepare('SELECT filiere FROM Etudiant WHERE idEtudiant = ?');
            $etudiant->execute([$user['idUtilisateur']]);
            $etudiantData = $etudiant->fetch();
        ?>

        <div class="form-group">
            <label>Filière</label>
            <input type="text" name="extra"
                   value="<?= htmlspecialchars($etudiantData['filiere'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>

    <?php elseif ($user['role'] === 'encadreur'): ?>
        <h3>👨‍🏫 Informations encadreur</h3>

        <?php
            $enc = $db->prepare('SELECT specialite FROM Encadreur WHERE idEncadreur = ?');
            $enc->execute([$user['idUtilisateur']]);
            $encData = $enc->fetch();
        ?>

        <div class="form-group">
            <label>Spécialité</label>
            <input type="text" name="extra"
                   value="<?= htmlspecialchars($encData['specialite'] ?? '', ENT_QUOTES, 'UTF-8') ?>" required>
        </div>
    <?php endif; ?>

    <div style="display:flex;gap:10px;align-items:center">
        <button type="submit" class="btn">💾 Enregistrer</button>
        <span style="font-size:13px;color:var(--text-soft)">
            Rôle : <strong><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $user['role'])), ENT_QUOTES, 'UTF-8') ?></strong>
        </span>
    </div>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
