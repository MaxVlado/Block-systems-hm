<h3>Place</h3>
<div class="row-place-option">
    <label for="place-manual">
        <span>Manual</span>
        <input type="radio" id="place-manual" name="place" value="manual"
            <?php
                if(isset($block)) {
                    checked($block->place, 'manual');
                }else{
                    echo 'checked';
                }
            ?>>
    </label>
    <label for="place-all-page">
        <span>All page</span>
        <input type="radio" id="place-all-page" name="place" value="all-page" <?php if(isset($block)) checked($block->place, 'all-page'); ?>>
    </label>
    <label for="place-custom">
        <span>Custom </span>
        <input type="radio" id="place-custom" name="place" value="custom" <?php if(isset($block)) checked($block->place, 'custom'); ?>>
    </label>
</div>