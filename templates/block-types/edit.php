<?php /* Edit Block Type Form */ ?>
<div class="wrap block-systems-hm">
    <h1><?php esc_html_e('Edit Block Type', 'block-systems-hm'); ?></h1>

    <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=block-systems-hm-edit-block-type&action=update')); ?>">
        <input type="hidden" name="action" value="update">

        <input type="hidden" name="block_type_id" value="<?php echo esc_attr($block_type->id); ?>">
        <?php wp_nonce_field('block_systems_hm_edit_block_type', '_wpnonce'); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="name"><?php esc_html_e('Name', 'block-systems-hm'); ?></label>
                </th>
                <td>
                    <input type="text" name="name" id="name" value="<?php echo esc_attr($block_type->name); ?>" required>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="check_css"><?php esc_html_e('Check CSS', 'block-systems-hm'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="check_css" id="check_css" <?php checked($block_type->check_css,1) ?>>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="check_code"><?php esc_html_e('Check Code', 'block-systems-hm'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="check_code" id="check_code" <?php checked($block_type->check_code,1) ?>>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label><?php esc_html_e('Fields', 'block-systems-hm'); ?></label>
                </th>
                <td>
                    <div id="fields-container">
                        <?php $fields = maybe_unserialize($block_type->fields); ?>
                        <?php if (!empty($fields)) : ?>
                            <?php foreach ($fields as $index => $field) : ?>
                                <div class="field-row">
                                    <input type="text" name="fields[<?php echo $index; ?>][name]" value="<?php echo esc_attr($field['name']); ?>" placeholder="<?php esc_attr_e('Field Name', 'block-systems-hm'); ?>" required>
                                    <select name="fields[<?php echo $index; ?>][type]">
                                        <option value="text" <?php selected($field['type'], 'text'); ?>><?php esc_html_e('Text', 'block-systems-hm'); ?></option>
                                        <option value="textarea" <?php selected($field['type'], 'textarea'); ?>><?php esc_html_e('Textarea', 'block-systems-hm'); ?></option>
                                        <option value="checkbox" <?php selected($field['type'], 'checkbox'); ?>><?php esc_html_e('Checkbox', 'block-systems-hm'); ?></option>
                                        <option value="img" <?php selected($field['type'], 'img'); ?>><?php esc_html_e('Image', 'block-systems-hm'); ?></option>

                                    </select>
                                    <button type="button" class="remove-field button-link-delete"><?php esc_html_e('Remove', 'block-systems-hm'); ?></button>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <button type="button" id="add-field" class="button"><?php esc_html_e('Add Field', 'block-systems-hm'); ?></button>
                </td>
            </tr>
            </tbody>
        </table>
        <?php submit_button(__('Update Block Type', 'block-systems-hm'), 'primary', 'submit_edit_block_type'); ?>
    </form>
</div>

<script>
    (function($) {
        $(document).ready(function() {
            var fieldsContainer = $('#fields-container');
            var addFieldButton = $('#add-field');
            var fieldRowTemplate = `
                <div class="field-row">
                    <input type="text" name="fields[{index}][name]" placeholder="<?php esc_attr_e('Field Name', 'block-systems-hm'); ?>" required>
                    <select name="fields[{index}][type]">
                        <option value="text"><?php esc_html_e('Text', 'block-systems-hm'); ?></option>
                        <option value="textarea"><?php esc_html_e('Textarea', 'block-systems-hm'); ?></option>
                        <option value="checkbox"><?php esc_html_e('Checkbox', 'block-systems-hm'); ?></option>
                        <option value="img"><?php esc_html_e('Image', 'block-systems-hm'); ?></option>
                        <!-- Добавьте другие типы полей -->
                    </select>
                    <button type="button" class="remove-field button-link-delete"><?php esc_html_e('Remove', 'block-systems-hm'); ?></button>
                </div>
            `;

            addFieldButton.on('click', function() {
                var rowIndex = fieldsContainer.find('.field-row').length;
                var newRow = fieldRowTemplate.replace(/{index}/g, rowIndex);
                fieldsContainer.append(newRow);
            });

            fieldsContainer.on('click', '.remove-field', function() {
                $(this).closest('.field-row').remove();
            });
        });
    })(jQuery);
</script>