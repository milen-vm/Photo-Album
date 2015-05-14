<div class="container">
    <legend>
        <?=htmlspecialchars($this->album_data['name'])?>
    </legend>
    <p><?=htmlspecialchars($this->album_data['description'])?></p>
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
    <!-- Upload photo form -->
    <form action="<?=ROOT_URL?>image/upload" method="post"
    enctype="multipart/form-data" class="form-horizontal">
        <!-- Form Name -->
        <legend>
            Add photo
        </legend>
        <!-- File Button -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="filebutton">Select image</label>
            <div class="col-md-4">
                <input id="filebutton" name="photo" class="input-file" type="file" />
            </div>
        </div>
        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label">Submit</label>
            <div class="col-md-4">
                <input type="submit" name="submit" value="Upload" class="btn btn-primary" />
            </div>
        </div>
    </form>
</div>