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

    const data = {
        "id":"sales",
        "class":"bg-green", //can you add more classes or change de color
        "icon_class":"bg-aqua",
        "icon":"glyphicon glyphicon-cog",
        "text":"Sales",
        "description":"75% Increase in 30 Days ",
        "value_progress":"75",
        "value":"2500",
    }
    $j('fieldset').append('<div class="-widgets- col-sm-12"></div>')
    widget("info-box",data).then(function(res){
        $j('.-widgets-').append(res.html);
    });
    widget("small-box",data).then(function(res){
        $j('.-widgets-').append(res.html);
    });
    
    


});

function load_images(addFrame = false) {
    if (!is_add_new()) {
        let data = {
            tn: AppGini.currentTableName(),
            fn: 'uploads', //change this value if use other field name
            id: selected_id(),
        }
        if (addFrame) active_upload_frame(data);

        loadImages(data)
    }
}