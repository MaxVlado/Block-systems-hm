<h3 class="">Filter by </h3>
<div>
    <h4>Checked</h4>
    <select id="filter-checked" class="filter-field">
        <option value="">All</option>
        <option value="checked">Checked</option>
        <option value="unchecked">Unchecked</option>
    </select>
</div>
<div>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>

    <h4>Date</h4>
    <input type="text" id="filter-date" class="filter-field" readonly>
    <div>
        <button type="button" id="reset-date-filter">Reset date</button>
    </div>
</div>
<div>
    <h4>Tag</h4>
    <?php $tags = get_tags(); ?>
    <select id="filter-tag" class="filter-field">
        <option value="">All</option>
        <?php
        foreach ($tags as $tag) { ?>
            <option value="<?php echo $tag->slug ?>"><?php echo  $tag->name ?></option>
        <?php } ?>
    </select>
</div>