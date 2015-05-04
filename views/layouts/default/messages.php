<?php if (isset($_SESSION['messages'])) : ?>
    <div class="container">
    <?php
    foreach ($_SESSION['messages'] as $msg) : ?>
        <div class="alert alert-<?=htmlspecialchars($msg['type'])?> alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <strong><?=htmlspecialchars(ucfirst($msg['type']))?>!</strong> <?= htmlspecialchars($msg['text']) ?>
        </div>
    <?php endforeach ?>
    </div>
<?php
unset($_SESSION['messages']);
endif ?>