const Def_Settings = {
    id: selected_id(),
    tn: AppGini.currentTableName(),
    fn: 'uploads',
    cmd: false,
};

const Ajax_Settings = {
    method: "post",
    url: "hooks/multipleUpload/functions-ajax.php",
    dataType: "json"
}

const selectedIx = function (obj) {
    return $j(obj).closest('li.list-group-item-container').attr('data-ix');
}

const ajax = function (data){
    return $j.ajax({
        method: Ajax_Settings.method,
        url: Ajax_Settings.url,
        dataType: Ajax_Settings.dataType,
        data
    });
}

const isTv = function () {
    return $j('.table_view').length > 0 ? true : false;
}

$j('body').on('click', ".modal-media", function (e) {
    const mod = $j('#' + $j(this).data('modal-id'));
    if (!mod.length) return;
    mod.modal();
    resizeModal(mod)
});

$j('body').on('click', ".open-gallery", function (e) {
    data = $j(this).closest("tr").data();
    openGalery(data);
});

$j('body').on('click', '.set-default-media', function (e) {
    const $this = $j(this),
        $currentDefault = $j('li.list-group-item-success'),
        lastix = $currentDefault.data();
    $currentDefault.removeClass('list-group-item-success');
    $currentDefault.find('span.glyphicon-check').addClass('glyphicon-unchecked').removeClass('glyphicon-check');
    $currentDefault.find('button.btn-primary').show();
    let data = $this.closest(".modal-body").data();

    data = $j.extend({}, $this.data(), data)
    if (typeof lastix !== 'undefined') {
        data.lastix = lastix.ix;
    }

    ajax(data).done(function(res){
        const content = $j("li[data-ix='" + res.setIx + "']");
        content.addClass('list-group-item-success');
        content.find('span.glyphicon-unchecked').addClass('glyphicon-check').removeClass('glyphicon-unchecked');
        $this.hide();
    });
})

$j('body').on('click', '.set-default-page', function (e) {
    const ix = selectedIx(this);
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
    ajax(data).done(function(res){
        createPDFThumbnails();
    });

})

$j('body').on('click', ".remove-media", function (e) {
    const ix = selectedIx(this);
    var data = $j(this).closest('.modal-body').data(),
        content = $j("li[data-ix='" + ix + "']");
    content.addClass('disable-content');
    data.ix = ix;
    data.cmd = "del-item"

    ajax(data).done(function(res){
        content.remove();
    });
})

$j('body').on('click', ".edit-title", function (e) {
    var $this = $j(this),
    data = $this.closest('.modal-body').data(),
    div = $this.closest('.box-header').children('.title-media'),
    value = div.attr('data-title'),
    tb = div.find('input:text'); //get textbox, if exist
    
    if (tb.length) { //text box already exist
        const ix = selectedIx(this);
        var newtitle = tb.val();
        div.text(newtitle); //remove text box & put its current value as text to the div
        div.attr('data-title', newtitle);
        $this.children('span').removeClass('glyphicon-ok').addClass('glyphicon-pencil');
        data.cmd = "set-title"
        data.newtitle = newtitle;
        data.ix = ix;
        ajax(data).done(function(res){
            //console.log(res)
        });
    } else {
        $this.children('span').removeClass('glyphicon-pencil').addClass('glyphicon-ok')
        tb = $j('<input>').prop({
            'type': 'text',
            'value': value, //set text box value from div current text
            'style': 'color: #333;'
        });
        div.empty().append(tb); //add new text box
        tb.focus(); //put text box on focus
    }
})

$j('body').on('click', '.img-lite.thumbnail', function () {
    const $this = $j(this);
    const ix = $this.attr('data-ix');
    $this.addClass('active');
    $j(".img-lite").removeClass('active');
    $j('.mySlides').hide();
    $j(".lbid-" + ix).css("display", "block");
});

function loadImages(settings) {
    data = $j.extend({}, Def_Settings, settings);
    data.cmd = "full";

    $j('#imagesThumbs').load(Ajax_Settings.url, data, function () {
        if (!is_add_new()) {
            if (content_type() === 'print-detailview') {
                $j('div.columns-thumbs').hide();
            }
            createPDFThumbnails('.mySliders ');
        }
    });
}

function openGalery(settings) {

    data = $j.extend({}, Def_Settings, settings);
    data.cmd = "gallery";

    $j.ajax({
        method: Ajax_Settings.method,
        dataType: "html",
        url: Ajax_Settings.url,
        data,
        success: function (res) {
            let $modal = $j('#modal-media-gallery');
            $modal.length > 0 && $modal.remove();
            $j('body').append(res);
            $j('#modal-media-gallery').modal('show');
            createPDFThumbnails('.gallery ');

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
        var selector = $j('#' + data.tn + '_dv_action_buttons');
        selector.prepend(' <div id="imagesThumbs"></div>');
        selector.append('<p></p><div id="uploadFrame" class="col-12"></div>');
        $j('#uploadFrame').load('hooks/multipleUpload/_multipleUpload.php', data);
        return true
    }
    return false
}

async function add_button_TV() {
    const resp = await fetch("hooks/multipleUpload/templates/bs3_btnGalleryTv.hbs");
    const btn = await resp.text();
    const $controls = $j('tbody tr td.text-center');
    $controls.each(function () {
        $j(this).append(btn);
    })
}