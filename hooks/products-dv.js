$j(function() {
    if (!is_add_new()) {

        let folder = `images/${thisTable()}/${ selected_id()}`
        let title = $j('#name').val();
        let data = {
            tn: thisTable(),
            fn: 'uploads',
            id: selected_id(),
            key: "id",
            folder: folder,
            title: title
        }

        active_upload_frame(data);

        loadImages(data)
    }

});