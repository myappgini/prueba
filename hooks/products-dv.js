$j(function() {

    load_images(true);

});

function load_images(addFrame = false) {
    if (!is_add_new()) {
        let folder = `images/${AppGini.currentTableName()}/${ selected_id()}`
        let data = { 
            tn: AppGini.currentTableName(),
            fn: 'uploads',
            id: selected_id(),
            folder: folder,
        }
        if (addFrame) active_upload_frame(data);

        loadImages(data)
    }
}