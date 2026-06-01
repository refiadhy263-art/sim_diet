<div class="card my-4 float-right shadow-sm p-3 mb-5 bg-white rounded">
    <div class="card-body">
        <h5 class="h5">Recent Posts</h5>
        <hr>
        <div class="list-group list-group-flush">
            <?php foreach ($newses as $news) : ?>
                <div class="media mb-3">
                    <div class="media-body">
                        <h6 class="card-title "><a href="news/<?= $news['slug'] ?>" class="text-decoration-none"><?= $news['title']  ?></a></h6>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</div>