<div class="wrap block-systems-hm">
    <h1><?php esc_html_e('Add New Block Type', 'block-systems-hm'); ?></h1>

    <form method="post" action="">
        <input type="hidden" name="action" value="add">
        <?php wp_nonce_field('add_block_type', 'block_systems_hm_add_block_type_nonce'); ?>

        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="name"><?php esc_html_e('Name', 'block-systems-hm'); ?></label>
                </th>
                <td>
                    <input type="text" name="name" id="name" required>
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="check_css"><?php esc_html_e('Check CSS', 'block-systems-hm'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="check_css" id="check_css" >
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="check_code"><?php esc_html_e('Check Code', 'block-systems-hm'); ?></label>
                </th>
                <td>
                    <input type="checkbox" name="check_code" id="check_code" >
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="name"><?php esc_html_e('Fields', 'block-systems-hm'); ?></label>
                </th>
                <td>
                    <div id="fields-container">
                        <div class="field-row">
                            <input type="text" name="fields[0][name]" placeholder="<?php esc_attr_e('Field Name', 'block-systems-hm'); ?>" >
                            <select name="fields[0][type]">
                                <option value="text"><?php esc_html_e('Text', 'block-systems-hm'); ?></option>
                                <option value="textarea"><?php esc_html_e('Textarea', 'block-systems-hm'); ?></option>
                                <option value="img"> <?php esc_html_e('Image', 'block-systems-hm'); ?></option>
                                <option value="checkbox"><?php esc_html_e('Checkbox', 'block-systems-hm'); ?></option>

                                <option value="img"><?php esc_html_e('Image', 'block-systems-hm'); ?></option>
                            </select>
                            <button type="button" class="remove-field button-link-delete"><?php esc_html_e('Remove', 'block-systems-hm'); ?></button>
                        </div>
                    </div>
                    <button type="button" id="add-field" class="button"><?php esc_html_e('Add Field', 'block-systems-hm'); ?></button>
                </td>
            </tr>
            </tbody>
        </table>

        <?php submit_button(__('Add Block Type', 'block-systems-hm'), 'primary', 'submit_add_block_type'); ?>
    </form>
</div>
<script>
    (function($) {
        $(document).ready(function() {
            var fieldsContainer = $('#fields-container');
            var addFieldButton = $('#add-field');
            var fieldRowTemplate = fieldsContainer.find('.field-row').first().clone();

            addFieldButton.on('click', function() {
                var newRow = fieldRowTemplate.clone();
                var rowIndex = fieldsContainer.find('.field-row').length;
                newRow.find('input, select').each(function() {
                    var name = $(this).attr('name').replace('[0]', '[' + rowIndex + ']');
                    $(this).attr('name', name);
                });
                fieldsContainer.append(newRow);
            });

            fieldsContainer.on('click', '.remove-field', function() {
                $(this).closest('.field-row').remove();
            });
        });
    })(jQuery);
</script>