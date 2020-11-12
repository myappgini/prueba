$j(function() {
    active_upload_frame(thisTable());
    loadImages($j('#titulo').val(), selected_id())
});

function active_upload_frame(tn = false, fn = 'uploads', f = 'images') {
    if (tn) {
        var $actionButtons = $j('#' + tn + '_dv_action_buttons');
        $actionButtons.prepend(' <div id="imagesThumbs"></div>');
        $actionButtons.append('<p></p><div id="uploadFrame" class="col-12"></div>');
        $j('#uploadFrame').load('hooks/multipleUpload/multipleUpload.php', { f: `/${f}/${tn}` });
    }
}