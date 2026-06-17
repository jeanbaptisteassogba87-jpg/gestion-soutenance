<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Evaluer un étudiant</h1>
    <a href="/app/controllers/JuryController.php?action=dashboard" class="btn btn-outline">← Retour</a>
</div>

<?php if (empty($soutenances)): ?>
    <div class="alert alert-error">⚠️ Aucune soutenance assignée.</div>
<?php else: ?>

<form action="/app/controllers/JuryController.php?action=evaluer"
      method="POST"
      class="form-card">

    <h3>✏️ Attribuer une note</h3>

    <div class="form-group">
        <label>Soutenance</label>
        <select name="idSoutenance" id="selectSoutenance" onchange="afficherDetails()" required>
            <option value="">-- Choisir une soutenance --</option>
            <?php foreach ($soutenances as $s): ?>
            <option value="<?= $s['idSoutenance'] ?>"
                    data-titre="<?= htmlspecialchars($s['titre']) ?>"
                    data-etu1="<?= htmlspecialchars($s['nom_etudiant1'] . ' ' . $s['prenom_etudiant1']) ?>"
                    data-etu2="<?= isset($s['nom_etudiant2']) ? htmlspecialchars($s['nom_etudiant2'] . ' ' . $s['prenom_etudiant2']) : '' ?>"
                    data-fichier="<?= $s['fichier'] ?? '' ?>"
                    data-note="<?= $s['note'] ?? '' ?>">
                Soutenance #<?= $s['idSoutenance'] ?> — <?= $s['date'] ?> à <?= $s['heure'] ?>
            </option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Détails dynamiques de la soutenance sélectionnée -->
    <div id="details" style="display:none; background:#f9f9ff; border-radius:8px; padding:16px; border:1px solid #e0e0f0;">
        <p style="font-size:13px;color:#555;margin-bottom:6px"><strong>Mémoire :</strong> <span id="d-titre"></span></p>
        <p style="font-size:13px;color:#555;margin-bottom:6px"><strong>Etudiant 1 :</strong> <span id="d-etu1"></span></p>
        <p style="font-size:13px;color:#555;margin-bottom:10px"><strong>Etudiant 2 :</strong> <span id="d-etu2"></span></p>
        <a id="d-pdf" href="#" target="_blank" class="btn btn-sm btn-outline">📄 Voir le mémoire PDF</a>
        <span id="d-nopdf" style="color:#aaa;font-size:13px;display:none">Aucun fichier disponible</span>
    </div>

    <div class="form-group">
        <label>Note (sur 20)</label>
        <input type="number" name="note" id="note" min="0" max="20" placeholder="Ex : 14" required>
    </div>

    <div class="form-group">
        <label>Mention</label>
        <select name="mention" id="mention" required>
            <option value=""> Choisir </option>
            <option value="Passable">Passable (10 - 12)</option>
            <option value="Assez Bien">Assez Bien (12 - 14)</option>
            <option value="Bien">Bien (14 - 16)</option>
            <option value="Tres Bien">Très Bien (16 - 18)</option>
            <option value="Excellent">Excellent (18 - 20)</option>
        </select>
    </div>

    <button type="submit" class="btn">✅ Valider la note</button>
</form>

<script>
function afficherDetails() {
    const select = document.getElementById('selectSoutenance');
    const option = select.options[select.selectedIndex];
    const details = document.getElementById('details');

    if (!option.value) {
        details.style.display = 'none';
        return;
    }

    // Afficher les détails
    details.style.display = 'block';
    document.getElementById('d-titre').textContent = option.dataset.titre;
    document.getElementById('d-etu1').textContent  = option.dataset.etu1;
    document.getElementById('d-etu2').textContent  = option.dataset.etu2 || '—';

    // Lien PDF
    const fichier = option.dataset.fichier;
    const pdfLink = document.getElementById('d-pdf');
    const noPdf   = document.getElementById('d-nopdf');
    if (fichier) {
        pdfLink.href = '/public/uploads/memoires/' + fichier;
        pdfLink.style.display = 'inline-block';
        noPdf.style.display   = 'none';
    } else {
        pdfLink.style.display = 'none';
        noPdf.style.display   = 'inline';
    }

    // Pré-remplir note si déjà notée
    if (option.dataset.note) {
        document.getElementById('note').value = option.dataset.note;
    }
}
</script>

<script>
document.getElementById('note').addEventListener('input', function() {
    let note = parseFloat(this.value);
    let mentionSelect = document.getElementById('mention');

    if (isNaN(note)) {
        mentionSelect.value = '';
        return;
    }

    if (note >= 18) {
        mentionSelect.value = 'Excellent';
    } else if (note >= 16) {
        mentionSelect.value = 'Tres Bien';
    } else if (note >= 14) {
        mentionSelect.value = 'Bien';
    } else if (note >= 12) {
        mentionSelect.value = 'Assez Bien';
    } else if (note >= 10) {
        mentionSelect.value = 'Passable';
    } else {
        mentionSelect.value = '';
    }
});
</script>

<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>