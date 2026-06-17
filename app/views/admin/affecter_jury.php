<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Affecter un nouveau jury</h1>
    <a href="/app/controllers/AdminController.php?action=dashboard" class="btn btn-outline">← Retour</a>
</div>

<form action="/app/controllers/AdminController.php?action=affecter_jury" method="POST" class="form-card">

    <h3>🔄 Changer le jury d'une soutenance</h3>

    <div class="form-group">
        <label>Soutenance</label>
        <select name="idSoutenance" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($soutenances as $s): ?>
            <option value="<?= $s['idSoutenance'] ?>">Soutenance #<?= $s['idSoutenance'] ?> — <?= $s['date'] ?> à <?= $s['heure'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="form-group">
        <label>Nouveau jury</label>
        <select name="idJury" required>
            <option value="">-- Choisir --</option>
            <?php foreach ($jurys as $j): ?>
            <option value="<?= $j['idJury'] ?>">Jury #<?= $j['idJury'] ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <button type="submit" class="btn">✅ Affecter</button>
</form>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>