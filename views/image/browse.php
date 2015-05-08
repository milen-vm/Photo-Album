<div class="container">
    <legend>
        Album Name
    </legend>
    <div class="row">
        <?php foreach ($this->images_paths as $path) : ?>
        <div class="col-lg-3 col-sm-4 col-xs-6">
            <a title="Photo" href="<?=htmlentities($path)?>"> <img class="thumbnail img-responsive" alt="photo" src="<?=htmlentities($path)?>"> </a>
        </div>
        <?php endforeach ?>
    </div>
    <!-- Upload photo form -->
    <form action="/Photo-Album/image/upload" method="post"
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