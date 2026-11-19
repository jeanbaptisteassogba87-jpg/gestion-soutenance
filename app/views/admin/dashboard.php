
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Tableau de bord — Administrateur</h1>
    <span>Bonjour, <strong><?= htmlspecialchars($_SESSION['nom'] . ' ' . $_SESSION['prenom'], ENT_QUOTES, 'UTF-8') ?></strong></span>
</div>

<div class="cards">
    <div class="card">
        <h3><?= count($soutenances) ?></h3>
        <p>Soutenances planifiées</p>
    </div>
    <div class="card">
        <h3><?= count($memoires) ?></h3>
        <p>Mémoires en attente</p>
    </div>
</div>

<div class="actions-bar">
    <a href="/app/controllers/AdminController.php?action=planifier" class="btn"><i class="fa-solid fa-calendar-plus" aria-hidden="true"></i> Planifier une soutenance</a>
    <a href="/app/controllers/AdminController.php?action=affecter_jury" class="btn btn-outline"><i class="fa-solid fa-users-gear" aria-hidden="true"></i> Changer un jury</a>
</div>

<?php if (!empty($_SESSION['succes'])): ?>
    <div class="alert alert-success">
        <?= htmlspecialchars($_SESSION['succes'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['succes']); ?>
    </div>
<?php endif; ?>

<?php if (!empty($_SESSION['erreur'])): ?>
    <div class="alert alert-danger">
        <?= htmlspecialchars($_SESSION['erreur'], ENT_QUOTES, 'UTF-8'); unset($_SESSION['erreur']); ?>
    </div>
<?php endif; ?>

<div class="table-wrap">
    <div class="table-header"><h2>Soutenances planifiées</h2></div>
    <table>
        <thead>
            <tr>
                <th>#</th><th>Date</th><th>Heure</th>
                <th>Salle</th><th>Note</th><th>Mention</th>
            </tr>
        </thead>
        <tbody>
        <?php if (empty($soutenances)): ?>
            <tr>
                <td colspan="6" style="text-align:center;color:#aaa;padding:20px">
                    Aucune soutenance planifiée
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($soutenances as $s): ?>
            <tr>
                <td><?= (int)$s['idSoutenance'] ?></td>
                <td><?= htmlspecialchars($s['date'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($s['heure'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($s['salle'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= $s['note'] !== null ? htmlspecialchars((string)$s['note'], ENT_QUOTES, 'UTF-8') : '—' ?></td>
                <td><?= htmlspecialchars($s['mention'] ?? '—', ENT_QUOTES, 'UTF-8') ?></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<div class="danger-zone">
    <div class="table-header">
        <h2 style="color:#b42318;">Zone de danger — remise à zéro</h2>
    </div>

    <p>
        Supprime définitivement les utilisateurs, mémoires, jurys et soutenances,
        puis recrée uniquement le compte <code>admin</code>.
    </p>

    <form action="/app/controllers/AdminController.php?action=reinitialiser"
          method="POST">

        <input type="hidden" name="csrf_token"
               value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">

        <label>Pour confirmer, écrivez <strong>REINITIALISER</strong>
            <input type="text" name="confirmation" required autocomplete="off">
        </label>

        <label>Nouveau mot de passe du compte admin
            <input type="password" name="motDePasse"
                   required minlength="8" autocomplete="new-password">
        </label>

        <button type="submit" class="btn btn-danger"
                onclick="return confirm('Toutes les données seront supprimées définitivement. Continuer ?')">
            Réinitialiser toutes les données
        </button>
    </form>
</div>

<div class="table-wrap">
    <div class="table-header"><h2>Mémoires en attente de validation</h2></div>
    <table>
        <thead>
            <tr><th>#</th><th>Titre</th><th>Date dépôt</th><th>Statut</th></tr>
        </thead>
        <tbody>
        <?php if (empty($memoires)): ?>
            <tr>
                <td colspan="4" style="text-align:center;color:#aaa;padding:20px">
                    Aucun mémoire en attente
                </td>
            </tr>
        <?php else: ?>
            <?php foreach ($memoires as $m): ?>
            <tr>
                <td><?= (int)$m['idMemoire'] ?></td>
                <td><?= htmlspecialchars($m['titre'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><?= htmlspecialchars($m['dateDepot'], ENT_QUOTES, 'UTF-8') ?></td>
                <td><span class="badge badge-en_attente">En attente</span></td>
            </tr>
            <?php endforeach; ?>
        <?php endif; ?>
        </tbody>
    </table>
</div>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
