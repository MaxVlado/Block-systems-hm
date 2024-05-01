<h3>Select rubrics and tags </h3>
<div class="select-rubrics-tags">
    <div id="rubrics-block">
        <h4>Rubrics</h4>
        <?php
        $selectedRubrics =isset($block) ? explode(',', $block->selected_rubrics) : [];
        $rubrics = get_terms(array(
            'taxonomy' => 'category',
            'hide_empty' => false,
        ));
        echo '<ul>';
        foreach ($rubrics as $rubric) {
            $checked = in_array($rubric->term_id, $selectedRubrics) ? 'checked' : '';
            echo '<li>';
            echo '<label>';
            echo '<input type="checkbox" name="selected_rubrics[]" value="' . $rubric->term_id . '" ' . $checked . '>';
            echo $rubric->name;
            echo '</label>';
            echo '</li>';
        }
        echo '</ul>';
        ?>
    </div>
    <div id="tags-block">
        <h4>Tags</h4>
        <?php
        $selectedTags = isset($block) ? explode(',', $block->selected_tags) : [];
        $tags = get_terms(array(
            'taxonomy' => 'post_tag',
            'hide_empty' => false,
        ));
        echo '<ul>';
        foreach ($tags as $tag) {
            $checked = in_array($tag->term_id, $selectedTags) ? 'checked' : '';
            echo '<li>';
            echo '<label>';
            echo '<input type="checkbox" name="selected_tags[]" value="' . $tag->term_id . '" ' . $checked . '>';
            echo $tag->name;
            echo '</label>';
            echo '</li>';
        }
        echo '</ul>';
        ?>
    </div>
</div>