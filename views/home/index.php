<div class="container">
    <legend>
        Public Images
    </legend>
    <div class="row">
        <?php foreach ($this->images_data as $data) : ?>
        <div class="col-xs-6 col-md-3">
            <a href="<?=htmlentities($data)?>" title="Photo" class="thumbnail album-list">
                <img src="<?=htmlentities($data)?>" alt="photo" />
            </a>
        </div>
        <?php endforeach ?>
    </div>
    <ul class="pager">
        <li>
            <a href="<?php echo ROOT_URL . 'album/index/' . htmlspecialchars($this->page - 1) .
                '/' . htmlspecialchars($this->page_size);?>">Prev</a>             
        </li>
        <li>
            <a href="<?php echo ROOT_URL . 'album/index/' . htmlspecialchars($this->page + 1) .
                '/' . htmlspecialchars($this->page_size);?>">Next</a>               
        </li>
    </ul>
</div>