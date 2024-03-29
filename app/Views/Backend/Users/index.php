<section class="container mt-2">
    <h1 class="text-center">Administration des utilisateurs</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead>
                <tr>
                    <th scope="col">Id</th>
                    <th scope="col">Nom complet</th>
                    <th scope="col">Email</th>
                    <th scope="col">roles</th>
                    <th scope="col">Actions</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php foreach ($users as $user) : ?>
                    <tr>
                        <td scope="row"><?= $user->getId(); ?></td>
                        <td><?= $user->getFullName(); ?></td>
                        <td><?= $user->getEmail(); ?></td>
                        <td><?= implode(', ', $user->getRoles()); ?></td>
                        <td>
                            <div class="d-flex justify-content-center gap-3 flex-wrap align-items-center">
                                <a href="/admin/users/<?= $user->getId(); ?>/edit" class="btn btn-warning">Modifier</a>
                                <form action="/admin/users/delete" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce user ?')">
                                    <input type="hidden" name="id" value="<?= $user->getId(); ?>">
                                    <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                                    <button class="btn btn-danger">Supprimer</button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</section>