/* global $j */

openVideo = (i)=>{
    $j('body form').on('click',".launch-modal", function(e) {
        e.preventDefault();
        $j('#' + $j(this).data('modal-id')).modal();
    });
    $j('.close').click(function() {
        $j('#video-mp4-' + i).trigger('load');
    });
};

async function loadImages(settings) {

    let def = {
        id: false,
        tn: false,
        fn: 'uploads',
        cmd: "full"
    }
    def = $j.extend({}, def, settings);

    $j('#imagesThumbs').load('hooks/multipleUpload/UploadsView.php', def, function() {
        if (!is_add_new()) {
            if (content_type() === 'print-detailview') {
                $j('div.columns-thumbs').hide();
            }
        }
    });
}

function currentSlide(n) {
    var slides = $j(".lbid-" + n);
    var dots = $j(".img-lite");
    $j('.mySlides').hide();
    dots.removeClass("active");
    slides.css("display", "block");
    dots[n].className += " active";
}

function showPdf(file, title) {
    var msg = '<div style ="height:100%;"><embed src="' + file + '#view=Fit"  width="100%" height="100%" style="z-index: 2;"></div>';
    modal_window({
        message: msg,
        title: title,
        size: 'full',
        footer: [{
                label: '<i class="glyphicon glyphicon-cloud-download"></i> Download',
                bs_class: 'default',
                click: function(e) {
                    var windowName = "popUp"; //$(this).attr("name");
                    window.open(file, windowName);
                    e.preventDefault(); //stop the browser from following
                },
                causes_closing: true
            },
            {
                label: '<i class="glyphicon glyphicon-remove"></i> Close',
                bs_class: 'primary',
                click: function() {
                    return true;
                },
                causes_closing: true //el valor indica que cuando hace click se cierra la ventana.
            }
        ]
    });
}


//open galery, open modal form
async function openGalery(btn) {

    let data = {
        cmd: "form",
        tn: AppGini.currentTableName(),
        id: selected_id(),
        fn: "uploads"
    }

    $j.ajax({
            method: "POST",
            dataType: "text",
            url: 'hooks/multipleUpload/UploadsView.php',
            data: data
        })
        .done(function(msg) {
            let $modal = $j('#images-modal');
            if( $modal.length > 0) {
                $modal.remove();
            }
            $j('body form').append(msg);
            $j('#images-modal').modal('show')
        });

}

function save_button(tn, id) {

    alert('save_button '+tn);
 
}

/**
* add a upload frame in dv
* 
* @param {object} settings - user seting from calling.
*   need 
*   tn (tableName) neceesarry, 
*   fn (fieldName), defualt uploads if the user make in your table afiled asis
*   folder name destiny, by default is images
* @return {bollean} - true is everithink ok, otherwise false
* 
**/
function active_upload_frame(settings) {

    let def = {
        tn: false,
        fn: 'uploads',
        folder: 'iamges',
    }
    def = $j.extend({}, def, settings);

    if (def.tn) {
        var $actionButtons = $j('#' + def.tn + '_dv_action_buttons');
        $actionButtons.prepend(' <div id="imagesThumbs"></div>');
        $actionButtons.append('<p></p><div id="uploadFrame" class="col-12"></div>');
        $j('#uploadFrame').load('hooks/multipleUpload/_multipleUpload.php', def);
        return true
    }
    return false
}