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

        $('input[name="place"]').on('change', function() {
            var selectedPlace = $(this).val();
            if (selectedPlace === 'custom') {
                $('#custom-options').show();
                getPostsList(1);
            } else {
                $('#custom-options').hide();
            }
        });

        $("#filter-date").datepicker({
            dateFormat: "yy-mm-dd",
            onSelect: function(dateText, inst) {
                getPostsList(1);
            }
        });

        $("#reset-date-filter").on("click", function() {
            $("#filter-date").val("");
            getPostsList(1);
        });

        $(document).on('click', '#pagination-container a', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            getPostsList(page);
        });

        function getPostsList(page) {
            var filterName = $('#filter-name').val();
            var filterUrl = $('#filter-url').val();
            var filterDate = $('#filter-date').val();
            var filterTag = $('#filter-tag').val();
            var filterChecked = $('#filter-checked').val();
            var sortOrder = $('#sort-order').val();
            var blockId = $('input[name="block_id"]').val();

            var selectedPosts = $('input[name="selected_posts[]"]:checked').map(function() {
                return $(this).val();
            }).get();

            $.ajax({
                url: ajaxurl,
                method: 'GET',
                data: {
                    action: 'get_posts_list',
                    block_id: blockId,
                    filter_name: filterName,
                    filter_url: filterUrl,
                    filter_date: filterDate,
                    filter_tag: filterTag,
                    filter_checked: filterChecked,
                    selected_posts: selectedPosts,
                    sort_order: sortOrder,
                    paged: page
                },
                success: function(response) {
                    $('#list-posts').html(response.data.html);
                    generatePagination(page, response.data.total_pages);
                    showPostsForPage(page);
                    $('.filter-field, .sort-field').off('change');
                    $('.filter-field, .sort-field').on('change', function() {
                        getPostsList(1);
                    });
                }
            });
        }

        function generatePagination(currentPage, totalPages) {
            var paginationHtml = '';
            var maxVisibleButtons = 10;

            if (totalPages > 1) {
                if (currentPage > 1) {
                    paginationHtml += '<a href="#" data-page="' + (currentPage - 1) + '">Previous</a>';
                }

                paginationHtml += '<a href="#" data-page="1"' + (currentPage === 1 ? ' class="current"' : '') + '>1</a>';

                if (currentPage > Math.floor(maxVisibleButtons / 2) + 1) {
                    paginationHtml += '<span class="ellipsis">...</span>';
                }

                var startPage = Math.max(2, currentPage - Math.floor(maxVisibleButtons / 2));
                var endPage = Math.min(totalPages - 1, currentPage + Math.floor(maxVisibleButtons / 2));

                if (endPage - startPage + 1 < maxVisibleButtons) {
                    if (currentPage < totalPages - Math.floor(maxVisibleButtons / 2)) {
                        endPage = Math.min(totalPages - 1, startPage + maxVisibleButtons - 2);
                    } else {
                        startPage = Math.max(2, endPage - maxVisibleButtons + 2);
                    }
                }

                for (var i = startPage; i <= endPage; i++) {
                    paginationHtml += '<a href="#" data-page="' + i + '"' + (i === currentPage ? ' class="current"' : '') + '>' + i + '</a>';
                }

                if (currentPage < totalPages - Math.floor(maxVisibleButtons / 2)) {
                    paginationHtml += '<span class="ellipsis">...</span>';
                }

                paginationHtml += '<a href="#" data-page="' + totalPages + '"' + (currentPage === totalPages ? ' class="current"' : '') + '>' + totalPages + '</a>';

                if (currentPage < totalPages) {
                    paginationHtml += '<a href="#" data-page="' + (currentPage + 1) + '">Next</a>';
                }
            }

            $('#pagination-container').html(paginationHtml);
        }

        function showPostsForPage(page) {
            var postsPerPage = 10;
            var startIndex = (page - 1) * postsPerPage;
            var endIndex = startIndex + postsPerPage;

            $('#posts-table tr').hide();
            $('#posts-table tr').slice(startIndex, endIndex).show();
        }

        var initialPlace = $('input[name="place"]:checked').val();

        if (initialPlace === 'custom') {
            $('#custom-options').show();
            getPostsList(1);
        } else {
            $('#custom-options').hide();
        }
    });
})(jQuery);