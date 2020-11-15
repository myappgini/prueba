$j(function() {
    //removeEmpty();

    var $row = $j('tbody tr');
    $row.each(function() {
        var $this = $j(this);
        var id = $this.data('id');
        $this.children('td.row').children('div:nth-child(2)').append('<div class="' + AppGini.currentTableName() + '-image" data-id="' + id + '" ></div>');
    });

    showTumbs('item');

    $j('.dl-horizontal').addClass('row');
    $j('dt').addClass('col-3');
    $j('dd').addClass('col-9').removeClass('text-right');
});