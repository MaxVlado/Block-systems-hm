(function($) {
    $(document).ready(function() {
        var blockTypeSelect = $('#block_type_id');
        var blockFieldsContainer = $('#block-fields-container');

        blockTypeSelect.on('change', function() {
            var selectedBlockTypeId = $(this).val();
            if (selectedBlockTypeId) {
                $.ajax({
                    url: ajaxurl,
                    method: 'GET',
                    data: {
                        action: 'get_block_type_fields',
                        block_type_id: selectedBlockTypeId
                    },
                    success: function(response) {
                        blockFieldsContainer.html(response);
                    }
                });
            } else {
                blockFieldsContainer.empty();
            }
        });
    });
})(jQuery);
