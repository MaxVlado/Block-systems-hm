<div class="wrap block-systems-hm">
    <h1><?php esc_html_e( 'All Blocks', 'block-systems-hm' ); ?></h1>
    <br/>
    <div class="wp-clearfix">
        <a href="<?php echo esc_url(admin_url('admin.php?page=block-systems-hm-add-block')); ?>" class="page-title-action"><?php esc_html_e('Add New', 'block-systems-hm'); ?></a>
    </div>
    <br/>
    <table class="wp-list-table widefat striped">
        <thead>
        <tr>
            <th scope="col"><?php esc_html_e( 'Description', 'block-systems-hm' ); ?></th>
            <th scope="col"><?php esc_html_e( 'Block Type', 'block-systems-hm' ); ?></th>
            <th scope="col"><?php esc_html_e( 'Shortcode', 'block-systems-hm' ); ?></th>
            <th scope="col"><?php esc_html_e( 'Posts', 'block-systems-hm' ); ?></th>
            <th scope="col"><?php esc_html_e( 'Tags', 'block-systems-hm' ); ?></th>
            <th scope="col"><?php esc_html_e( 'Act.', 'block-systems-hm' ); ?></th>
        </tr>
        </thead>
        <tbody>
        <?php foreach ( $all_blocks as $block ) :
            $block_type_model = new Block_Systems_HM_Block_Type_Model();
            $block_anchor_model = new Block_Systems_HM_Block_Anchor_Model();
            $anchors = $block_anchor_model->get_anchor($block->id);
            $posts = maybe_unserialize($block->selected_posts);
            $tags = maybe_unserialize($block->selected_tags);
            ?>

            <tr >
                <td><?php echo esc_html( $block->description ); ?></td>
                <td>
                    <?php
                    $block_type = $block_type_model->get_block_type($block->block_type_id);
                    echo esc_html($block_type->name);
                    ?>
                </td>
                <td>
                    <a class="edit-link" href="<?php echo esc_url(add_query_arg(array('page' => 'block-systems-hm-edit-block', 'action' => 'edit', 'block_id' => $block->id), admin_url('admin.php'))); ?>">
                        [<?php echo esc_html( $block->shortcode ); ?>]
                    </a>
                </td>
                <td>
                    <?php if (!empty($posts)) { ?>
                        <ul class="posts-list">
                            <?php foreach (explode(',', $posts) as $post_id) {
                                $post_title = get_the_title($post_id);
                                ?>
                                <li><?= esc_html($post_title) ?></li>
                            <?php } ?>
                        </ul>
                    <?php }  ?>
                </td>
                <td>
                    <?php
                    if (!empty($tags)) {
                        $tag_ids = explode(',', $tags);
                        $tag_names = array();
                        foreach ($tag_ids as $tag_id) {
                            $tag = get_term($tag_id, 'post_tag');
                            if ($tag && !is_wp_error($tag)) {
                                $tag_names[] = $tag->name;
                            }
                        }
                        if (!empty($tag_names)) {
                            ?>
                            <ul class="tags-list">
                                <?php foreach ($tag_names as $tag_name) { ?>
                                    <li><?= esc_html($tag_name) ?></li>
                                <?php } ?>
                            </ul>
                            <?php
                        } }
                    ?>
                </td>
                <td>
                    <a class="delete-link" href="<?php echo esc_url(add_query_arg(array('page' => 'block-systems-hm-all-blocks', 'action' => 'delete', 'block_id' => $block->id), admin_url('admin.php'))); ?>"
                       onclick="return confirm('<?php esc_attr_e('Are you sure you want to delete this block?', 'block-systems-hm'); ?>');">
                        <span class="dashicons dashicons-trash"></span>
                    </a>
                </td>
            </tr>

        <?php endforeach; ?>
        </tbody>
    </table>
</div>