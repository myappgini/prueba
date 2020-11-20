$j(function() {

    load_images(true);

});

function load_images(addFrame = false) {
    if (!is_add_new()) {
        let folder = `images/${AppGini.currentTableName()}/${ selected_id()}`
        let title = $j('#name').val();
        let data = { 
            tn: AppGini.currentTableName(),
            fn: 'uploads',
            id: selected_id(),
            key: "id",
            folder: folder,
            title: title
        }
        if (addFrame) active_upload_frame(data);

        loadImages(data)
    }
}