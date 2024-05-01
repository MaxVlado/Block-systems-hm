<?php
/**
 * Class Block_Systems_HM_Block_Types_Controller
 */
class Block_Systems_HM_Block_Types_Controller {
    /**
     * Handle block type actions.
     */
    public function handle_actions() {


        if (isset($_POST['action'])) {

            $action = sanitize_text_field($_POST['action']);

            switch ($action) {
                case 'add':
                    $this->handle_add_block_type();
                    break;
                default:
                    break;
            }
        } elseif (isset($_GET['action'])) {
            $action = sanitize_text_field($_GET['action']);

            switch ($action) {
                case 'delete':
                    if (isset($_GET['block_type_id'])) {
                        $block_type_id = absint($_GET['block_type_id']);
                        $this->handle_delete_block_type($block_type_id);
                    }
                    break;
                default:
                    break;
            }

        }


    }

    /**
     * Handle the creation of a new block type.
     */
    private function handle_add_block_type() {


        if (isset($_POST['submit_add_block_type'])) {

            $data = array(
                'name' => sanitize_text_field($_POST['name']),
                'fields' => serialize($_POST['fields']),
                'check_css' =>  isset($_POST['check_css']) ? 1 : 0,
                'check_code' =>  isset($_POST['check_code']) ? 1 : 0,
            );

            $block_type_model = new Block_Systems_HM_Block_Type_Model();

            if ($block_type_model->create_block_type($data)) {
                // Block type created successfully
                $redirect_url = add_query_arg(
                    array(
                        'page' => 'block-systems-hm-all-block-types',
                        'message' => 'block_type_added'
                    ),
                    admin_url('admin.php')
                );
                wp_redirect($redirect_url);
                exit;
            } else {
                // Failed to create the block type
                echo '<div class="notice notice-error"><p>' . esc_html__('Failed to create the block type.', 'block-systems-hm') . '</p></div>';
            }
        }

    }

    private function handle_delete_block_type($block_type_id) {
        $block_type_model = new Block_Systems_HM_Block_Type_Model();

        if ($block_type_model->delete_block_type($block_type_id)) {
            // Block type deleted successfully
            $redirect_url = add_query_arg(
                array(
                    'page' => 'block-systems-hm-all-block-types',
                    'message' => 'block_type_deleted'
                ),
                admin_url('admin.php')
            );
            wp_redirect($redirect_url);
            exit;
        } else {
            // Failed to delete the block type
            echo '<div class="notice notice-error"><p>' . esc_html__('Failed to delete the block type.', 'block-systems-hm') . '</p></div>';
        }
    }

    /**
     * Get all block types.
     *
     * @return array Array of block type objects.
     */
    public function get_all_block_types() {
        $block_type_model = new Block_Systems_HM_Block_Type_Model();
        return $block_type_model->get_all_block_types();
    }

    public function get_block_types($block_type_id) {
        $block_type_model = new Block_Systems_HM_Block_Type_Model();
        return $block_type_model->get_block_type($block_type_id);
    }

    public function get_block_type_field($block_type_id, $field_index) {
        $block_type_model = new Block_Systems_HM_Block_Type_Model();
        $block_type = $block_type_model->get_block_type($block_type_id);

        if ($block_type && !empty($block_type->fields)) {
            $fields = maybe_unserialize($block_type->fields);

            if (is_array($fields) && isset($fields[$field_index])) {
                return $fields[$field_index];
            }
        }

        return false;
    }

    public function get_block_type_field_by_name($block_type_id, $field_name) {
        $block_type_model = new Block_Systems_HM_Block_Type_Model();
        $block_type = $block_type_model->get_block_type($block_type_id);

        if ($block_type && !empty($block_type->fields)) {
            $fields = maybe_unserialize($block_type->fields);

            if (is_array($fields)) {
                foreach ($fields as $field) {
                    if ($field['name'] === $field_name) {
                        return $field;
                    }
                }
            }
        }

        return false;
    }

}