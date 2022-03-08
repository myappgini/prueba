$j(function () {

    load_images(true);
    
});

function load_images(addFrame = false) {
    if (!is_add_new()) {
        let data = {
            tn: AppGini.currentTableName(),
            fn: 'uploads', //change this value if use other field name
            id: selected_id(),
        }
        if (addFrame) active_upload_frame(data);
        setTimeout(() => {
            loadImages(data)
        }, 300);
    }
}