<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Planning des soutenances</h1>
</div>

<div class="table-wrap">
    <div class="table-header"><h2>Toutes les soutenances</h2></div>
    <table>
        <thead>
            <tr><th>#</th><th>Date</th><th>Heure</th><th>Salle</th><th>Note</th><th>Mention</th></tr>
        </thead>
        <tbody>
        <?php if (empty($soutenances)): ?>
            <tr><td colspan="6" style="text-align:center;color:#aaa;padding:20px">Aucune soutenance planifiée</td></tr>
        <?php else: ?>
            <?php foreach ($soutenances as $s): ?>
            <tr>
                <td><?= $s['idSoutenance'] ?></td>
                <td><?= $s['date'] ?></td>
                <td><?= $s['heure'] ?></td>
                <td><?= htmlspecialchars($s['salle']) ?></td>
                <td><?= $s['note'] ?? '—' ?></td>
                <td><?= $s['mention'] ?? '—' ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>