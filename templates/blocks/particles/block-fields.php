<?php
$fields = maybe_unserialize($block->current_fields);
if (!empty($fields)) {
    ?>
    <?php
    foreach ($fields as $field_name => $field_value) {
        $field = $block_types->get_block_type_field_by_name($block->block_type_id, $field_name);
        if ($field) {
            ?>
            <div class="row-title-data">
                <div class="title">
                    <?php echo esc_html($field['name']); ?>
                </div>
                <div class="data">
                    <?php
                    switch ($field['type']) {
                        case 'text':
                            ?>
                            <input type="text" name="fields[<?php echo esc_attr($field_name); ?>]" id="field_<?php echo esc_attr($field_name); ?>" value="<?php echo esc_attr($field_value); ?>" >
                            <?php
                            break;
                        case 'textarea':
                            ?>
                            <textarea name="fields[<?php echo esc_attr($field_name); ?>]" id="field_<?php echo esc_attr($field_name); ?>" rows="4" ><?php echo esc_textarea($field_value); ?></textarea>
                            <?php
                            break;
                        case 'checkbox':
                            $checked = ($field_value == 'on') ? 'checked' : '';
                            ?>
                            <input type="checkbox" name="fields[<?php echo esc_attr($field_name); ?>]" id="field_<?php echo esc_attr($field_name); ?>" <?php echo $checked; ?>>
                            <?php
                            break;
                        case 'img':
                            ?>
                            <input type="text" name="fields[<?php echo esc_attr($field_name); ?>]" id="field_<?php echo esc_attr($field_name); ?>" class="image-field" value="<?php echo esc_attr($field_value); ?>" >
                            <button type="button" class="button select-image">Select Image</button>
                            <?php
                            break;
                        // Добавьте обработку других типов полей
                    }
                    ?>
                </div>
            </div>
            <?php
        }
    }
    ?>
    <?php
}
?>