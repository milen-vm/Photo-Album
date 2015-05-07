<script>
    // Load this when the DOM is ready
    $(function() {
        // You used .myCarousel here.
        // That's the class selector not the id selector,
        // which is #myCarousel
        $('#myCarousel').carousel();
    }); 
</script>
<div class="container">
    <div class="row">
        <fieldset>
            <legend>
                Album Name
            </legend>
            <?php foreach ($this->images_paths as $path) : ?>
                <div class="col-lg-3 col-sm-4 col-xs-6">
                    <a title="Photo" href="#">
                        <img class="thumbnail img-responsive" alt="photo" src="<?= htmlentities($path) ?>">
                    </a>
                </div>
            <?php endforeach ?>
        </fieldset>
    </div>
    <!-- Upload photo form -->
    <form action="/Photo-Album/image/upload" method="post"
        enctype="multipart/form-data" class="form-horizontal">
        <fieldset>
            <!-- Form Name -->
            <legend>
                Add photo
            </legend>
            <!-- File Button -->
            <div class="form-group">
                <label class="col-md-4 control-label" for="filebutton">File Button</label>
                <div class="col-md-4">
                    <input id="filebutton" name="photo" class="input-file" type="file" />
                </div>
            </div>
            <!-- Button -->
            <div class="form-group">
                <label class="col-md-4 control-label">Single Button</label>
                <div class="col-md-4">
                    <input type="submit" name="submit" value="Upload" class="btn btn-primary" />
                </div>
            </div>
        </fieldset>
</form>
</div>

<!-- Carousel modal -->
<div class="modal" id="myModal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" type="button" data-dismiss="modal">
                    ×
                </button>
                <h3 class="modal-title"></h3>
            </div>
            <div class="modal-body">
                <div id="modalCarousel" class="carousel">
                    <div class="carousel-inner"></div>
                    <a class="carousel-control left" href="#modaCarousel" data-slide="prev"><i class="glyphicon glyphicon-chevron-left"></i></a>
                    <a class="carousel-control right" href="#modalCarousel" data-slide="next"><i class="glyphicon glyphicon-chevron-right"></i></a>
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-default" data-dismiss="modal">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>


<script>
    /* copy loaded thumbnails into carousel */
    $('.row .thumbnail').on('load', function() {

    }).each(function(i) {
        if (this.complete) {
            var item = $('<div class="item"></div>');
            var itemDiv = $(this).parents('div');
            var title = $(this).parent('a').attr("title");

            item.attr("title", title);
            $(itemDiv.html()).appendTo(item);
            item.appendTo('.carousel-inner');
            if (i == 0) {// set first item active
                item.addClass('active');
            }
        }
    });

    /* activate the carousel */
    $('#modalCarousel').carousel({
        interval : false
    });

    /* change modal title when slide changes */
    $('#modalCarousel').on('slid.bs.carousel', function() {
        $('.modal-title').html($(this).find('.active').attr("title"));
    })
    /* when clicking a thumbnail */
    $('.row .thumbnail').click(function() {
        var idx = $(this).parents('div').index();
        var id = parseInt(idx);
        $('#myModal').modal('show');
        // show the modal
        $('#modalCarousel').carousel(id);
        // slide carousel to selected

    });
</script>


