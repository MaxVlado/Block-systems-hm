<?php
/**
 * Class Block_Systems_HM_Blocks_Controller
 */
class Block_Systems_HM_Blocks_Controller {

    public function __construct() {
        add_action('wp_ajax_get_block_type_fields', array($this, 'get_block_type_fields'));
        add_action('init', array($this, 'register_block_shortcodes'));
        add_filter('the_content', array($this, 'insert_block_shortcode'));
        add_action('wp_ajax_get_posts_list', array($this, 'get_posts_list'));
        add_action('wp_ajax_nopriv_get_posts_list', array($this, 'get_posts_list'));
        add_action('wp_ajax_get_posts', array($this, 'get_posts'));
        add_action('wp_ajax_nopriv_get_posts', array($this, 'get_posts'));
    }

    /**
     * Handle block actions.
     */
    public function handle_actions() {

        if (isset($_POST['action'])) {
            $action = sanitize_text_field($_POST['action']);

            if ($action === 'add') {
                    $this->handle_add_block();
            }
        } elseif (isset($_GET['action'])) {
            $action = sanitize_text_field($_GET['action']);
            if ($action === 'delete') {
                if (isset($_GET['block_id'])) {
                    $block_id = absint($_GET['block_id']);
                    $this->handle_delete_block($block_id);
                }
            }
        }

    }

    private function handle_add_block() {
        if (isset($_POST['submit_add_block'])) {
            // Проверка nonce и прав пользователя

            $block_model = new Block_Systems_HM_Block_Model();
            $block_type_id = isset($_POST['block_type_id']) ? intval($_POST['block_type_id']) : 0;
            $shortcode = isset($_POST['shortcode']) ? sanitize_text_field($_POST['shortcode']) : '';
            $unique_shortcode = $block_model->generate_unique_shortcode($shortcode);
            $anchors = isset($_POST['anchors']) && is_array($_POST['anchors']) ? $_POST['anchors'] : array();

            $fields = isset($_POST['fields']) ? $_POST['fields'] : array();

            $data = array(
                'block_type_id' => $block_type_id,
                'shortcode' => $unique_shortcode,
                'css' => isset($_POST['css']) ? $_POST['css'] : null,
                'code' => isset($_POST['code']) ? $_POST['code'] : null,
                'description' => isset($_POST['description']) ? sanitize_text_field($_POST['description']) : null,
                'current_fields' => serialize($fields)
            );

            $data['place'] = isset($_POST['place']) ? sanitize_text_field($_POST['place']) : '';
            //$data['tags'] = isset($_POST['tags']) ? sanitize_text_field($_POST['tags']) : '';
           // $data['position'] = isset($_POST['position']) ? intval($_POST['position']) : 0;
           // $data['priority'] = isset($_POST['priority']) ? intval($_POST['priority']) : 0;
           // $data['flag'] = isset($_POST['flag']) ? sanitize_text_field($_POST['flag']) : '';
            $data['selected_posts'] = isset($_POST['selected_posts']) ? implode(',', array_map('intval', $_POST['selected_posts'])) : '';

            $data['selected_rubrics'] = isset($_POST['selected_rubrics']) && is_array($_POST['selected_rubrics']) ? implode(',', $_POST['selected_rubrics']) : '';
            $data['selected_tags'] = isset($_POST['selected_tags']) && is_array($_POST['selected_tags']) ? implode(',', $_POST['selected_tags']) : '';
            $data['excluded_posts'] = isset($_POST['excluded_posts']) && is_array($_POST['excluded_posts']) ? implode(',', $_POST['excluded_posts']) : '';



            $block_id = $block_model->create_block($data);
            $anchor_model = new Block_Systems_HM_Block_Anchor_Model();
            $createAnchors = $anchor_model->create_ancors($anchors,$block_id);

            if ($block_id && $createAnchors) {
                // Блок успешно добавлен
                wp_redirect(admin_url('admin.php?page=block-systems-hm-all-blocks&message=block_added'));
                exit;
            } else {
                // Ошибка при добавлении блока
                echo '<div class="notice notice-error"><p>' . esc_html__('Failed to add the block.', 'block-systems-hm') . '</p></div>';
            }
        }
    }

    private function handle_delete_block($block_id) {

        $block_model = new Block_Systems_HM_Block_Model();

        if ($block_model->delete_block($block_id)) {
            // Block deleted successfully
            $redirect_url = add_query_arg(
                array(
                    'page' => 'block-systems-hm-all-blocks',
                    'message' => 'block_deleted'
                ),
                admin_url('admin.php')
            );
            wp_redirect($redirect_url);
            exit;
        } else {
            // Failed to delete the block
            echo '<div class="notice notice-error"><p>' . esc_html__('Failed to delete the block.', 'block-systems-hm') . '</p></div>';
        }
    }

    public function get_all_blocks() {
        $block_model = new Block_Systems_HM_Block_Model();
        return $block_model->get_all_blocks();
    }

    public function get_all_anchors() {
        $block_anchors_model = new Block_Systems_HM_Block_Anchor_Model();
        return $block_anchors_model->get_all_anchors();
    }

    public function get_block_type_fields() {
    $block_type_id = isset($_GET['block_type_id']) ? intval($_GET['block_type_id']) : 0;

    if ($block_type_id) {
        $block_type_model = new Block_Systems_HM_Block_Type_Model();
        $block_type = $block_type_model->get_block_type($block_type_id);

        if ($block_type && !empty($block_type->fields)) {
            $fields = maybe_unserialize($block_type->fields); ?>

            <?php
            foreach ($fields as $field) {
                $field_name = $field['name']; ?>
               <div class="row-title-data">
                    <div class="title"><?php echo esc_html($field['name']); ?></div>
                    <div class="data">
                        <?php
                        switch ($field['type']) {
                            case 'text':
                                ?>
                                <input type="text" name="fields[<?php echo esc_attr($field_name); ?>]" id="field_<?php echo esc_attr($field_name); ?>" >
                                <?php
                                break;
                            case 'textarea':
                                ?>
                                <textarea name="fields[<?php echo esc_attr($field_name); ?>]" id="field_<?php echo esc_attr($field_name); ?>" rows="4" ></textarea>
                                <?php
                                break;
                            case 'checkbox':
                                ?>
                                <input type="checkbox" name="fields[<?php echo esc_attr($field_name); ?>]" id="field_<?php echo esc_attr($field_name); ?>">
                                <?php
                                break;
                            case 'img':
                                ?>
                                <input type="text" name="fields[<?php echo esc_attr($field_name); ?>]" id="field_<?php echo esc_attr($field_name); ?>" class="image-field" >
                                <button type="button" class="button select-image">Select Image</button>
                                <?php
                                break;
                            // Добавьте обработку других типов полей
                        }
                        ?>
                    </div>
                </div>
                <?php
            } ?>

                <?php
        }
    }

    wp_die();
}

    public function get_block_by_shortcode($tag) {
        $block_model = new Block_Systems_HM_Block_Model();
        $block = $block_model->get_block_by_shortcode($tag);
        return $block;
    }

// shortcodes
    public function register_block_shortcodes() {
        $blocks = $this->get_all_blocks();
        foreach ($blocks as $block) {
            add_shortcode($block->shortcode, array($this, 'render_block_shortcode'));
        }
        add_action('wp_footer', array($this, 'generate_block_styles'));
        add_action('admin_footer', array($this, 'generate_block_styles'));
    }

    public  function generate_block_styles() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'blocks';
        $blocks = $wpdb->get_results("SELECT * FROM $table_name WHERE css IS NOT NULL AND css != ''");

        if (!empty($blocks)) {
            echo '<style>';
            foreach ($blocks as $block) {
                echo stripslashes($block->css) . "\n\n";
            }
            echo '</style>';
        }
    }


    public function get_posts_list() {

        $block_id = isset($_GET['block_id']) ? intval($_GET['block_id']) : false;

        if ($block_id) {
            global $wpdb;
            $block = $wpdb->get_row($wpdb->prepare("SELECT * FROM {$wpdb->prefix}blocks WHERE id = %d", $block_id));
            if ($block) {
                $selectedPosts = explode(',', $block->selected_posts);
                if (!is_array($selectedPosts)) {
                    $selectedPosts = [];
                }
            } else {
                $selectedPosts = [];
            }
        } else {
            $selectedPosts = [];
        }

        if (isset($_GET['selected_posts'])) {
            $selectedPosts = array_map('intval', $_GET['selected_posts']);
        }

        $filterName = isset($_GET['filter_name']) ? sanitize_text_field($_GET['filter_name']) : '';
        $filterUrl = isset($_GET['filter_url']) ? sanitize_text_field($_GET['filter_url']) : '';
        $filterID = isset($_GET['filter_id']) ? intval($_GET['filter_id']) : 0;
        $filterDate = isset($_GET['filter_date']) ? sanitize_text_field($_GET['filter_date']) : '';
        $filterTag = isset($_GET['filter_tag']) ? sanitize_text_field($_GET['filter_tag']) : '';
        $sortOrder = isset($_GET['sort_order']) ? sanitize_text_field($_GET['sort_order']) : 'DESC';
        $filterChecked = isset($_GET['filter_checked']) ? sanitize_text_field($_GET['filter_checked']) : '';

        $args = array(
            'post_type' => 'post',
            'posts_per_page' => -1, // Получаем все посты
            'orderby' => 'date',
            'order' => $sortOrder,
            'meta_query' => array(
                'relation' => 'AND',
            ),

        );

        // Исключение страниц из списка постов
        if (get_option('block_systems_hm_exclude_pages')) {
            $args['post_type'] = 'post';
        }

        // Фильтрация по рубрикам и тегам
        if ($filterChecked === 'checked') {
            $args['post__in'] = $selectedPosts;
        } elseif ($filterChecked === 'unchecked') {
            $args['post__not_in'] = $selectedPosts;
        }

        if (!empty($filterName)) {
           // $args['title'] = $filterName;
            $args['s'] = $filterName;
        }

        if (!empty($filterUrl)) {
            $url_slug = basename($filterUrl);
            $args['name'] = $url_slug;
        }

        if (!empty($filterID)) {
            $args['p'] = $filterID;
        }

        if (!empty($filterDate)) {
            $args['date_query'] = array(
                array(
                    'year' => date('Y', strtotime($filterDate)),
                    'month' => date('m', strtotime($filterDate)),
                    'day' => date('d', strtotime($filterDate)),
                )
            );
        }

        if (!empty($filterTag)) {
            $args['tag'] = $filterTag;
        }

        if ($sortOrder === 'ASC' || $sortOrder === 'DESC') {
            $args['orderby'] = 'date';
            $args['order'] = $sortOrder;
        }

        // Пагинация
        $posts = get_posts($args);

        ob_start();
        ?>

        <table class="wp-list-table" id="posts-table">
            <?php foreach ($posts as $index => $post) : ?>
                <tr <?php if ($index >= 10) echo 'style="display: none;"'; ?>>
                    <td><?php echo $index + 1; ?></td>
                    <td>
                        <label>
                            <input type="checkbox" name="selected_posts[]" value="<?php echo $post->ID; ?>" <?php checked(in_array($post->ID, $selectedPosts)); ?>>
                            <?php echo $post->post_title; ?>
                        </label>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <div class="pagination" id="pagination-container"></div>

        <?php
        $html = ob_get_clean();

        wp_send_json_success(array(
            'html' => $html,
            'total_posts' => count($posts),
        ));
    }


    public function insert_block_shortcode_rab($content) {
        if (!is_single()) {
            return $content;
        }
        $blocks = $this->get_all_blocks();
        foreach ($blocks as $block) {
            if ($block->place === 'manual') {
                $shortcode = '[' . $block->shortcode . ']';
                $content .= $shortcode;
                $display_block = false;
            }
            if ($block->place === 'all-page') {
                $display_block = true;
            }
            if ($block->place === 'custom') {
                $selected_rubrics = explode(',', $block->selected_rubrics);
                $selected_tags = explode(',', $block->selected_tags);
                $excluded_posts = explode(',', $block->excluded_posts);
                $selected_posts = explode(',', $block->selected_posts);

                $current_rubrics = wp_get_post_categories(get_the_ID(), array('fields' => 'ids'));
                $current_tags = wp_get_post_tags(get_the_ID(), array('fields' => 'ids'));

                // Проверка совпадения рубрик, тегов и выбранных постов
                $display_block = false;
                if (array_intersect($selected_rubrics, $current_rubrics) ||
                    array_intersect($selected_tags, $current_tags) ||
                    in_array(get_the_ID(), $selected_posts)) {
                    $display_block = true;
                }

                // Исключение постов
                if (in_array(get_the_ID(), $excluded_posts)) {
                    $display_block = false;
                }
            }
            if ($display_block) {
                $shortcode = '[' . $block->shortcode . ']';
                $position = intval($block->position);
                $pattern = '/(<' . $block->tags . '[^>]*>.*?<\/' . $block->tags . '>)/is';
                $content = preg_replace_callback($pattern, function ($matches) use ($shortcode, $position, $block) {
                    static $count = 0;
                    $count++;
                    if ($count == $position) {
                        if ($block->flag === 'before') {
                            return $shortcode . $matches[0];
                        } else {
                            return $matches[0] . $shortcode;
                        }
                    }
                    return $matches[0];
                }, $content);
            }

        }
        return $content;
    }

    public function insert_block_shortcode_sort_rab($content) {
        if (!is_single()) {
            return $content;
        }
        $blocks = $this->get_all_blocks();

        // Группируем блоки по tags и position
        $grouped_blocks = array();
        foreach ($blocks as $block) {
            if ($block->place === 'manual') {
                continue; // Пропускаем блоки с place=manual
            }
            $key = $block->tags . '_' . $block->position;
            if (!isset($grouped_blocks[$key])) {
                $grouped_blocks[$key] = array();
            }
            $grouped_blocks[$key][] = $block;
        }

        // Обрабатываем блоки в отсортированном порядке
        $processed_blocks = array();
        foreach ($grouped_blocks as $group) {
            // Сортируем блоки внутри группы по флагу (before идет перед after) и приоритету
            usort($group, function($a, $b) {
                if ($a->flag === $b->flag) {
                    return $a->priority - $b->priority;
                }
                return ($a->flag === 'before') ? -1 : 1;
            });

            foreach ($group as $block) {
                $display_block = false;
                if ($block->place === 'all-page') {
                    $display_block = true;
                }
                if ($block->place === 'custom') {
                    $selected_rubrics = explode(',', $block->selected_rubrics);
                    $selected_tags = explode(',', $block->selected_tags);
                    $excluded_posts = explode(',', $block->excluded_posts);
                    $selected_posts = explode(',', $block->selected_posts);

                    $current_rubrics = wp_get_post_categories(get_the_ID(), array('fields' => 'ids'));
                    $current_tags = wp_get_post_tags(get_the_ID(), array('fields' => 'ids'));

                    // Проверка совпадения рубрик, тегов и выбранных постов
                    if (array_intersect($selected_rubrics, $current_rubrics) ||
                        array_intersect($selected_tags, $current_tags) ||
                        in_array(get_the_ID(), $selected_posts)) {
                        $display_block = true;
                    }

                    // Исключение постов
                    if (in_array(get_the_ID(), $excluded_posts)) {
                        $display_block = false;
                    }
                }
                if ($display_block && !in_array($block->id, $processed_blocks)) {
                    $shortcode = '[' . $block->shortcode . ']';
                    $position = intval($block->position);
                    $pattern = '/(<' . $block->tags . '[^>]*>.*?<\/' . $block->tags . '>)/is';
                    $content = preg_replace_callback($pattern, function ($matches) use ($shortcode, $position, $block) {
                        static $count = array();
                        if (!isset($count[$block->tags])) {
                            $count[$block->tags] = 0;
                        }
                        $count[$block->tags]++;
                        if ($count[$block->tags] == $position) {
                            if ($block->flag === 'before') {
                                return $shortcode . $matches[0];
                            } else {
                                return $matches[0] . $shortcode;
                            }
                        }
                        return $matches[0];
                    }, $content, 1);
                    $processed_blocks[] = $block->id;
                }
            }
        }
        return $content;
    }

    public function insert_block_shortcode($content) {
        if (!is_single()) {
            return $content;
        }

        $blocks = $this->get_all_blocks();
        $anchors = $this->get_all_anchors();

        // Группируем якоря по block_id
        $grouped_anchors = array();
        foreach ($anchors as $anchor) {
            if (!isset($grouped_anchors[$anchor->block_id])) {
                $grouped_anchors[$anchor->block_id] = array();
            }
            $grouped_anchors[$anchor->block_id][] = $anchor;
        }

        // Обрабатываем блоки
        $processed_blocks = array();
        foreach ($blocks as $block) {
            if ($block->place === 'manual') {
                continue; // Пропускаем блоки с place=manual
            }

            $display_block = false;
            if ($block->place === 'all-page') {
                $display_block = true;
            }

            if ($block->place === 'custom') {
                $selected_rubrics = explode(',', $block->selected_rubrics);
                $selected_tags = explode(',', $block->selected_tags);
                $excluded_posts = explode(',', $block->excluded_posts);
                $selected_posts = explode(',', $block->selected_posts);
                $current_rubrics = wp_get_post_categories(get_the_ID(), array('fields' => 'ids'));
                $current_tags = wp_get_post_tags(get_the_ID(), array('fields' => 'ids'));

                // Проверка совпадения рубрик, тегов и выбранных постов
                if (array_intersect($selected_rubrics, $current_rubrics) || array_intersect($selected_tags, $current_tags) || in_array(get_the_ID(), $selected_posts)) {
                    $display_block = true;
                }

                // Исключение постов
                if (in_array(get_the_ID(), $excluded_posts)) {
                    $display_block = false;
                }
            }

            if ($display_block && !in_array($block->id, $processed_blocks)) {
                if (isset($grouped_anchors[$block->id])) {
                    // Сортируем якоря по position и priority
                    usort($grouped_anchors[$block->id], function($a, $b) {
                        if ($a->position == $b->position) {
                            return $a->priority - $b->priority;
                        }
                        return $a->position - $b->position;
                    });

                    foreach ($grouped_anchors[$block->id] as $anchor) {
                        $shortcode = '[' . $block->shortcode . ']';
                        $position = intval($anchor->position);
                        $tag = $anchor->tag;
                        $flag = $anchor->flag;

                        $pattern = '/(<' . preg_quote($tag, '/') . '[^>]*>.*?<\/' . preg_quote($tag, '/') . '>)/is';
                        $content = preg_replace_callback($pattern, function ($matches) use ($shortcode, $position, $flag) {
                            static $count = 0;
                            $count++;
                            if ($count == $position) {
                                if ($flag === 'before') {
                                    return $shortcode . $matches[0];
                                } else {
                                    return $matches[0] . $shortcode;
                                }
                            }
                            return $matches[0];
                        }, $content);
                    }
                }

                $processed_blocks[] = $block->id;
            }
        }

        return $content;
    }

    public function render_block_shortcode($atts, $content, $tag) {
        $block = $this->get_block_by_shortcode($tag);
        $template_model = new Block_Systems_HM_Template_Model();
        $block_type_model = new Block_Systems_HM_Block_Type_Model();
        $block_type = $block_type_model->get_block_type($block->block_type_id);

        if ($block) {
            if ($block->place === 'manual' || $block->place === 'all-page') {
                return $template_model->getBlockSystemsHMTemplate($block,$block_type->id,);
            }

            if ($block->place === 'custom') {
                $selected_rubrics = explode(',', $block->selected_rubrics);
                $selected_tags = explode(',', $block->selected_tags);
                $excluded_posts = explode(',', $block->excluded_posts);
                $selected_posts = explode(',', $block->selected_posts);

                $current_rubrics = wp_get_post_categories(get_the_ID(), array('fields' => 'ids'));
                $current_tags = wp_get_post_tags(get_the_ID(), array('fields' => 'ids'));

                // Проверка совпадения рубрик, тегов и выбранных постов
                $display_block = false;
                if (array_intersect($selected_rubrics, $current_rubrics) ||
                    array_intersect($selected_tags, $current_tags) ||
                    in_array(get_the_ID(), $selected_posts)) {
                    $display_block = true;
                }

                // Исключение постов
                if (in_array(get_the_ID(), $excluded_posts)) {
                    $display_block = false;
                }

                if ($display_block) {
                   return $template_model->getBlockSystemsHMTemplate($block,$block_type->id,);
                }
            }

        }
        return '';
    }





}