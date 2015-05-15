<ul class="pager">
    <?php if ($this->curr_page == 1) :?>
    <li class="disabled">
        <a>First</a>
    </li>
    <li class="disabled">
        <a>Prev</a>
    </li>
    <?php else : ?>
    <li>
        <a href="<?=$this->first_page_url?>">First</a>
    </li>
    <li>
        <a href="<?=$this->prev_page_url?>">Prev</a>
    </li>
    <?php endif ?>
    <li>
        Page <?=$this->curr_page?> of <?=$this->last_page?>
    </li>
    <?php if ($this->curr_page == $this->last_page) :?>
    <li class="disabled">
        <a>Next</a>
    </li>
    <li class="disabled">
        <a>Last</a>
    </li>
    <?php else : ?>
    <li>
        <a href="<?=$this->next_page_url?>" >Next</a>
    </li>
    <li>
        <a href="<?=$this->last_page_url?>">Last</a>
    </li>
    <?php endif ?>
</ul>