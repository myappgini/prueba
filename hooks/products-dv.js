$j(function () {

    load_images(true);
    
    //$j('#products_dv_form').append(add_card("hello world"));

});

function load_images(addFrame = false) {
    if (!is_add_new()) {
        let data = {
            tn: AppGini.currentTableName(),
            fn: 'uploads',
            id: selected_id(),
        }
        if (addFrame) active_upload_frame(data);

        loadImages(data)
    }
}