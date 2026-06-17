<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Gestion des utilisateurs</h1>
    <a href="/app/controllers/AdminController.php?action=creer_utilisateur" class="btn">+ Ajouter un utilisateur</a>
</div>
<div style="display:flex;gap:6px;margin-bottom:20px;border-bottom:2px solid var(--border);padding-bottom:0;">
    <?php
    $type = $_GET['type'] ?? 'etudiant';
    $tabs = ['etudiant' => '🎓 Etudiants', 'encadreur' => '👨‍🏫 Encadreurs', 'membre_jury' => '⚖️ Membres jury'];
    foreach ($tabs as $key => $label):
        $active = $type === $key ? 'style="background:var(--primary);color:#fff;border-color:var(--primary)"' : '';
    ?>
    <a href="/app/controllers/AdminController.php?action=utilisateurs&type=<?= $key ?>"
       class="btn btn-sm btn-outline" <?= $active ?>><?= $label ?></a>
    <?php endforeach; ?>
</div>

<?php if ($type === 'etudiant'): ?>
<!-- ── ETUDIANTS ── -->
<div class="table-wrap">
    <div class="table-header"><h2>🎓 Etudiants (<?= count($etudiants) ?>)</h2></div>
    <div class="table-scroll">
    <table>
        <thead>
            <tr><th>Nom</th><th>Prénom</th><th>Login</th><th>Filière</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($etudiants as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><code><?= htmlspecialchars($u['login']) ?></code></td>
            <td><?= htmlspecialchars($u['filiere']) ?></td>
            <td>
                <a href="/app/controllers/AdminController.php?action=modifier_utilisateur&id=<?= $u['idUtilisateur'] ?>" class="btn btn-sm btn-outline">✏️ Modifier</a>
                <a href="/app/controllers/AdminController.php?action=supprimer_utilisateur&id=<?= $u['idUtilisateur'] ?>&role=etudiant"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Supprimer cet étudiant ? Cette action est irréversible.')">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<?php elseif ($type === 'encadreur'): ?>
<div class="table-wrap">
    <div class="table-header"><h2>👨‍🏫 Encadreurs (<?= count($encadreurs) ?>)</h2></div>
    <div class="table-scroll">
    <table>
        <thead>
            <tr><th>Nom</th><th>Prénom</th><th>Login</th><th>Spécialité</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($encadreurs as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><code><?= htmlspecialchars($u['login']) ?></code></td>
            <td><?= htmlspecialchars($u['specialite']) ?></td>
            <td>
                <a href="/app/controllers/AdminController.php?action=modifier_utilisateur&id=<?= $u['idUtilisateur'] ?>" class="btn btn-sm btn-outline">✏️ Modifier</a>
                <a href="/app/controllers/AdminController.php?action=supprimer_utilisateur&id=<?= $u['idUtilisateur'] ?>&role=encadreur"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Supprimer cet encadreur ?')">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>

<?php elseif ($type === 'membre_jury'): ?>
<div class="table-wrap">
    <div class="table-header"><h2>⚖️ Membres jury (<?= count($jurys_m) ?>)</h2></div>
    <div class="table-scroll">
    <table>
        <thead>
            <tr><th>Nom</th><th>Prénom</th><th>Login</th><th>Actions</th></tr>
        </thead>
        <tbody>
        <?php foreach ($jurys_m as $u): ?>
        <tr>
            <td><?= htmlspecialchars($u['nom']) ?></td>
            <td><?= htmlspecialchars($u['prenom']) ?></td>
            <td><code><?= htmlspecialchars($u['login']) ?></code></td>
            <td>
                <a href="/app/controllers/AdminController.php?action=modifier_utilisateur&id=<?= $u['idUtilisateur'] ?>" class="btn btn-sm btn-outline">✏️ Modifier</a>
                <a href="/app/controllers/AdminController.php?action=supprimer_utilisateur&id=<?= $u['idUtilisateur'] ?>&role=membre_jury"
                   class="btn btn-sm btn-danger"
                   onclick="return confirm('Supprimer ce membre jury ?')">🗑️</a>
            </td>
        </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
    </div>
</div>
<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>