<?php
/**
 * Class Block_Systems_HM_Block_Anchor_Model
 */
class Block_Systems_HM_Block_Anchor_Model {

   public  function insertAnchor($anchors,$block_id){
       global $wpdb;
       $wpdb->delete("{$wpdb->prefix}block_anchors", array('block_id' => $block_id), array('%d'));
       $success = true;
       foreach ($anchors as $anchor) {
           $data = array(
               'block_id' => $block_id,
               'tag' => sanitize_text_field($anchor['tag']),
               'position' => intval($anchor['position']),
               'priority' => intval($anchor['priority']),
               'flag' => sanitize_text_field($anchor['flag']),
           );

           if ($wpdb->insert("{$wpdb->prefix}block_anchors", $data) === false) {
               $success = false;
               break;
           }
       }
       return $success;
   }

    public function create_ancors($anchors,$block_id) {
        global $wpdb;

        $success = true;
        foreach ($anchors as $anchor) {
            $data = array(
                'block_id' => $block_id,
                'tag' => sanitize_text_field($anchor['tag']),
                'position' => intval($anchor['position']),
                'priority' => intval($anchor['priority']),
                'flag' => sanitize_text_field($anchor['flag']),
            );

            if ($wpdb->insert("{$wpdb->prefix}block_anchors", $data) === false) {
                $success = false;
                break;
            }
        }
        return $success;
    }

    public function get_all_anchors() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'block_anchors';

        $query = "SELECT * FROM $table_name";
        $anchors = $wpdb->get_results($query);

        return $anchors;
    }

    public function get_anchor($block_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'block_anchors';

        $query = $wpdb->prepare("SELECT * FROM $table_name WHERE block_id = %d", $block_id);
        return $wpdb->get_results($query);
    }
}
