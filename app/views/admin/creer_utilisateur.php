
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Ajouter un utilisateur</h1>
    <a href="/app/controllers/AdminController.php?action=utilisateurs" class="btn btn-outline">← Retour</a>
</div>

<?php if (isset($erreur)): ?>
    <div class="alert alert-error">
         <?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<form action="/app/controllers/AdminController.php?action=creer_utilisateur" method="POST" class="form-card">

    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

    <h3><i class="fa-solid fa-user" aria-hidden="true"></i> Informations personnelles</h3>

    <div class="form-group">
        <label>Nom</label>
        <input type="text" name="nom" placeholder="Ex : Dossou" required>
    </div>

    <div class="form-group">
        <label>Prénom</label>
        <input type="text" name="prenom" placeholder="Ex : Hervé" required>
    </div>

    <div class="form-group">
        <label>Login</label>
        <input type="text" name="login" placeholder="Ex : hdossou" required>
        <small>Doit être unique dans le système.</small>
    </div>

    <div class="form-group">
        <label>Mot de passe</label>
        <input type="password" name="motDePasse"
               placeholder="Minimum 8 caractères" required minlength="8">
    </div>

    <h3><i class="fa-solid fa-id-badge" aria-hidden="true"></i> Rôle et informations spécifiques</h3>

    <div class="form-group">
        <label>Rôle</label>
        <select name="role" id="roleSelect" onchange="toggleExtra()" required>
            <option value="">-- Choisir un rôle --</option>
            <option value="etudiant">Étudiant</option>
            <option value="encadreur"> Encadreur</option>
            <option value="membre_jury">Membre du jury</option>
        </select>
    </div>

    <div class="form-group" id="extraField" style="display:none">
        <label id="extraLabel">Filière</label>
        <input type="text" name="extra" id="extraInput" placeholder="">
        <small id="extraHelp"></small>
    </div>

    <button type="submit" class="btn"><i class="fa-solid fa-user-plus" aria-hidden="true"></i> Créer l'utilisateur</button>
</form>

<script>
function toggleExtra() {
    const role = document.getElementById('roleSelect').value;
    const field = document.getElementById('extraField');
    const label = document.getElementById('extraLabel');
    const input = document.getElementById('extraInput');
    const help = document.getElementById('extraHelp');

    if (role === 'etudiant') {
        field.style.display = 'flex';
        label.textContent = 'Filière';
        input.placeholder = 'Ex : Licence Informatique';
        input.required = true;
        help.textContent = 'La filière dans laquelle est inscrit l\'étudiant.';
    } else if (role === 'encadreur') {
        field.style.display = 'flex';
        label.textContent = 'Spécialité';
        input.placeholder = 'Ex : Génie Logiciel';
        input.required = true;
        help.textContent = 'Le domaine de spécialité de l\'encadreur.';
    } else {
        field.style.display = 'none';
        input.required = false;
    }
}
</script>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
