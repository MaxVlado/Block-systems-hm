<div class="wrap block-systems-hm">
    <h1><?php esc_html_e('Edit Block', 'block-systems-hm'); ?></h1>
    <form method="post" action="">
        <?php submit_button(__('Update Block', 'block-systems-hm'), 'primary', 'submit_edit_block'); ?>
        <?php wp_nonce_field('edit_block', 'block_systems_hm_edit_block_nonce'); ?>
        <input type="hidden" name="action" value="update">
        <input type="hidden" name="block_id" value="<?php echo esc_attr($block->id); ?>">
        <section class="template">
            <h3 class="" >Template</h3>
            <div class="data">
                <?php echo $template ; ?>
            </div>
            <div class="data-html">
                <pre><code><?php echo htmlentities($template); ?></code></pre>
            </div>
        </section>
        <section class="basic-option flex">
            <div class="left table-title-data">
                <?php  require_once plugin_dir_path(__FILE__) . 'particles/basic-option.php'; ?>

                <div id="block-fields-container">
                    <?php  require_once plugin_dir_path(__FILE__) . 'particles/block-fields.php'; ?>
                </div>
            </div>
        </section>
        <section class="place-option">
            <?php  require_once plugin_dir_path(__FILE__) . 'particles/place.php'; ?>
        </section>

        <section class="anchor-option" id="anchor-option">
            <div class="table-title-data">
                <h3>Anchors</h3>
                <div id="anchor-fields-container">
                    <?php
                    function get_block_anchors($block_id) {
                        global $wpdb;
                        $table_name = $wpdb->prefix . 'block_anchors';
                        $query = "SELECT * FROM $table_name WHERE block_id = %d";
                        $anchors = $wpdb->get_results($wpdb->prepare($query, $block_id));
                        return $anchors;
                    }

                    $anchors = get_block_anchors($block->id);
                    if (!empty($anchors)) {
                        foreach ($anchors as $anchor) {
                            ?>
                            <div class="anchor-field">
                                <div class="row-title-data">
                                    <div class="title"><?php esc_html_e('Tag', 'block-systems-hm'); ?></div>
                                    <div class="data">
                                        <select name="anchors[<?php echo $anchor->id; ?>][tag]" required>
                                            <?php
                                            $blocks_systems_tags = ['h2', 'h3', 'h4', 'h5', 'h6', 'p', 'a', 'div', 'span','h1' ];
                                            foreach ($blocks_systems_tags as $blocks_systems_tag) {
                                                $selected = ($blocks_systems_tag == $anchor->tag) ? 'selected' : '';
                                                echo '<option value="' . esc_attr($blocks_systems_tag) . '" ' . $selected . '>' . esc_html($blocks_systems_tag) . '</option>';
                                            }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="row-title-data">
                                    <div class="title"><?php esc_html_e('Position', 'block-systems-hm'); ?></div>
                                    <div class="data">
                                        <input type="number" name="anchors[<?php echo $anchor->id; ?>][position]" min="0" value="<?php echo esc_attr($anchor->position); ?>">
                                    </div>
                                </div>
                                <div class="row-title-data">
                                    <div class="title"><?php esc_html_e('Priority', 'block-systems-hm'); ?></div>
                                    <div class="data">
                                        <input type="number" name="anchors[<?php echo $anchor->id; ?>][priority]" min="0" value="<?php echo esc_attr($anchor->priority); ?>">
                                    </div>
                                </div>
                                <div class="row-title-data">
                                    <div class="title"><?php esc_html_e('Flag', 'block-systems-hm'); ?></div>
                                    <div class="data">
                                        <label for="anchors-before[<?php echo $anchor->id; ?>][flag]">
                                            Before <input type="radio" name="anchors[<?php echo $anchor->id; ?>][flag]"
                                                          id="anchors-before[<?php echo $anchor->id; ?>][flag]"
                                                          value="before" <?php checked($anchor->flag, 'before'); ?>>
                                        </label>
                                        <label for="anchors-after[<?php echo $anchor->id; ?>][flag]">
                                            After  <input type="radio" name="anchors[<?php echo $anchor->id; ?>][flag]"
                                                          id="anchors-after[<?php echo $anchor->id; ?>][flag]"
                                                          value="after" <?php checked($anchor->flag, 'after'); ?>>
                                        </label>

                                    </div>
                                </div>
                                <div class="remove-anchor-field">
                                    <button type="button" class="button remove-anchor-btn">Remove Anchor</button>
                                </div>
                            </div>
                            <?php
                        }
                    }
                    ?>
                </div>
                <div class="add-anchor-field">
                    <button type="button" class="button add-anchor-btn">Add Anchor</button>
                </div>
            </div>
        </section>

        <section id="filter-option" class="filter-option" style="display: none;">
            <?php  require_once plugin_dir_path(__FILE__) . 'particles/select-rubrics-tags.php'; ?>

            <div class="select">
                <div id="filter-bar" class="filter-bar" >
                    <?php  require_once plugin_dir_path(__FILE__) . 'particles/filter-bar.php'; ?>
                </div>
                <div class="select-post">
                    <h3>Select posts</h3>
                    <div id="selected-posts">
                        <?php
                        $all_post = explode(',',$block->selected_posts)
                        ?>
                        Selected <span ><?php echo count($all_post) ?> </span> posts
                    </div>
                    <div class="search-container">
                        <?php  require_once plugin_dir_path(__FILE__) . 'particles/search-containe.php'; ?>
                    </div>
                    <div id="posts-block">
                        <div class="" id="list-posts"></div>
                        <div id="pagination-container"></div>
                    </div>
                </div>
            </div>
        </section>

        <?php submit_button(__('Update Block', 'block-systems-hm'), 'primary', 'submit_edit_block'); ?>
    </form>
</div>
<?php
wp_localize_script('block-systems-hm-filter', 'blockSystemsData', array(
'blockId' => $block->id,
));
?>
<script>
    (function ($) {
        $(document).ready(function () {
            var anchorCounter = 0;

            // Add anchor field
            $('.add-anchor-btn').on('click', function () {
                var uniqueId = 'anchor_' + anchorCounter;
                var anchorField = '<div class="anchor-field">' +
                    '<div class="row-title-data">' +
                    '<div class="title"><?php esc_html_e('Tag', 'block-systems-hm'); ?></div>' +
                    '<div class="data">' +
                    '<select name="anchors[' + uniqueId + '][tag]" required>' +
                    '<?php
                        $blocks_systems_tags = [ 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'a', 'div', 'span','h1'];
                        foreach ($blocks_systems_tags as $blocks_systems_tag) {
                            echo '<option value="' . esc_attr($blocks_systems_tag) . '">' . esc_html($blocks_systems_tag) . '</option>';
                        }
                        ?>' +
                    '</select>' +
                    '</div>' +
                    '</div>' +
                    '<div class="row-title-data">' +
                    '<div class="title"><?php esc_html_e('Position', 'block-systems-hm'); ?></div>' +
                    '<div class="data">' +
                    '<input type="number" name="anchors[' + uniqueId + '][position]" min="1" value="1">' +
                    '</div>' +
                    '</div>' +
                    '<div class="row-title-data">' +
                    '<div class="title"><?php esc_html_e('Priority', 'block-systems-hm'); ?></div>' +
                    '<div class="data">' +
                    '<input type="number" name="anchors[' + uniqueId + '][priority]" min="0" value="0">' +
                    '</div>' +
                    '</div>' +
                    '<div class="row-title-data">' +
                    '<div class="title"><?php esc_html_e('Flag', 'block-systems-hm'); ?></div>' +
                    '<div class="data">' +
                    '<label for="anchors-before-' + uniqueId + '">' +
                    'Before <input type="radio" name="anchors[' + uniqueId + '][flag]" id="anchors-before-' + uniqueId + '" value="before" checked>' +
                    '</label>' +
                    '<label for="anchors-after-' + uniqueId + '">' +
                    'After <input type="radio" name="anchors[' + uniqueId + '][flag]" id="anchors-after-' + uniqueId + '" value="after">' +
                    '</label>' +
                    '</div>' +
                    '</div>' +
                    '<div class="remove-anchor-field">' +
                    '<button type="button" class="button remove-anchor-btn">Remove Anchor</button>' +
                    '</div>' +
                    '</div>';

                $('#anchor-fields-container').append(anchorField);
                anchorCounter++;
            });

            // Remove anchor field
            $(document).on('click', '.remove-anchor-btn', function () {
                $(this).closest('.anchor-field').remove();
            });
        });
    })(jQuery);
</script>

