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
    <?php $this->pagination->includePangination(); ?>
</div>