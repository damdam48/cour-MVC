<section class="container mt2">
    <h1 class="text-center">Administration des articles</h1>
    <a href="/admin/articles/create" class="btn btn-primary my-2">Créer un article</a>
    <div class="row gy-4">
        <?php foreach ($articles as $article) : ?>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body">
                        <h2 class="card-title"><?= $article->getTitle(); ?></h2>
                        <p class="card-text"><?= $article->getContent(); ?></p>
                        <p class="card-text"></p>
                        <em class="card-text text-muted"><?= $article->getCreatedAt()->format('Y-m-d'); ?></em>

                        <div class="mt-2 d-flex justify-content-between align-items-center flex-wrap">
                            <a href="/admin/articles/<?= $article->getId(); ?>/edit" class="btn btn-warning">Modifier</a>
                            <form action="/admin/articles/delete" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ?')">
                                <input type="hidden" name="id" value="<?= $article->getId(); ?>">
                                <input type="hidden" name="token" value="<?= $_SESSION['token']; ?>">
                                <button type="submit" class="btn btn-danger">Supprimer</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</section>