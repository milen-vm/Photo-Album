<div class="container">
    <h3>
        Public Images
    </h3>
    <hr />
    <div class="row">
        <?php foreach ($this->images_data as $key => $value) : ?>
        <div class="col-xs-6 col-md-3">
            <a href="<?=ROOT_URL . 'image/view/' . $key?>" title="View full size"
                    class="thumbnail album-list">
                <img src="<?=$value?>" alt="photo" />
            </a>
        </div>
        <?php endforeach ?>
    </div>
    <?php $this->pagination->includePangination(); ?>
</div>