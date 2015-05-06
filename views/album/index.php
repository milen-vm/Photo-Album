<div class="container">
    <div class="row">
        <?php foreach ($this->albums as $album) : ?>
            <div class="col-sm-6 col-md-4">
                <div class="thumbnail">
                    <img class="album-title" src="/Photo-Album/content/images/empty-album.png" alt="Title photo">
                    <div class="caption">
                        <h3><?= htmlspecialchars($album['name']) ?></h3>
                        <p><?= htmlspecialchars($album['description']) ?></p>
                        <p>
                            <a href="#" class="btn btn-primary">Share</a><a href="#" class="btn btn-default">Download</a>
                        </p>
                    </div>
                </div>
            </div>
        <?php endforeach ?>
    </div>
</div>