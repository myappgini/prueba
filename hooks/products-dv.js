$j(function() {
    if (!is_add_new()) {
        let folder = `images/${thisTable()}/${ selected_id()}`
        active_upload_frame(thisTable(), folder);
        loadImages($j('#titulo').val(), selected_id())
    }

});

//tn, table name
//folder source, defualt "images"
//fn field name, default "uploads"
function active_upload_frame(tn = false, folder = 'images', fn = 'uploads') {
    if (tn) {
        var $actionButtons = $j('#' + tn + '_dv_action_buttons');
        $actionButtons.prepend(' <div id="imagesThumbs"></div>');
        $actionButtons.append('<p></p><div id="uploadFrame" class="col-12"></div>');
        $j('#uploadFrame').load('hooks/multipleUpload/multipleUpload.php', {
            f: `${folder}`
        });
    }
}