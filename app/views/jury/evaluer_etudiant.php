
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Evaluer un étudiant</h1>
    <a href="/app/controllers/JuryController.php?action=dashboard" class="btn btn-outline">
        ← Retour
    </a>
</div>

<?php if (empty($soutenances)): ?>

    <div class="alert alert-error">
        Aucune soutenance assignée.
    </div>

<?php else: ?>

<form action="/app/controllers/JuryController.php?action=evaluer"
      method="POST"
      class="form-card">

    <h3>Attribuer une note</h3>

    <!-- Protection CSRF -->
    <input type="hidden"
           name="csrf_token"
           value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

    <div class="form-group">
        <label for="selectSoutenance">Soutenance</label>

        <select name="idSoutenance"
                id="selectSoutenance"
                onchange="afficherDetails()"
                required>

            <option value="">-- Choisir une soutenance --</option>

            <?php foreach ($soutenances as $s): ?>

                <option
                    value="<?= (int) $s['idSoutenance'] ?>"
                    data-titre="<?= htmlspecialchars($s['titre'] ?? '', ENT_QUOTES, 'UTF-8') ?>"
                    data-etu1="<?= htmlspecialchars(($s['nom_etudiant1'] ?? '') . ' ' . ($s['prenom_etudiant1'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                    data-etu2="<?= htmlspecialchars(isset($s['nom_etudiant2']) ? ($s['nom_etudiant2'] . ' ' . ($s['prenom_etudiant2'] ?? '')) : '', ENT_QUOTES, 'UTF-8') ?>"
                    data-idmemoire="<?= (int) $s['idMemoire'] ?>"
                    data-note="<?= htmlspecialchars((string) ($s['note'] ?? ''), ENT_QUOTES, 'UTF-8') ?>"
                >
                    Soutenance #<?= (int) $s['idSoutenance'] ?>
                    — <?= htmlspecialchars($s['date'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                    à <?= htmlspecialchars($s['heure'] ?? '', ENT_QUOTES, 'UTF-8') ?>
                </option>

            <?php endforeach; ?>

        </select>
    </div>

    <!-- Détails de la soutenance -->
    <div id="details"
         style="display:none; background:#f9f9ff; border-radius:8px; padding:16px; border:1px solid #e0e0f0;">

        <p style="font-size:13px; color:#555; margin-bottom:6px;">
            <strong>Mémoire :</strong>
            <span id="d-titre"></span>
        </p>

        <p style="font-size:13px; color:#555; margin-bottom:6px;">
            <strong>Etudiant 1 :</strong>
            <span id="d-etu1"></span>
        </p>

        <p style="font-size:13px; color:#555; margin-bottom:10px;">
            <strong>Etudiant 2 :</strong>
            <span id="d-etu2"></span>
        </p>

        <a id="d-pdf"
           href="#"
           target="_blank"
           rel="noopener noreferrer"
           class="btn btn-sm btn-outline">
            Voir le mémoire PDF
        </a>

        <span id="d-nopdf"
              style="color:#aaa; font-size:13px; display:none;">
            Aucun fichier disponible
        </span>

    </div>

    <!-- Saisie de la note -->
    <div class="form-group">
        <label for="note">Note (sur 20)</label>

        <input
            type="number"
            name="note"
            id="note"
            min="0"
            max="20"
            step="1"
            placeholder="Ex : 14"
            required
            oninput="calculerMention()"
        >
    </div>

    <!-- Mention calculée automatiquement -->
    <div class="form-group">
        <label>Mention calculée automatiquement</label>

        <p id="mention"
           style="font-weight:bold; font-size:16px; margin-top:8px;">
            —
        </p>

        <small style="color:#777;">
            La mention est déterminée automatiquement à partir de la note.
        </small>
    </div>

    <button type="submit" class="btn">
        Valider la note
    </button>

</form>

<script>
function afficherDetails() {
    const select = document.getElementById('selectSoutenance');
    const option = select.options[select.selectedIndex];

    const details = document.getElementById('details');
    const noteInput = document.getElementById('note');

    if (!option.value) {
        details.style.display = 'none';
        noteInput.value = '';
        calculerMention();
        return;
    }

    // Afficher les détails
    details.style.display = 'block';

    document.getElementById('d-titre').textContent = option.dataset.titre;
    document.getElementById('d-etu1').textContent = option.dataset.etu1;
    document.getElementById('d-etu2').textContent = option.dataset.etu2 || '—';

      // Afficher le fichier PDF de manière sécurisée
    const idMemoire = option.dataset.idmemoire;
    const pdfLink = document.getElementById('d-pdf');
    const noPdf = document.getElementById('d-nopdf');

    if (idMemoire) {
        pdfLink.href = '/app/controllers/TelechargerMemoireController.php?id=' + encodeURIComponent(idMemoire);
        pdfLink.style.display = 'inline-block';
        noPdf.style.display = 'none';
    } else {
        pdfLink.style.display = 'none';
        noPdf.style.display = 'inline';
    }
    // Charger la note existante ou vider le champ
    noteInput.value = option.dataset.note || '';

    // Actualiser la mention
    calculerMention();
}


// Calcul de la mention à titre indicatif dans l'interface.
// Le calcul définitif est effectué côté serveur.
function calculerMention() {
    const noteInput = document.getElementById('note');
    const mentionElement = document.getElementById('mention');

    const note = noteInput.value.trim();

    if (note === '') {
        mentionElement.textContent = '—';
        return;
    }

    const valeur = Number(note);

    if (!Number.isInteger(valeur) || valeur < 0 || valeur > 20) {
        mentionElement.textContent = 'Note invalide';
        return;
    }

    if (valeur >= 18) {
        mentionElement.textContent = 'Excellent';
    } else if (valeur >= 16) {
        mentionElement.textContent = 'Très Bien';
    } else if (valeur >= 14) {
        mentionElement.textContent = 'Bien';
    } else if (valeur >= 12) {
        mentionElement.textContent = 'Assez Bien';
    } else if (valeur >= 10) {
        mentionElement.textContent = 'Passable';
    } else {
        mentionElement.textContent = 'Aucune mention';
    }
}
</script>

<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
