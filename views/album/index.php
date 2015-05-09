<div class="container">
    <legend>
        My albums
    </legend>
    <div class="row">
        <?php if (isset($this->albums)) : ?>
            <?php foreach ($this->albums as $album) : ?>
                <div class="col-sm-6 col-md-3">
                    <div class="thumbnail album-title">
                        <a href="/Photo-Album/image/browse/<?= htmlspecialchars($album['id']) ?>" title="Browse">
                            <img src="/Photo-Album/content/images/photo-album.gif" alt="Title photo">
                        </a>
                        <div class="caption">
                            <h3><?= htmlspecialchars($album['name']) ?></h3>
                            <p>
                                <form action="" method="post">
                                    <input type="hidden" name="album_id" value="<?= htmlspecialchars($album['id']) ?>" />
                                    <input type="submit" value="Delete" class="btn btn-danger" />
                                </form>
                                
                                <a href="#" class="btn btn-default">Download</a>
                            </p>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <ul class="pager">
        <li>
            <a href="/Photo-Album/album/index/<?=htmlspecialchars($this->page - 1)?>/<?=htmlspecialchars($this->page_size)?>">Previous</a>             
        </li>
        <li>
            <a href="/Photo-Album/album/index/<?=htmlspecialchars($this->page + 1)?>/<?=htmlspecialchars($this->page_size)?>">Next</a>               
        </li>
    </ul>
</div>
<script>
    $('#logout').on('click', function(ev) {
        $.ajax({
            method: 'POST',
            url: '/Photo-Album/account/logout',
            success: function(data) {
                location.reload(); 
            },
            error: function() {
                console.log('Cannot load AJAX data.');
            }
        })
    });
</script>