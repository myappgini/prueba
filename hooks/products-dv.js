$j(function () {

    load_images(true);
    
    //$j('#products_dv_form').append(add_card("hello world"));

    //full example settings
    var b = {
        class: "btn btn-success",
        id: "mi-boton",
        name: "sales",
        value: "sales",
        type: "submit",
        onclick: "",
        title: "Click to view sales",
        text: "Sales ",
    };

    add_action_button(b);

    //remove_text('button');


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