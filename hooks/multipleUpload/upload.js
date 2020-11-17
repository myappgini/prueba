/* global $j */

async function showTumbs(fn = "uploads") {
    var $obj = $j('.' + AppGini.currentTableName() + '-image');
    $obj.each(function(index) {
        var $this = $j(this);
        var x = $this.data("id"); //id

        $j.ajax({
            type: "POST",
            url: "hooks/multipleUpload/functions-ajax.php",
            data: {
                cmd: 'get_json',
                tn: AppGini.currentTableName(),
                fn,
                where: `"${key}"="${x}"`
            },
            dataType: "json",
            success: function(a) {
                var b = 'full';
                var title = $j('#' + AppGini.currentTableName() + '-' + fn + '-' + x).text();
                if (!isJson(a) || !a) {
                    a = {
                        images: []
                    };
                    b = 'empty';
                } else {
                    a = JSON.parse(a);
                }
                $j.ajax({
                    method: 'POST',
                    dataType: 'html',
                    url: 'hooks/multipleUpload/previewImages.php',
                    cache: 'false',
                    data: {
                        json: a,
                        cmd: b,
                        indice: x,
                        title: title,
                        tableName: AppGini.currentTableName()
                    },
                    success: function(response) {
                        var imgTumb = $j('<div />', {
                            id: 'imagesThumbs-' + x,
                            class: 'thumbs',
                            title: title
                        });
                        imgTumb.html(response);
                        setTimeout(function() {
                            $this.append(imgTumb);
                            showSlides((getDefualtImage(a)), x);
                            if (a.images.length < 2) {
                                $j('#imagesThumbs-' + x + ' div.columns-thumbs').hide();
                            }
                        }, 500);
                    }
                });
            }
        });
    });
}

function get_uploades_json(data = false) {
    const promise = new Promise(function(resolve, reject) {
        if (data) {
            $j.ajax({
                    type: "POST",
                    url: "hooks/multipleUpload/functions-ajax.php",
                    data: data,
                    dataType: "json"
                })
                .done(function(a) {
                    resolve(a);
                })
                .fail(function() {
                    reject(new Error("error on get data from ajax "));
                });

        }
        if (!data) {
            reject(new Error('data needed'));
        }
    });
    return promise;
}

function getDefualtImage(j) {
    var ret = 1;
    for (var key in j.images) {
        if (j.images[key].defaultImage === true) {
            ret = parseInt(key) + 1;
            break;
        }
    }
    return ret;
}

async function loadImages(settings) {

    let def = {
        title: "missing title",
        id: false,
        tn: false,
        fn: 'uploads',
        key: 'id',
        cmd: "full",
        where: `\`${settings.key}\`="${settings.id}"`
    }
    def = $j.extend({}, def, settings);

    //var j = await getUploadedFile(def);
    $j('#imagesThumbs').load('hooks/multipleUpload/UploadsView.php', def, function() {
        if (!is_add_new()) {
            if (content_type() === 'print-detailview') {
                $j('div.columns-thumbs').hide();
            }
        }
    });
}

function currentSlide(n, x) {
    showSlides(slideIndex = n, x);
}

function showPdf(file, title) {
    var visible = 'hidden';
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
                    if (content_type() === 'tableview') {
                        showTumbs();
                    } else {
                        loadImages();
                    }
                    return true;
                },
                causes_closing: true //el valor indica que cuando hace click se cierra la ventana.
            }
        ]
    });
}

async function setPdfThumb(i, tv) {
    //move to top of iden selected images o make default
    var a = await getUploadedFile(tv);
    a = a.images[i - 1];
    var new_page = parseInt($j('#numPage').val()) || 0;
    if (new_page > 0) {
        $j.ajax({
            method: "POST",
            dataType: 'html',
            url: 'hooks/multipleUpload/_resampledIMG.php',
            cache: 'false',
            data: {
                cmd: 'newPDF',
                $source: a.name,
                $fileName: a.fileName,
                $ext: a.extension,
                $folder: a.folder_base,
                $page: new_page
            },
            success: function(response) {
                //                       alert(response);
            }
        });
    }
}

function showSlides(n) {
    var slides = $j(".lbid-" + n);
    var dots = $j(".img-lite");
    $j('.mySlides').hide();
    dots.removeClass("active");
    slides.css("display", "block");
    //dots[n].addClass("active");
    dots[n].className += " active";
}

function showMov(file, n, t = 'video/mp4') {

    //    modal_window({
    //        message: '<div align="center"><video controls style="height:auto"><source src="' + file + '" type="' + t + '"></video></div>',
    //        title: n,
    //        size: 'full',
    //        footer: [
    //              {
    //               label: '<i class="glyphicon glyphicon-cloud-download"></i> Download',
    //               bs_class: 'default',
    //               click: function(e){
    //                   //download y new window
    //                    var windowName = "popUp";//$(this).attr("name");
    //                    window.open(file, windowName);
    //                    e.preventDefault();  //stop the browser from following
    //                   },
    //               causes_closing: true
    //               },
    //               {
    //               label: '<i class="glyphicon glyphicon-remove"></i> Close',
    //               bs_class: 'primary',
    //               click: function(){
    //                   return true;
    //                   },
    //               causes_closing: true //el valor indica que cuando hace click se cierra la ventana.
    //               }
    //       ]
    //    });
}

/**
 * Get upLoadedFile from ajax async function
 * @param {string} id record selector id
 * @param {string} tn table name to get record
 * @param {string} fn filename to get data images, default uploads
 * @param {string} key key for selector id to get record, default id
 */
async function getUploadedFile(settings) {

    let def = {
        id: false,
        tn: false,
        fn: false,
        key: false,
        cmd: "get_json",
        where: `\`${settings.key}\`="${settings.id}"`
    }
    def = $j.extend({}, def, settings);
    var a = await get_uploades_json(def);

    if (!a || !isJson(a)) {
        a = {
            images: []
        };
    } else {
        a = JSON.parse(a);
    }
    return a;
}

async function openOtherFiles(id) {
    var a = await getUploadedFile(id);
    var largo = $j('#' + AppGini.currentTableName + '-codigoCompleto-' + id).text();
    $j.ajax({
            method: "POST",
            dataType: "text",
            url: 'hooks/multipleUpload/previewImages.php',
            data: {
                json: a,
                cmd: 'buttons',
                indice: id,
                largo: largo.length,
                tableName: AppGini.currentTableName
            }
        })
        .done(function(msg) {
            modal_window({
                message: msg,
                title: 'Arquivos',
                footer: [{
                    label: '<i class="glyphicon glyphicon-remove"></i> Close',
                    bs_class: 'primary',
                    click: function() {
                        return true;
                    },
                    causes_closing: true //el valor indica que cuando hace click se cierra la ventana.
                }]
            });
        });

}

function array_move(arr, old_index, new_index) {
    if (new_index >= arr.images.length) {
        var k = new_index - arr.images.length + 1;
        while (k--) {
            arr.images.push(undefined);
        }
    }
    arr.images.splice(new_index, 0, arr.images.splice(old_index, 1)[0]);
    //    return arr; // for testing
    $j('#uploadedFiles').val(returnJsonstr(arr));
}

function returnJsonstr(a) {
    a = JSON.stringify(a);
    a = a.replace(/\\/g, '');
    a = a.replace(/\["/g, '[');
    a = a.replace(/\"]/g, ']');
    a = a.replace(/\},"/g, '},');
    return a;
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
            $j('body').append(msg);
            $j('#images-modal').modal('show')
        });

}

function save_button(tn, id) {

    alert('save_button '+tn);
 
}


//tn, table name
//folder source, defualt "images"
//fn field name, default "uploads"
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
        $j('#uploadFrame').load('hooks/multipleUpload/multipleUpload.php', def);
    }
}