<?php foreach ($newses as $news) :
?>

    <div class="col">

        <div class="card shadow-sm p-3 mb-5 bg-white rounded">
            <img src="/img/<?= $news['gambar'] ?>" class="card-img-top" alt="gambar" height="50%">
            <div class="card-body">
                <h5 class="card-title "><a href="news/<?= $news['slug'] ?>" class="text-decoration-none"><?= $news['title']  ?></a></h5>
                <a href="news/<?= $news['slug'] ?>" class="btn btn-primary mb-2">Read more</a>
            </div>
        </div>

    </div>

<?php endforeach;

?>