<div class="wrap block-systems-hm">
    <h1><?php esc_html_e('All Block Types', 'block-systems-hm'); ?></h1> <br/>
    <div class="wp-clearfix"><a
            href="<?php echo esc_url(admin_url('admin.php?page=block-systems-hm-add-block-type')); ?>"
            class="page-title-action"><?php esc_html_e('Add New', 'block-systems-hm'); ?></a></div>
    <br/>
    <table class="wp-list-table widefat striped">
        <thead>
        <tr>
            <th scope="col"><?php esc_html_e('ID', 'block-systems-hm'); ?></th>
            <th scope="col"><?php esc_html_e('Name', 'block-systems-hm'); ?></th>
            <th scope="col"><?php esc_html_e('Fields', 'block-systems-hm'); ?></th>
            <th scope="col"><?php esc_html_e('CSS', 'block-systems-hm'); ?></th>
            <th scope="col"><?php esc_html_e('Code', 'block-systems-hm'); ?></th>
            <th scope="col" class="w-50"><?php esc_html_e('Actions', 'block-systems-hm'); ?></th>
        </tr>
        </thead>
        <tbody> <?php foreach ($all_block_types as $block_type) : ?>
            <tr>
                <td><?php echo esc_html($block_type->id); ?></td>
                <td>
                    <a class="edit-link" href="<?php echo esc_url(add_query_arg(array('page' => 'block-systems-hm-edit-block-type', 'action' => 'edit', 'block_type_id' => $block_type->id), admin_url('admin.php'))); ?>">
                        <?php echo esc_html($block_type->name); ?>
                    </a>

                </td>
                <td> <?php $fields = maybe_unserialize($block_type->fields);
                    if (!empty($fields)) {
                        echo '<ul>';
                        foreach ($fields as $field) {
                            echo '<li>';
                            echo esc_html($field['name']) . ' (' . esc_html($field['type']) . ')';
                            echo '</li>';
                        }
                        echo '</ul>';
                    } else {
                        echo '-';
                    } ?> </td>
                <td>
                    <?php echo ($block_type->check_css == 1) ? '<span class="dashicons dashicons-saved"></span>' : ''; ?>
                </td>
                <td>
                    <?php echo ($block_type->check_code == 1) ? '<span class="dashicons dashicons-saved"></span>' : ''; ?>
                </td>
                <td>
                    <a href="<?php echo esc_url(add_query_arg(array('page' => 'block-systems-hm-all-block-types', 'action' => 'delete', 'block_type_id' => $block_type->id), admin_url('admin.php'))); ?>"
                       onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this block type?', 'block-systems-hm'); ?>');"> <?php esc_html_e('Delete', 'block-systems-hm'); ?> </a>
                </td>
            </tr> <?php endforeach; ?> </tbody>
    </table>
</div>