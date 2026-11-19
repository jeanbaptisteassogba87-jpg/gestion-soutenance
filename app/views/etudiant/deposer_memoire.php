
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Déposer un mémoire</h1>
    <a href="/app/controllers/EtudiantController.php?action=dashboard" class="btn btn-outline">← Retour</a>
</div>

<?php if (!empty($erreur)): ?>
    <div class="alert alert-error">
        <?= htmlspecialchars($erreur, ENT_QUOTES, 'UTF-8') ?>
    </div>
<?php endif; ?>

<form action="/app/controllers/EtudiantController.php?action=deposer"
      method="POST"
      enctype="multipart/form-data"
      class="form-card">

    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

    <h3>📄 Informations du mémoire</h3>

    <div class="form-group">
        <label>Titre du mémoire</label>
        <input type="text"
               name="titre"
               maxlength="255"
               value="<?= htmlspecialchars($_POST['titre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
               placeholder="Ex : Conception d'un système de gestion..."
               required>
    </div>

    <div class="form-group">
        <label>Binôme (votre co-étudiant)</label>
        <select name="idEtudiant2">
            <option value="">-- Aucun binôme (mémoire individuel) --</option>
            <?php foreach ($etudiants as $e): ?>
                <option value="<?= (int) $e['idEtudiant'] ?>"
                    <?= (isset($_POST['idEtudiant2']) && (string) $_POST['idEtudiant2'] === (string) $e['idEtudiant']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['nom'] . ' ' . $e['prenom'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Encadreur</label>
        <select name="idEncadreur" required>
            <option value="">-- Choisir votre encadreur --</option>
            <?php foreach ($encadreurs as $e): ?>
                <option value="<?= (int) $e['idEncadreur'] ?>"
                    <?= (isset($_POST['idEncadreur']) && (string) $_POST['idEncadreur'] === (string) $e['idEncadreur']) ? 'selected' : '' ?>>
                    <?= htmlspecialchars($e['nom'] . ' ' . $e['prenom'], ENT_QUOTES, 'UTF-8') ?>
                </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Fichier PDF du mémoire</label>
        <input type="file"
               name="fichier"
               accept=".pdf,application/pdf"
               required>
        <small style="color:#888;font-size:12px">
            Fichier PDF uniquement — maximum 10 Mo
        </small>
    </div>

    <button type="submit" class="btn">📤 Soumettre le mémoire</button>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
