(function($) {
    $(document).ready(function() {
        $('input[name="place"]').on('change', function() {
            var selectedPlace = $(this).val();
            if (selectedPlace === 'all-page' || selectedPlace === 'custom') {
                $('#anchor-option').show();
            }else {
                $('#anchor-option').hide();
            }
            if (selectedPlace === 'custom') {
                $('.filter-option').show();
                getPostsList(1); // Загрузка записей для первой страницы
            } else {
                $('.filter-option').hide();
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

        // Обработка пагинации
        $(document).on('click', '#pagination-container a', function(e) {
            e.preventDefault();
            var page = $(this).data('page');
            getPostsList(page);
        });

        function getPostsList(page) {
            var filterName = $('#filter-name').val();
            var filterUrl = $('#filter-url').val();
            var filterID = $('#filter-id').val();
            var filterDate = $('#filter-date').val();
            var filterTag = $('#filter-tag').val();
            var filterChecked = $('#filter-checked').val();
            var sortOrder = $('#sort-order').val();
            var blockId = blockSystemsData.blockId;

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
                    filter_id: filterID,
                    filter_date: filterDate,
                    filter_tag: filterTag,
                    filter_checked: filterChecked,
                    selected_posts: selectedPosts,
                    sort_order: sortOrder,
                    paged: page
                },
                success: function(response) {
                   // console.log(response.data.html)
                    var parsedHTML = $.parseHTML(response.data.html);
                    $('#list-posts').html('');
                    $('#list-posts').html(parsedHTML);

                    var totalPosts = response.data.total_posts;
                    var postsPerPage = 10;
                    var totalPages = Math.ceil(totalPosts / postsPerPage);

                    generatePagination(page, totalPages);
                    // Показать посты для текущей страницы
                    showPostsForPage(page);
                    // Удалите предыдущие обработчики событий
                    $('.filter-field, .sort-field').off('change');

                    // Добавьте новые обработчики событий
                    $('.filter-field, .sort-field').on('change', function() {
                        getPostsList(1);
                    });
                }
            });
        }

        function generatePagination(currentPage, totalPages) {
            var paginationHtml = '';
            var maxVisibleButtons = 10; // Максимальное количество видимых кнопок

            if (totalPages > 1) {
                // Добавляем кнопку "Предыдущая"
                if (currentPage > 1) {
                    paginationHtml += '<a href="#" data-page="' + (currentPage - 1) + '">Предыдущая</a>';
                }

                // Отображаем первую страницу
                paginationHtml += '<a href="#" data-page="1"' + (currentPage === 1 ? ' class="current"' : '') + '>1</a>';

                // Отображаем многоточие перед группой кнопок
                if (currentPage > Math.floor(maxVisibleButtons / 2) + 1) {
                    paginationHtml += '<span class="ellipsis">...</span>';
                }

                // Определяем начальную и конечную страницы для отображения
                var startPage = Math.max(2, currentPage - Math.floor(maxVisibleButtons / 2));
                var endPage = Math.min(totalPages - 1, currentPage + Math.floor(maxVisibleButtons / 2));

                // Корректируем начальную и конечную страницы, если они выходят за пределы допустимого диапазона
                if (endPage - startPage + 1 < maxVisibleButtons) {
                    if (currentPage < totalPages - Math.floor(maxVisibleButtons / 2)) {
                        endPage = Math.min(totalPages - 1, startPage + maxVisibleButtons - 2);
                    } else {
                        startPage = Math.max(2, endPage - maxVisibleButtons + 2);
                    }
                }

                // Отображаем кнопки пагинации
                for (var i = startPage; i <= endPage; i++) {
                    paginationHtml += '<a href="#" data-page="' + i + '"' + (i === currentPage ? ' class="current"' : '') + '>' + i + '</a>';
                }

                // Отображаем многоточие после группы кнопок
                if (currentPage < totalPages - Math.floor(maxVisibleButtons / 2)) {
                    paginationHtml += '<span class="ellipsis">...</span>';
                }

                // Отображаем последнюю страницу
                paginationHtml += '<a href="#" data-page="' + totalPages + '"' + (currentPage === totalPages ? ' class="current"' : '') + '>' + totalPages + '</a>';

                // Добавляем кнопку "Следующая"
                if (currentPage < totalPages) {
                    paginationHtml += '<a href="#" data-page="' + (currentPage + 1) + '">Следующая</a>';
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

        // Инициализация списка постов при загрузке страницы
        var initialPlace = $('input[name="place"]:checked').val();

        if (initialPlace === 'all-page' || initialPlace === 'custom') {
            $('#anchor-option').show();
        }else {
            $('#anchor-option').hide();
        }

        if (initialPlace === 'custom') {
            $('.filter-option').show();
            getPostsList(1); // Загрузка записей для первой страницы
        } else {
            $('.filter-option').hide();
        }
    });
})(jQuery);