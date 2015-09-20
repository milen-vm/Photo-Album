<div class="container">
    <form action="<?php echo ROOT_URL . 'album/update/' . $this->album['id']; ?>"
        method="post" class="form-horizontal">
        <!-- Form Name -->
        <h3>
            Edit Album
        </h3>
        <hr />
        <!-- Album name input-->
        <div class="form-group">
            <label class="col-md-4 control-label" for="textinput">Name</label>
            <div class="col-md-4">
                <input id="textinput" name="name" placeholder="max length 50 characters"
                    class="form-control input-md" type="text"
                    value="<?php echo htmlspecialchars($this->album['name']); ?>" />
                <span class="help-block">Enter album name.</span>
            </div>
        </div>
        <!-- Description -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="textarea">Description</label>
            <div class="col-md-4">
                <textarea class="form-control" id="textarea" name="description" placeholder="Enter text..."
                ><?php echo htmlspecialchars($this->album['description']); ?></textarea>
                <span class="help-block">Some things abount the album.</span>
            </div>
        </div>
        <!-- Is private album Checkboxes -->
        <div class="form-group">
            <label class="col-md-4 control-label" for="checkboxes">Privacy</label>
            <div class="col-md-4">
                <div class="checkbox">
                    <label for="checkboxes-0">
                        <input name="is_private" id="checkboxes-0" value="1" type="checkbox"
                            <?php echo $this->album['is_private'] == 1 ? 'checked' : ''; ?> />
                        Private album </label>
                    <span class="help-block">Only you can see this album.</span>
                </div>
            </div>
        </div>
        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 control-label">Submit</label>
            <div class="col-md-4">
                <input type="hidden" name="form_token" value="<?=$_SESSION['form_token']?>" />
                <input id="register" type="submit" value="Update" name="submit"
                class="btn btn-primary" />
            </div>
        </div>
    </form>
</div>
