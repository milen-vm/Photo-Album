<div class="container">
    <legend>
        My albums
    </legend>
    <div class="row">
        <?php if (isset($this->albums)) : ?>
            <?php foreach ($this->albums as $album) : ?>
                <div class="col-sm-6 col-md-3">
                    <div class="thumbnail album-title">
                        <a href="<?php echo ROOT_URL . 'image/browse/' . htmlspecialchars($album['id']);?>"
                            title="Browse">
                            <img src="<?=ROOT_URL?>content/images/photo-album.gif" alt="Title photo">
                        </a>
                        <div class="caption">
                            <h3><?= htmlspecialchars($album['name']) ?></h3>
                            <form action="<?php echo ROOT_URL . 'album/delete/' . htmlspecialchars($album['id']);?>"
                                method="post">
                                <a href="#confirm" class="btn btn-danger delete-album" data-toggle="modal">
                                    Delete
                                </a>
                                <input type="hidden" name="form_token" value="<?=$_SESSION['form_token']?>" />
                            </form>
                        </div>
                    </div>
                </div>
            <?php endforeach ?>
        <?php endif ?>
    </div>
    <?php $this->pagination->includePangination(); ?>
    <!-- <ul class="pager">
        <li>
            <a href="<?php echo ROOT_URL . 'album/index/' . htmlspecialchars($this->page - 1) .
                '/' . htmlspecialchars($this->page_size);?>">Prev</a>             
        </li>
        <li>
            <a href="<?php echo ROOT_URL . 'album/index/' . htmlspecialchars($this->page + 1) .
                '/' . htmlspecialchars($this->page_size);?>">Next</a>               
        </li>
    </ul> -->
</div>
<!-- Modal confirm -->
<div id="confirm" class="modal fade">
    <div class="modal-dialog modal-width">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">Confirmation</h4>
            </div>
            <div class="modal-body">
                <p>Do you want to delete this album?</p>
                <p>This will remove all images in there.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cansel</button>
                <button id="confirm-delete" type="button" class="btn btn-danger"
                    data-dismiss="modal">Delete</button>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function(ev){
    $('.delete-album').on('click', function(e) {
        var $form = $(this).closest('form');
        console.log($form);
        $('#confirm-delete').on('click', function() {
            $form.trigger('submit');
        });
    });
});
</script>