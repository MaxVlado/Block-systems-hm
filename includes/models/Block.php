<?php
/**
 * Class Block_Systems_HM_Block_Model
 */
class Block_Systems_HM_Block_Model {

    public function get_all_blocks() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'blocks';

        $blocks = $wpdb->get_results("SELECT * FROM $table_name");

        return $blocks;
    }



    public function get_block($block_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'blocks';

        $block = $wpdb->get_row($wpdb->prepare("SELECT * FROM $table_name WHERE id = %d", $block_id));

        return $block;
    }

    public function get_block_by_shortcode($shortcode) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'blocks';

        $block = $wpdb->get_row(
            $wpdb->prepare(
                "SELECT * FROM $table_name WHERE shortcode = %s",
                $shortcode
            )
        );

        return $block;
    }

    public function generate_unique_shortcode($shortcode) {
        $prefix = BLOCK_SYSTEMS_SHORTCODE_PREFIX;

        // Проверяем, начинается ли шорткод с префикса
        if (!str_starts_with($shortcode, $prefix)) {
            $unique_shortcode = $prefix . $shortcode;
        } else {
            $unique_shortcode = $shortcode;
        }

        // Проверка на существование шорткода
        if (shortcode_exists($unique_shortcode)) {
            // Если шорткод уже существует, добавляем или увеличиваем числовой суффикс
            $suffix = 1;
            $parts = explode('_', $unique_shortcode);
            $last_part = end($parts);

            if (is_numeric($last_part)) {
                $suffix = intval($last_part) + 1;
                array_pop($parts);
                $unique_shortcode = implode('_', $parts);
            }

            while (shortcode_exists($unique_shortcode . '_' . $suffix)) {
                $suffix++;
            }

            $unique_shortcode .= '_' . $suffix;
        }

        return $unique_shortcode;
    }
    public function create_block( $data,  ) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'blocks';

        $result = $wpdb->insert($table_name, $data);

        if ($result) {
            return $wpdb->insert_id;
        } else {
            return false;
        }
    }
    public function update_block($block_id, $data) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'blocks';

        $result = $wpdb->update(
            $table_name,
            $data,
            array('id' => $block_id),
            array('%d','%s', '%s', '%s','%s', '%s', '%s',  '%s', '%s', '%s', '%s'), // Форматы данных для значений
            array('%d') // Формат данных для условия WHERE
        );

        return $result !== false;
    }

    public function delete_block($block_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'blocks';

        $result = $wpdb->delete($table_name, array('id' => $block_id));

        return $result !== false;
    }
}