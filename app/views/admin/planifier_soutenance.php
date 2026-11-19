<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Planifier une soutenance</h1>
    <a href="/app/controllers/AdminController.php?action=dashboard" class="btn btn-outline">← Retour</a>
</div>

<?php if (isset($erreur)): ?>
    <div class="alert alert-error">⚠️ <?= $erreur ?></div>
<?php endif; ?>

<?php if (empty($memoires)): ?>
    <div class="alert alert-error">
        ⚠️ Aucun mémoire validé disponible. L'encadreur doit d'abord valider un mémoire.
    </div>
<?php else: ?>

<form action="/app/controllers/AdminController.php?action=planifier"

      method="POST"
      class="form-card">

      <input type="hidden" name="csrf_token"
       value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

    <h3>📄 Informations de la soutenance</h3>

    <div class="form-group">
        <label>Mémoire concerné (validés uniquement)</label>
        <select name="idMemoire" required>
            <option value="">-- Choisir un mémoire --</option>
            <?php foreach ($memoires as $m): ?>
            <option value="<?= $m['idMemoire'] ?>">
                <?= htmlspecialchars($m['titre']) ?>
                — <?= htmlspecialchars($m['nom_etudiant1'] . ' ' . $m['prenom_etudiant1']) ?>
                <?= isset($m['nom_etudiant2']) ? '& ' . htmlspecialchars($m['nom_etudiant2'] . ' ' . $m['prenom_etudiant2']) : '' ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Date</label>
        <input type="date" name="date" required>
    </div>

    <div class="form-group">
        <label>Heure</label>
        <input type="time" name="heure" required>
    </div>

    <div class="form-group">
        <label>Salle</label>
        <input type="text" name="salle" placeholder="Ex : Salle A203" required>
    </div>

    <h3>👥 Composition du jury</h3>
    <p style="font-size:13px;color:#888;margin-top:-8px">Chaque membre ne peut avoir qu'un seul rôle.</p>

    <div class="form-group">
        <label>Président</label>
        <select name="president" id="president" onchange="updateSelects()" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($membres as $m): ?>
            <option value="<?= $m['idMembreJury'] ?>">
                <?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Rapporteur</label>
        <select name="rapporteur" id="rapporteur" onchange="updateSelects()" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($membres as $m): ?>
            <option value="<?= $m['idMembreJury'] ?>">
                <?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Examinateur</label>
        <select name="examinateur" id="examinateur" onchange="updateSelects()" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($membres as $m): ?>
            <option value="<?= $m['idMembreJury'] ?>">
                <?= htmlspecialchars($m['nom'] . ' ' . $m['prenom']) ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn">✅ Planifier la soutenance</button>
</form>

<script>
function updateSelects() {
    const selects = ['president', 'rapporteur', 'examinateur'];
    
    // Récupérer les valeurs choisies
    const chosen = {};
    selects.forEach(id => {
        chosen[id] = document.getElementById(id).value;
    });

    // Pour chaque select, désactiver les options choisies par les autres
    selects.forEach(id => {
        const select = document.getElementById(id);
        Array.from(select.options).forEach(option => {
            if (option.value === '') return; // Ignorer l'option vide
            // Désactiver si choisie par un autre select
            const chosenByOther = selects
                .filter(otherId => otherId !== id)
                .some(otherId => chosen[otherId] === option.value);
            option.disabled = chosenByOther;
            option.style.color = chosenByOther ? '#ccc' : '';
        });
    });
}
</script>

<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>