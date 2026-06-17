<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Déposer un mémoire</h1>
    <a href="/app/controllers/EtudiantController.php?action=dashboard" class="btn btn-outline">← Retour</a>
</div>

<?php if (isset($erreur)): ?>
    <div class="alert alert-error">⚠️ <?= $erreur ?></div>
<?php endif; ?>

<form action="/app/controllers/EtudiantController.php?action=deposer"
      method="POST"
      enctype="multipart/form-data"
      class="form-card">

    <h3>📄 Informations du mémoire</h3>

    <div class="form-group">
        <label>Titre du mémoire</label>
        <input type="text" name="titre" placeholder="Ex : Conception d'un système de gestion..." required>
    </div>

    <div class="form-group">
        <label>Binôme (votre co-étudiant)</label>
        <select name="idEtudiant2" required>
            <option value="">-- Choisir votre binôme --</option>
            <?php foreach ($etudiants as $e): ?>
            <option value="<?= $e['idEtudiant'] ?>">
                <?= htmlspecialchars($e['nom'] . ' ' . $e['prenom']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Encadreur</label>
        <select name="idEncadreur" required>
            <option value="">-- Choisir votre encadreur --</option>
            <?php foreach ($encadreurs as $e): ?>
            <option value="<?= $e['idEncadreur'] ?>">
                <?= htmlspecialchars($e['nom'] . ' ' . $e['prenom']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Fichier PDF du mémoire</label>
        <input type="file" name="fichier" accept=".pdf" required>
        <small style="color:#888;font-size:12px">Fichier PDF uniquement — max 10 Mo</small>
    </div>

    <button type="submit" class="btn">📤 Soumettre le mémoire</button>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>