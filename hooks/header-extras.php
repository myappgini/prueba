
<?php /* Inserted by Membership Profile Image for AppGini on 2021-01-17 05:53:12 */ ?>
		<?php include('hooks/mpi/scripts.php');?>
<?php /* End of Membership Profile Image for AppGini code */ ?>

<?php
?>

<script>
/**
 * Construct a selectable drop down list with registered users.
 * @param {string} f - Field name to replace wit drop-down list.
 * @param {string} t - table name destiny.
 */
function users_dropdown(f, t) {
    var $selectField = $j('#' + f + '').hide();
    var $span = $j('<span/>', { id: 's2_users_' + f });
    $selectField.closest('div').append($span);
    var val = $selectField.val();

    $span.select2({
        width: '100%',
        formatNoMatches: function(term) { return 'No matches found!'; },
        minimumResultsForSearch: 5,
        loadMorePadding: 200,
        escapeMarkup: function(m) { return m; },
        ajax: {
            url: 'admin/getUsers.php',
            dataType: 'json',
            cache: true,
            data: function(term, page) { return { s: term, p: page, t: t }; },
            results: function(resp, page) { return resp; }
        }
    }).on('change', function(e) {
        $j('[name="' + f + '"]').val(e.added.id);
    });
    if (val) {
        $span.select2('data', { text: val, id: val });
    }
}
</script>