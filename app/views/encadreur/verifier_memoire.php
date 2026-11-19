<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Mémoires à vérifier</h1>
</div>

<div class="table-wrap">
    <div class="table-header"><h2>Liste des mémoires</h2></div>
    <table>
        <thead>
            <tr>
                <th>#</th>
                <th>Etudiant 1</th>
                <th>Etudiant 2</th>
                <th>Titre</th>
                <th>Date dépôt</th>
                <th>Fichier</th>
                <th>Statut</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($memoires)): ?>
            <tr><td colspan="8" style="text-align:center;color:#aaa;padding:20px">Aucun mémoire</td></tr>
        <?php else: ?>
            <?php foreach ($memoires as $m): ?>
            <tr>
                <td><?= $m['idMemoire'] ?></td>
                <td><?= htmlspecialchars($m['nom_etudiant1'] . ' ' . $m['prenom_etudiant1']) ?></td>
                <td><?= isset($m['nom_etudiant2']) ? htmlspecialchars($m['nom_etudiant2'] . ' ' . $m['prenom_etudiant2']) : '—' ?></td>
                <td><?= htmlspecialchars($m['titre']) ?></td>
                <td><?= $m['dateDepot'] ?></td>
                <td>
                    <?php if ($m['fichier']): ?>
                        <a href="/app/controllers/TelechargerMemoireController.php?id=<?= (int) $m['idMemoire'] ?>" target="_blank" class="btn btn-sm btn-outline">📄 Voir PDF</a>  
                    <?php else: ?>
                        <span style="color:#aaa">—</span>
                    <?php endif; ?>
                </td>
                <td><span class="badge badge-<?= $m['statut'] ?>"><?= ucfirst(str_replace('_', ' ', $m['statut'])) ?></span></td>
                <td>
                    <?php if ($m['statut'] === 'en_attente'): ?>
                       <form action="/app/controllers/EncadreurController.php?action=valider"
      method="POST" style="display:inline;">
    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="idMemoire" value="<?= (int)$m['idMemoire'] ?>">
    <button type="submit" class="btn btn-success btn-sm">Valider</button>
</form>

<form action="/app/controllers/EncadreurController.php?action=rejeter"
      method="POST" style="display:inline;">
    <input type="hidden" name="csrf_token"
           value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
    <input type="hidden" name="idMemoire" value="<?= (int)$m['idMemoire'] ?>">
    <button type="submit" class="btn btn-danger btn-sm">Rejeter</button>
</form>
                        <span style="color:#aaa;font-size:13px">Traité</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>