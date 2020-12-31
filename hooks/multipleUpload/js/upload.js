const Def_Settings = {
    id: selected_id(),
    tn: AppGini.currentTableName(),
    fn: 'uploads',
    cmd: false,
};

const setting_ajax = {
    method: "post",
    url: "hooks/multipleUpload/functions-ajax.php",
    dataType: "json"
}

openMedia = (i) => {
    $j('body form').one('click', ".modal-media", function (e) {
        e.preventDefault();
        let mod = $j('#' + $j(this).data('modal-id'));
        if (!mod.length) return;
        mod.modal();
        resizeModal(mod)
    });
};

setDefault = (i) => {
    $j('body form').one('click', '.set-default-media', function (e) {
        let $this = $j(this);
        let lastix = $j('li.list-group-item-success').data();
        let data = $this.closest(".modal-body").data();

        data = $j.extend({}, $this.data(), data)
        if (typeof lastix !== 'undefined') {
            data.lastix = lastix.ix;
        }
        $j.ajax({
            method: setting_ajax.method,
            url: setting_ajax.url,
            dataType: setting_ajax.dataType,
            data,
            success: function (res) {
                refresh_gallery(data.fn);
            }
        });
    })
}

setDefaultPage = (ix) => {
    $j('body form').one('click', '.set-default-page', function (e) {
        var $this = $j(this),
            $group = $this.closest('.input-group'),
            $input = $j('#pdf-page-' + ix),
            max = parseInt($input.attr('data-max-page')) || 1,
            page = parseInt($input.val()) || 0,
            data = $this.closest(".modal-body").data();
        data = $j.extend({}, $this.data(), data)
        $group.removeClass('has-error');
        if (page < 1 || page > max || max < 1) {
            $group.addClass('has-error')
            return;
        }
        $j(".img-pdf-" + ix).attr("data-pdfPage", page);
        data.page = page;

        $j.ajax({
            method: setting_ajax.method,
            url: setting_ajax.url,
            dataType: setting_ajax.dataType,
            data,
            success: function (res) {
                createPDFThumbnails();
            }
        });
    })
}

removeMedia = (ix) => {
    $j('body form').one('click', ".remove-media", function (e) {
        var data = $j(this).closest('.modal-body').data();
        data.ix = ix;
        data.cmd = "del_json"

        $j.ajax({
            method: setting_ajax.method,
            url: setting_ajax.url,
            dataType: setting_ajax.dataType,
            data,
            success: function (res) {
                console.log(res)
                refresh_gallery(data.fn);
            }
        });
    })
}

$j(document).on({
    'show.bs.modal': function () {
        var zIndex = 1040 + (10 * $j('.modal:visible').length);
        $j(this).css('z-index', zIndex);
        setTimeout(function () {
            $j('.modal-backdrop').not('.modal-stack').css('z-index', zIndex - 1).addClass('modal-stack');
        }, 0);
    },
    'hidden.bs.modal': function () {
        if ($j('.modal:visible').length > 0) {
            $j(this).modal('handleUpdate');
            setTimeout(function () {
                $j(document.body).addClass('modal-open');
            }, 0);
        }
    }
}, '.modal');

function refresh_gallery(fn) {
    let gallery = $j('#modal-media-gallery');
    gallery.modal('hide');
    gallery.on('hidden.bs.modal', function () {
        load_images(false);
        openGalery({
            fn
        });
    });
}

function resizeModal(mod) {
    mod.on('shown.bs.modal', function () {
        var wh = $j(window).height(),
            mhfoh = mod.find('.modal-header').outerHeight() + mod.find('.modal-footer').outerHeight(),
            val = wh - mhfoh - 80;
        mod.find('.modal-body').css({
            height: val
        });
    })
}

function loadImages(settings) {
    data = $j.extend({}, Def_Settings, settings);
    data.cmd = "full";

    $j('#imagesThumbs').load(setting_ajax.url, data, function (res) {
        if (res) {
            if (!is_add_new()) {
                if (content_type() === 'print-detailview') {
                    $j('div.columns-thumbs').hide();
                }
                createPDFThumbnails('.mySliders ');
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

    data = $j.extend({}, Def_Settings, settings);
    data.cmd = "gallery";

    $j.ajax({
        method: setting_ajax.method,
        dataType: "html",
        url: setting_ajax.url,
        data,
        success: function (res) {
            if (!res.error) {
                let $modal = $j('#modal-media-gallery');
                if ($modal.length > 0) {
                    $modal.remove();
                }
                $j('#imagesThumbs').append(res);
                $j('#modal-media-gallery').modal('show')
                createPDFThumbnails('.gallery ');
            }
        }
    });
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

    data = $j.extend({}, Def_Settings, settings);

    if (data.tn) {
        var $actionButtons = $j('#' + data.tn + '_dv_action_buttons');
        $actionButtons.prepend(' <div id="imagesThumbs"></div>');
        $actionButtons.append('<p></p><div id="uploadFrame" class="col-12"></div>');
        $j('#uploadFrame').load('hooks/multipleUpload/_multipleUpload.php', data);
        return true
    }
    return false
}