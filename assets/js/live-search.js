jQuery(document).ready(function ($) {
    var searchInput = $('.dmd-search-form input[name="s"]');
    var resultsContainer = $('#dmd-live-search-results');
    var timer;

    searchInput.on('input', function () {
        clearTimeout(timer);
        var query = $(this).val();

        if (query.length < 3) {
            resultsContainer.empty().hide();
            return;
        }

        timer = setTimeout(function () {
            $.ajax({
                url: dmd_ajax_object.ajax_url,
                type: 'POST',
                data: {
                    action: 'dmd_live_search',
                    query: query,
                    nonce: dmd_ajax_object.nonce
                },
                success: function (response) {
                    if (response.success) {
                        resultsContainer.html(response.data).show();
                    } else {
                        resultsContainer.html('<p class="p-2 text-sm text-muted">' + response.data + '</p>').show();
                    }
                }
            });
        }, 300);
    });

    // Hide when clicking outside
    $(document).click(function (e) {
        if (!resultsContainer.is(e.target) && resultsContainer.has(e.target).length === 0 && !searchInput.is(e.target)) {
            resultsContainer.hide();
        }
    });
});
