
<?php require_once __DIR__ . '/../layouts/header.php'; ?>

<div class="topbar">
    <h1>Gestion des utilisateurs</h1>
    <a href="/app/controllers/AdminController.php?action=creer_utilisateur" class="btn">+ Ajouter un utilisateur</a>
</div>

<div style="display:flex;gap:6px;margin-bottom:20px;border-bottom:2px solid var(--border);padding-bottom:0;">
    <?php
    $type = $_GET['type'] ?? 'etudiant';
    $tabs = [
        'etudiant' => '🎓 Etudiants',
        'encadreur' => '👨‍🏫 Encadreurs',
        'membre_jury' => '⚖️ Membres jury'
    ];

    foreach ($tabs as $key => $label):
        $active = $type === $key
            ? 'style="background:var(--primary);color:#fff;border-color:var(--primary)"'
            : '';
    ?>
        <a href="/app/controllers/AdminController.php?action=utilisateurs&type=<?= htmlspecialchars($key, ENT_QUOTES, 'UTF-8') ?>"
           class="btn btn-sm btn-outline" <?= $active ?>>
            <?= $label ?>
        </a>
    <?php endforeach; ?>
</div>

<?php if ($type === 'etudiant'): ?>

<div class="table-wrap">
    <div class="table-header">
        <h2>🎓 Etudiants (<?= count($etudiants) ?>)</h2>
    </div>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Login</th>
                    <th>Filière</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($etudiants as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><code><?= htmlspecialchars($u['login'], ENT_QUOTES, 'UTF-8') ?></code></td>
                    <td><?= htmlspecialchars($u['filiere'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="/app/controllers/AdminController.php?action=modifier_utilisateur&id=<?= (int)$u['idUtilisateur'] ?>"
                           class="btn btn-sm btn-outline">✏️ Modifier</a>

                        <form action="/app/controllers/AdminController.php?action=supprimer_utilisateur"
                              method="POST"
                              style="display:inline"
                              onsubmit="return confirm('Supprimer cet étudiant ? Cette action est irréversible.')">

                            <input type="hidden" name="csrf_token"
                                   value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= (int)$u['idUtilisateur'] ?>">
                            <input type="hidden" name="role" value="etudiant">

                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php elseif ($type === 'encadreur'): ?>

<div class="table-wrap">
    <div class="table-header">
        <h2>👨‍🏫 Encadreurs (<?= count($encadreurs) ?>)</h2>
    </div>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Login</th>
                    <th>Spécialité</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($encadreurs as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><code><?= htmlspecialchars($u['login'], ENT_QUOTES, 'UTF-8') ?></code></td>
                    <td><?= htmlspecialchars($u['specialite'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td>
                        <a href="/app/controllers/AdminController.php?action=modifier_utilisateur&id=<?= (int)$u['idUtilisateur'] ?>"
                           class="btn btn-sm btn-outline">✏️ Modifier</a>

                        <form action="/app/controllers/AdminController.php?action=supprimer_utilisateur"
                              method="POST"
                              style="display:inline"
                              onsubmit="return confirm('Supprimer cet encadreur ?')">

                            <input type="hidden" name="csrf_token"
                                   value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= (int)$u['idUtilisateur'] ?>">
                            <input type="hidden" name="role" value="encadreur">

                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php elseif ($type === 'membre_jury'): ?>

<div class="table-wrap">
    <div class="table-header">
        <h2>⚖️ Membres jury (<?= count($jurys_m) ?>)</h2>
    </div>

    <div class="table-scroll">
        <table>
            <thead>
                <tr>
                    <th>Nom</th>
                    <th>Prénom</th>
                    <th>Login</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($jurys_m as $u): ?>
                <tr>
                    <td><?= htmlspecialchars($u['nom'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><?= htmlspecialchars($u['prenom'], ENT_QUOTES, 'UTF-8') ?></td>
                    <td><code><?= htmlspecialchars($u['login'], ENT_QUOTES, 'UTF-8') ?></code></td>
                    <td>
                        <a href="/app/controllers/AdminController.php?action=modifier_utilisateur&id=<?= (int)$u['idUtilisateur'] ?>"
                           class="btn btn-sm btn-outline">✏️ Modifier</a>

                        <form action="/app/controllers/AdminController.php?action=supprimer_utilisateur"
                              method="POST"
                              style="display:inline"
                              onsubmit="return confirm('Supprimer ce membre jury ?')">

                            <input type="hidden" name="csrf_token"
                                   value="<?= htmlspecialchars(csrf_token(), ENT_QUOTES, 'UTF-8') ?>">
                            <input type="hidden" name="id" value="<?= (int)$u['idUtilisateur'] ?>">
                            <input type="hidden" name="role" value="membre_jury">

                            <button type="submit" class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php endif; ?>

<?php require_once __DIR__ . '/../layouts/footer.php'; ?>
