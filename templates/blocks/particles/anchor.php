
    <div class="table-title-data" >
        <h3>Anchor</h3>
        <div class="row-title-data">
            <div class="title"><?php esc_html_e('Tag', 'block-systems-hm'); ?></div>
            <div class="data">
                <select name="tags" id="tags" required>
                    <?php
                    $blocks_systems_tags = ['h1', 'h2', 'h3', 'h4', 'h5', 'h6', 'p', 'a', 'div', 'span'];
                    foreach ($blocks_systems_tags as $blocks_systems_tag) {
                        $selected = (isset($block) && ($blocks_systems_tag == $block->tags)) ? 'selected' : '';
                        echo '<option value="' . esc_attr($blocks_systems_tag) . '" ' . $selected . '>' . esc_html($blocks_systems_tag) . '</option>';
                    }
                    ?>
                </select>
            </div>
        </div>
        <div class="row-title-data">
            <div class="title"><?php esc_html_e('Position', 'block-systems-hm'); ?></div>
            <div class="data">
                <input type="number" name="position" id="position" min="0" value="<?php echo isset($block) ? esc_attr($block->position) : 0; ?>">
            </div>
        </div>
        <div class="row-title-data">
            <div class="title"><?php esc_html_e('Priority', 'block-systems-hm'); ?></div>
            <div class="data">
                <input type="number" name="priority" id="priority" min="0" value="<?php echo isset($block) ? esc_attr($block->priority) : 0; ?>">
            </div>
        </div>
        <div class="row-title-data">
            <div class="title"><?php esc_html_e('Flag', 'block-systems-hm'); ?></div>
            <div class="data">
                <table>
                    <tr>
                        <td>Before
                            <input type="radio" name="flag" value="before" <?php if(isset($block)) checked($block->flag, 'before'); ?>></td>

                        <td>After
                            <input type="radio" name="flag" value="after" <?php
                            if(isset($block)){
                                checked($block->flag, 'after');
                            }else{
                                echo 'checked';
                            }
                            ?>>
                        </td>
                    </tr>
                </table>
            </div>
        </div>
    </div>
