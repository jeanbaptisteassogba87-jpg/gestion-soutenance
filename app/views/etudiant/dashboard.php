<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Tableau de bord</h1>
    <span class="topbar-user">Bonjour, <strong><?= htmlspecialchars($_SESSION['nom'] . ' ' . $_SESSION['prenom']) ?></strong></span>
</div>

<div class="cards">
    <div class="card">
        <h3><?= count($mesMemoires) ?></h3>
        <p>Mes mémoires</p>
    </div>
    <div class="card">
        <h3><?= count($soutenances) ?></h3>
        <p>Soutenances planifiées</p>
    </div>
</div>

<div class="actions-bar">
    <?php if (empty($mesMemoires)): ?>
        <a href="/app/controllers/EtudiantController.php?action=deposer" class="btn">+ Déposer un mémoire</a>
    <?php endif; ?>
    <a href="/app/controllers/EtudiantController.php?action=planning" class="btn btn-outline"><i class="fa-regular fa-calendar" aria-hidden="true"></i> Planning</a>
</div>

<div class="table-wrap">
    <div class="table-header"><h2>Mon mémoire</h2></div>
    <div class="table-scroll">
    <table>
        <thead>
            <tr>
                <th>Titre</th>
                <th>Binôme</th>
                <th>Date dépôt</th>
                <th>Statut</th>
                <th>Note</th>
                <th>Mention</th>
                <th>Fichier</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($mesMemoires)): ?>
            <tr><td colspan="7" style="text-align:center;color:#aaa;padding:20px">Aucun mémoire déposé pour l'instant.</td></tr>
        <?php else: ?>
            <?php foreach ($mesMemoires as $m):
                $note = '—'; $mention = '—';
                foreach ($soutenances as $s) {
                    if ($s['idMemoire'] == $m['idMemoire']) {
                        $note    = $s['note']    ?? '—';
                        $mention = $s['mention'] ?? '—';
                        break;
                    }
                }
                $idConnecte = $_SESSION['idUtilisateur'];
                $binome = ($m['idEtudiant'] == $idConnecte)
                    ? (isset($m['nom_etudiant2']) ? htmlspecialchars($m['nom_etudiant2'] . ' ' . $m['prenom_etudiant2']) : '—')
                    : htmlspecialchars($m['nom_etudiant1'] . ' ' . $m['prenom_etudiant1']);
            ?>
            <tr>
                <td><?= htmlspecialchars($m['titre']) ?></td>
                <td><?= $binome ?></td>
                <td><?= $m['dateDepot'] ?></td>
                <td><span class="badge badge-<?= $m['statut'] ?>"><?= ucfirst(str_replace('_', ' ', $m['statut'])) ?></span></td>
                <td><?= $note ?></td>
                <td><?= $mention ?></td>
                <td>
                    <?php if ($m['fichier']): ?>
                        <a href="/app/controllers/TelechargerMemoireController.php?id=<?= (int) $m['idMemoire'] ?>" target="_blank" rel="noopener noreferrer" class="btn btn-sm btn-outline"><i class="fa-regular fa-file-pdf" aria-hidden="true"></i> Voir</a>
                    <?php else: ?>
                        <span style="color:#aaa">—</span>
                    <?php endif; ?>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
