openMedia = (i) => {
    $j('body form').one('click', ".modal-media", function (e) {
        e.preventDefault();
        let mod = $j('#' + $j(this).data('modal-id'));
        if (!mod.length) return;
        mod.modal();
        resizeModal(mod)
    });
};

function resizeModal(mod) {
    mod.on('shown.bs.modal', function () {
        var wh = $j(window).height(),
            mhfoh = mod.find('.modal-header').outerHeight() + mod.find('.modal-footer').outerHeight();
        let val = wh - mhfoh - 80;
        mod.find('.modal-body').css({
            height: val
        });
    })
}

setDefault = (i) => {
    $j('body form').one('click', '.set-default-media', function (e) {
        e.preventDefault();
        let $this = $j(this);
        let lastix = $j('li.list-group-item-success').data();
        let data = $this.closest(".modal-body").data();

        data = $j.extend({}, $this.data(), data)
        if (typeof lastix !== 'undefined') {
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
                gallery.on('hidden.bs.modal', function () {
                    load_images(false);
                    openGalery({
                        fn: data.fn
                    });
                });
            }
        });
    })
}

setDefaulPage = (ix, max) => {
    $j('body form').one('click', '.set-default-page', function (e) {
        $j(this).closest('.input-group').removeClass('has-error');
        max = getNumbers(max);
        page = $j('#paganumber' + ix).val();
        if (page < 1 || page > max[0] || max[0] < 1) {
            $j(this).closest('.input-group').addClass('has-error')
            return;
        }
        console.log(max[0], page);

    })
}

removeMedia = (ix) => {
    $j('body form').one('click', ".remove-media", function (e) {
        e.preventDefault();
        alert('remove' + ix)

    })
}

$j(document).on({
    'show.bs.modal': function () {
        var $mod = $j(this);
        var zIndex = 1040 + (10 * $j('.modal:visible').length);
        console.log(zIndex);
        $mod.css('z-index', zIndex);
        setTimeout(function () {
            $j('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    },
    'hidden.bs.modal': function () {
        if ($j('.modal:visible').length > 0) {
            // restore the modal-open class to the body element, so that scrolling works
            // properly after de-stacking a modal.
            $j(this).modal('handleUpdate');
            setTimeout(function () {
                $j(document.body).addClass('modal-open');
            }, 0);
        }
    }
}, '.modal');

function loadImages(settings) {
    let def = {
        id: false,
        tn: false,
        fn: 'uploads',
        cmd: "full"
    }
    def = $j.extend({}, def, settings);
    $j('#imagesThumbs').load('hooks/multipleUpload/UploadsView.php', def, function () {
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
        .done(function (msg) {
            let $modal = $j('#modal-media-gallery');
            if ($modal.length > 0) {
                $modal.remove();
            }
            $j('#imagesThumbs').append(msg);
            $j('#modal-media-gallery').modal('show')
        });
}

function save_button(tn, id) {

    alert('save_button ' + tn);

}

/**
 * add a upload frame in dv
 * 
 * @param {object} settings - user seting from calling.
 *   need 
 *   tn (tableName) neceesarry, 
 *   fn (fieldName), defualt uploads if the user make in your table afiled asis
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


function makeThumb(page) {
    // draw page to fit into 96x96 canvas
    var vp = page.getViewport(1);
    var canvas = document.createElement("canvas");
    canvas.width = canvas.height = 96;
    var scale = Math.min(canvas.width / vp.width, canvas.height / vp.height);
    return page.render({
        canvasContext: canvas.getContext("2d"),
        viewport: page.getViewport(scale)
    }).promise.then(function () {
        return canvas;
    });
}

pdfjsLib.getDocument("https://raw.githubusercontent.com/mozilla/pdf.js/ba2edeae/web/compressed.tracemonkey-pldi-09.pdf").promise.then(function (doc) {
    var pages = [];
    while (pages.length < doc.numPages) pages.push(pages.length + 1);
    return Promise.all(pages.map(function (num) {
        // create a div for each page and build a small canvas for it
        var div = document.createElement("div");
        document.body.appendChild(div);
        return doc.getPage(num).then(makeThumb)
            .then(function (canvas) {
                div.appendChild(canvas);
            });
    }));
}).catch(console.error);