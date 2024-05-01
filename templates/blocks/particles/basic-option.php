<h3>Basic fields</h3>
<div class="row-title-data flex">
    <div class="title"><?php esc_html_e('Block Type', 'block-systems-hm'); ?> <span class="flag-require">*</span></div>
    <td>
        <select name="block_type_id" id="block_type_id" required >>
            <option value=""><?php esc_html_e('Select Block Type', 'block-systems-hm'); ?></option>
            <?php
            $block_types = new Block_Systems_HM_Block_Types_Controller();
            $all_block_types = $block_types->get_all_block_types();
            $single_block_types = $block_types->get_block_types($block->block_type_id);
            foreach ($all_block_types as $block_type) {
                $selected = (isset($block) && ($block_type->id == $block->block_type_id)) ? 'selected' : '';
                echo '<option value="' . esc_attr($block_type->id) . '" ' . $selected . '>' . esc_html($block_type->name) . '</option>';
            }
            ?>
        </select>

    </td>
</div>

<div class="row-title-data">
    <div class="title">
        <?php esc_html_e('Shortcode', 'block-systems-hm'); ?><span class="flag-require">*</span>
    </div>
    <div class="data">
        <input type="text" style="width: auto" name="shortcode" id="shortcode" value="<?php echo isset($block) ? esc_attr($block->shortcode) : ''; ?>" pattern="[a-z0-9_-]+" required>

        <?php
        if(isset($block)){ ?>
            <span style="margin-left: 16px">[<?php echo esc_attr($block->shortcode); ?>]</span>
        <?php } else { ?>
        <span style="margin-left: 16px">
            Only lowercase letters, numbers, hyphens and underscores are allowed</span>
        <?php }  ?>

    </div>
</div>

<div class="row-title-data">
    <div class="title"><?php esc_html_e('Description', 'block-systems-hm'); ?></div>
    <div class="data">
        <textarea name="description" id="description" > <?php echo isset($block) ? esc_attr($block->description) : ''; ?></textarea>
    </div>
</div>

<?php if(isset($single_block_types) && $single_block_types->check_css == 1) { ?>
<div class="row-title-data">
    <div class="title"><?php esc_html_e('Css', 'block-systems-hm'); ?></div>
    <div class="data">
        <textarea name="css"  id="css"><?php echo isset($block) ? stripslashes($block->css) : ''; ?></textarea>
    </div>
</div>
<?php }?>

<?php if(isset($single_block_types) && $single_block_types->check_code == 1) { ?>
<div class="row-title-data">
    <div class="title"><?php esc_html_e('Code', 'block-systems-hm'); ?></div>
    <div class="data">
        <textarea name="code"  id="code"><?php echo isset($block) ? stripslashes($block->code): ''; ?></textarea>
    </div>
</div>
<?php }?>