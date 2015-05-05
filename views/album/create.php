<div class="container">
    <form action="/Photo-Album/album/create" method="post" class="form-horizontal">
        <fieldset>
            <!-- Form Name -->
            <legend>
                Create Album
            </legend>
            <!-- Album name input-->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textinput">Name</label>
                <div class="col-md-4">
                    <input id="textinput" name="name" placeholder="max length 50 characters"
                        class="form-control input-md" type="text">
                    <span class="help-block">Enter album name.</span>
                </div>
            </div>
            <!-- Description -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="textarea">Description</label>
                <div class="col-md-4">
                    <textarea class="form-control" id="textarea" name="description"
                        placeholder="Enter text..."></textarea>
                    <span class="help-block">Some things abount the album.</span>
                </div>
            </div>
            <!-- Is private album Checkboxes -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="checkboxes">Privacy</label>
                <div class="col-md-4">
                    <div class="checkbox">
                        <label for="checkboxes-0">
                            <input name="is_private" id="checkboxes-0" value="1" type="checkbox">
                            Private album </label>
                        <span class="help-block">Only you can see this album.</span>
                    </div>
                </div>
            </div>
            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label">Submit</label>
                <div class="col-md-4">
                    <input id="register" type="submit" value="Create" name="submit"
                    class="btn btn-primary">
                </div>
            </div>
        </fieldset>
    </form>
</div>
