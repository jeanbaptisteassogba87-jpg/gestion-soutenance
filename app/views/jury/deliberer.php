<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Délibération</h1>
    <a href="/app/controllers/JuryController.php?action=dashboard" class="btn btn-outline">← Retour</a>
</div>

<div class="table-wrap">
    <div class="table-header"><h2><i class="fa-solid fa-scale-balanced" aria-hidden="true"></i> Résultats</h2></div>
    <table>
        <thead>
            <tr><th>#</th><th>Date</th><th>Salle</th><th>Note</th><th>Mention</th><th>Décision</th></tr>
        </thead>
        <tbody>
        <?php if (empty($soutenances)): ?>
            <tr><td colspan="6" style="text-align:center;color:#aaa;padding:20px">Aucune soutenance</td></tr>
        <?php else: ?>
            <?php foreach ($soutenances as $s): ?>
            <tr>
                <td><?= $s['idSoutenance'] ?></td>
                <td><?= $s['date'] ?></td>
                <td><?= htmlspecialchars($s['salle']) ?></td>
                <td><?= $s['note'] ?? '—' ?></td>
                <td><?= $s['mention'] ?? '—' ?></td>
                <td>
                    <?php if (!is_null($s['note'])): ?>
                        <?php if ($s['note'] >= 10): ?>
                            <span class="badge badge-valide"><i class="fa-solid fa-circle-check" aria-hidden="true"></i> Admis</span>
                        <?php else: ?>
                            <span class="badge badge-rejete">❌ Ajourné</span>
                        <?php endif; ?>
                    <?php else: ?>
                        <span class="badge badge-en_attente">En attente</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
