<?php
/**
 * Class Block_Systems_HM_Template_Model
 */
class Block_Systems_HM_Template_Model {

    public function getBlockSystemsHMTemplate($block,$block_type_id){
        $block_type_model = new Block_Systems_HM_Block_Type_Model();
        $block_type = $block_type_model->get_block_type($block_type_id);
        if ($block_type->name === 'html_1') return $this->html_1($block);
        if ($block_type->name === 'banner_1') return $this->banner_1($block);
        if ($block_type->name === 'image_1') return $this->image_1($block);
    }

    private function html_1($block){
        $output = '';
        if (!empty($block->code)) {
            $code = stripslashes($block->code);
            $output = <<<HTML
<div id="{$block->shortcode}" class="block_systems_hn">
    {$code}
</div>
HTML;
        }
        return $output;
    }

    private function banner_1($block){
        $output = '';
        $result = maybe_unserialize($block->current_fields);
        if (!empty($result) && is_array($result)) {
            $output = <<<HTML
<a href="{$result['url']}" id="{$block->shortcode}" class="block_systems_hn">
    <div id="text_1">{$result['text_1']}</div>
    <div id="wr-text_2">
        <div id="text_2">{$result['text_2']}</div>
    </div>
</a>
HTML;
        }
        return $output;
    }

    private function image_1($block){
        $output = '';
        $result = maybe_unserialize($block->current_fields);
        if (!empty($result) && is_array($result)) {
            $output = <<<HTML
<a href="{$result['href']}">
<img class="wp-image-25601 size-full aligncenter" src="{$result['src']}" alt="{$result['alt']}" width="1080" height="1080">
</a>
<p style="text-align: center;">
<span class="wpbcb-block wpbcb-block--check">👉
<a href="{$result['href']}" target="_blank" rel="noopener">{$result['text']}</a>👈
</span>
</p>
HTML;

            return $output;
        }
    }

}