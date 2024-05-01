<?php
/**
 * Class Block_Systems_HM_Admin
 */
class Block_Systems_HM_Admin {
    /**
     * Add the plugin settings page.
     */
    public function add_plugin_admin_menu() {
        add_menu_page(
            'Block Systems HM',
            'Block Systems HM',
            'manage_options',
            'block-systems-hm',
            array( $this, 'display_plugin_admin_page' )
        );

        add_submenu_page(
            'block-systems-hm',
            'Blocks',
            'Blocks',
            'manage_options',
            'block-systems-hm-all-blocks',
            array( $this, 'display_all_blocks_page' )
        );

        add_submenu_page(
            '',
            'Add Block',
            'Add Block',
            'manage_options',
            'block-systems-hm-add-block',
            array( $this, 'display_add_block_page' )
        );

        add_submenu_page(
            '',
            'Edit Block',
            'Edit Block',
            'manage_options',
            'block-systems-hm-edit-block',
            array($this, 'display_edit_block_page')
        );

        add_submenu_page(
            'block-systems-hm',
            'Block Types',
            'Block Types',
            'manage_options',
            'block-systems-hm-all-block-types',
            array( $this, 'display_all_block_types_page' )
        );

        add_submenu_page(
            '',
            'Add Block Type',
            'Add Block Type',
            'manage_options',
            'block-systems-hm-add-block-type',
            array( $this, 'display_add_block_type_page' )
        );


        add_submenu_page(
            '',
            'Edit Block Type',
            'Edit Block Type',
            'manage_options',
            'block-systems-hm-edit-block-type',
            array($this, 'display_edit_block_type_page')
        );
    }

    /**
     * Render the plugin settings page.
     */
    public function display_plugin_admin_page() {
      //  require_once plugin_dir_path( dirname( __FILE__ ) ) . 'admin/templates/block-systems-hm-admin-settings.php';
    }

    /**
     *  blocks page.
     */
    public function display_all_blocks_page() {
        $blocks_controller = new Block_Systems_HM_Blocks_Controller();
        $all_blocks = $blocks_controller->get_all_blocks();

        require_once plugin_dir_path(dirname(__FILE__)) . 'templates/blocks/index.php';
    }

    public function display_add_block_page() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'templates/blocks/create.php';

    }


    public function display_edit_block_page() {
        $block_id = isset($_GET['block_id']) ? absint($_GET['block_id']) : 0;
        $block_model = new Block_Systems_HM_Block_Model();
        $block = $block_model->get_block($block_id);

        if (isset($_POST['action']) && $_POST['action'] === 'update') {
            $block_id = isset($_POST['block_id']) ? absint($_POST['block_id']) : 0;
            $shortcode = sanitize_text_field($_POST['shortcode']);
            if ($shortcode !== $block->shortcode) {
                $unique_shortcode = $block_model->generate_unique_shortcode($shortcode);
            } else {
                $unique_shortcode = $block->shortcode;
            }

            $block_type_id = isset($_POST['block_type_id']) ? absint($_POST['block_type_id']) : 0;
            $fields = isset($_POST['fields']) && is_array($_POST['fields']) ? $_POST['fields'] : array();
            $anchors = isset($_POST['anchors']) && is_array($_POST['anchors']) ? $_POST['anchors'] : array();

            $data = array(
                'block_type_id' => $block_type_id,
                'shortcode' => $unique_shortcode,
                'css' => isset($_POST['css']) ? $_POST['css'] : null,
                'code' => isset($_POST['code']) ? $_POST['code'] : null,
                'description' => isset($_POST['description']) ? sanitize_text_field($_POST['description']) : null,
                'current_fields' => serialize($fields),


            );
            $data['place'] = isset($_POST['place']) ? sanitize_text_field($_POST['place']) : '';
          //  $data['tags'] = isset($_POST['tags']) ? sanitize_text_field($_POST['tags']) : '';
          //  $data['position'] = isset($_POST['position']) ? intval($_POST['position']) : 0;
         //   $data['priority'] = isset($_POST['priority']) ? intval($_POST['priority']) : 0;
         //   $data['flag'] = isset($_POST['flag']) ? sanitize_text_field($_POST['flag']) : '';

            $data['selected_posts'] = isset($_POST['selected_posts']) ? implode(',', array_map('intval', $_POST['selected_posts'])) : '';
            $data['selected_rubrics'] = isset($_POST['selected_rubrics']) && is_array($_POST['selected_rubrics']) ? implode(',', $_POST['selected_rubrics']) : '';
            $data['selected_tags'] = isset($_POST['selected_tags']) && is_array($_POST['selected_tags']) ? implode(',', $_POST['selected_tags']) : '';
            $data['excluded_posts'] = isset($_POST['excluded_posts']) && is_array($_POST['excluded_posts']) ? implode(',', $_POST['excluded_posts']) : '';

            // Обновление данных блока
            $updated = $block_model->update_block($block_id, $data);

            // Обработка данных якорей и обновление таблицы block_anchors
            $anchor_model = new Block_Systems_HM_Block_Anchor_Model();
            $updatedAnchors = $anchor_model->insertAnchor($anchors,$block_id);


            if ($updated && $updatedAnchors) {
                $redirect_url = admin_url('admin.php?page=block-systems-hm-edit-block&action=edit&block_id='.$block_id);
                echo '<script>window.location.href = "' . $redirect_url . '";</script>';
            } else {
                // Ошибка при обновлении данных
                echo '<div class="notice notice-error"><p>' . esc_html__('Failed to update the block.', 'block-systems-hm') . '</p></div>';
            }
        }

        if ($block) {

            $template_controller  = new Block_Systems_HM_Template_Model();
            $template = $template_controller->getBlockSystemsHMTemplate($block,$block->block_type_id);
                // Передача данных блока в файл представления

            require_once plugin_dir_path(dirname(__FILE__)) . '/templates/blocks/edit.php';
        } else {
            echo '<div class="notice notice-error"><p>' . esc_html__('Block not found.', 'block-systems-hm') . '</p></div>';
        }

    }

    /**
     *  block types page.
     */
    public function display_all_block_types_page() {
        $block_types_controller = new Block_Systems_HM_Block_Types_Controller();

        $all_block_types = $block_types_controller->get_all_block_types();

        require_once plugin_dir_path(dirname(__FILE__)) . 'templates/block-types/index.php';
    }

    public function display_edit_block_type_page() {
        $block_type_id = isset($_GET['block_type_id']) ? absint($_GET['block_type_id']) : 0;
        $block_type_model = new Block_Systems_HM_Block_Type_Model();
        $block_type = $block_type_model->get_block_type($block_type_id);

        // Обработка отправленной формы
        if (isset($_POST['action']) && $_POST['action'] === 'update') {
            $block_type_id = isset($_POST['block_type_id']) ? absint($_POST['block_type_id']) : 0;

            $data = array(
                'name' => sanitize_text_field($_POST['name']),
                'fields' => serialize($_POST['fields']),
                'check_css' =>  isset($_POST['check_css']) ? 1 : 0,
                'check_code' =>  isset($_POST['check_code']) ? 1 : 0,
            );

            // Обновление данных типа блока
            $updated = $block_type_model->update_block_type($block_type_id, $data);

            if ($updated) {

                // Данные успешно обновлены
                $redirect_url = admin_url('admin.php?page=block-systems-hm-all-block-types');
               // wp_redirect($redirect_url);
                echo '<script>window.location.href = "' . esc_url($redirect_url) . '";</script>';
                exit;
            } else {
                // Ошибка при обновлении данных
                echo '<div class="notice notice-error"><p>' . esc_html__('Failed to update the block type.', 'block-systems-hm') . '</p></div>';
            }
        }

        if ($block_type) {
            // Передача данных типа блока в файл представления
            require_once plugin_dir_path(dirname(__FILE__)) . '/templates/block-types/edit.php';
        } else {
            echo '<div class="notice notice-error"><p>' . esc_html__('Block type not found.', 'block-systems-hm') . '</p></div>';
        }
    }


    public function display_add_block_type_page() {
        require_once plugin_dir_path(dirname(__FILE__)) . 'templates/block-types/create.php';
    }


}