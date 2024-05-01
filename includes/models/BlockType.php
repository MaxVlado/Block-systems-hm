<?php
/**
 * Class Block_Systems_HM_Block_Type_Model
 */
class Block_Systems_HM_Block_Type_Model {
    /**
     * Get all block types.
     *
     * @return array Array of block type objects.
     */
    public function get_all_block_types() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'block_types';

        return $wpdb->get_results("SELECT * FROM $table_name");
    }

    /**
     * Create a new block type.
     *
     * @param string $name Block type name.
     * @return bool|int True on success, false on failure.
     */
    public function create_block_type($data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'block_types';



        $result = $wpdb->insert($table_name, $data);

        if ($result) {
            return $wpdb->insert_id;
        } else {
            return false;
        }
    }

    /**
     * Get a block type by ID.
     *
     * @param int $block_type_id Block type ID.
     * @return object|null Block type object or null if not found.
     */
    public function get_block_type($block_type_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'block_types';

        return  $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $block_type_id));
    }

    /**
     * Update a block type.
     *
     * @param int $block_type_id Block type ID.
     * @param string $name Block type name.
     * @return bool True on success, false on failure.
     */
    public function update_block_type($block_type_id, $data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'block_types';

        $result = $wpdb->update($table_name, $data, array('id' => $block_type_id));

        return $result !== false;
    }

    /**
     * Delete a block type by ID.
     *
     * @param int $block_type_id Block type ID.
     * @return bool True on success, false on failure.
     */
    public function delete_block_type($block_type_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'block_types';

        $result = $wpdb->delete($table_name, array('id' => $block_type_id));

        return $result !== false;
    }
}