/* global $j */

openMedia = (i)=>{
    $j('body form').on('click',".modal-media", function(e) {
        e.preventDefault();
        let mod = $j('#' + $j(this).data('modal-id'));
        if(!mod.length) return;
        mod.modal();
        mod.on('shown.bs.modal', function(){
            var wh = $j(window).height(),
            mhfoh = mod.find('.modal-header').outerHeight() + mod.find('.modal-footer').outerHeight();
            let val = wh - mhfoh - 80;
            mod.find('.modal-body').css({
                height: val
            });
        })
    });
};

setDefault = (i)=>{
    $j('body form').one('click','.set-default-image', function(e){
        e.preventDefault();
        let $this = $j(this);
        let lastix = $j('li.list-group-item-success').data();
        let data = $this.closest(".modal-body").data();
        
        data = $j.extend({},$this.data(), data)
        if ( typeof lastix !== 'undefined'){
            data.lastix = lastix.ix;
        }
        $j.ajax({
            type: "post",
            url: "hooks/multipleUpload/functions-ajax.php",
            data: data,
            dataType: "json",
            success: function (res) {
                let gallery = $j('#modal-media-gallery');
                gallery.modal('hide');
                gallery.on('hidden.bs.modal', function() {
                    openGalery({fn:data.fn});
                });
            }
        });
    })
}

$j(document).on('show.bs.modal', '.modal', function () {
    var zIndex = 1040 + (10 * $j('.modal:visible').length);
    $j(this).css('z-index', zIndex);
    setTimeout(function() {
        $j('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
    }, 0);
});

function loadImages(settings) {
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

//open galery, open modal form
function openGalery(settings) {

    let def = {
        cmd: "gallery",
        tn: AppGini.currentTableName(),
        id: selected_id(),
        fn: "uploads"
    }
    def = $j.extend({}, def, settings);

    $j.ajax({
            method: "POST",
            dataType: "text",
            url: 'hooks/multipleUpload/UploadsView.php',
            data: def
        })
        .done(function(msg) {
            let $modal = $j('#modal-media-gallery');
            if( $modal.length > 0) {
                $modal.remove();
            }
            $j('body form').append(msg);
            $j('#modal-media-gallery').modal('show')
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