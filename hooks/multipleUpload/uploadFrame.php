<?php
$currDir = dirname(__FILE__);
if (!isset($data['tn']))  die("You can't call this file directly."); //from functions-ajax.php

if (!function_exists('makeSafe')) include realpath("{$currDir}/../..")."/lib.php";

$url = "hooks/multipleUpload/functions-ajax.php?tn={$data['tn']}&fn={$data['fn']}&id={$data['id']}&cmd=uploading";

echo '<!-- dropzone control multipleupload -->';
?>
<div class="dz-container">
    <div id="my-awesome-dropzone" class="dropzone">
        <i class="glyphicon glyphicon-upload"></i>
        <div id="imagesThumbs" class="col-lg-12"></div>
        <button class="btn btn-info col-xs-12" type="button" onclick="openGalery({fn:'<?php echo $data['fn']; ?>'});">Open Gallery</button>
    </div>
    <div id="response" class="alert alert-dismissible" role="alert">
        <button class="close" type="button" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
        <div class="alertmsg"></div>
    </div>
</div>
<script>
    var ext = '<?php echo implode("|", $mu->extensions); ?>';
    ext = ext.replace(/\|/g, ",.");
    $j("div#my-awesome-dropzone").dropzone({
        paramName: "uploadedFile", // The name that will be used to transfer the file
        maxFilesize: 200048,
        url: '<?php echo $url; ?>',
        acceptedFiles: ext,
        uploadMultiple: false,
        accept: function(file, done) {
            done();
        },
        init: function() {
            this.on("success", function(file, response) {
                if (file.status === "success") {
                    var res = "<strong> Upload OK!!!</strong>" + (response.isRenamed ? "<br>File name exist, new name: " + response.fileName + "." : response.fileName);
                    alertmesg(res, 'alert-success',file,this)
                }
            });
            this.on("queuecomplete", function(file, reponse) {
                //* refresh container images
                load_images(false);
                setTimeout(() => {
                    createPDFThumbnails();
                }, 500);
            });
            this.on("error", function(file, response) {
                res = $j.type(response) === "string" ? "Error: " + response : response['error']; //dropzone sends it's own error messages in string
                alertmesg(res, 'alert-danger',file,this)
                $j(".dropzone").css("border", "3px dotted red");
            });
        }
    })

    function deleteFile(file, elm) {
        elm.removeFile(file);
    }
    
    function alertmesg(res, color,file,elm) {
        $j("#response .alertmsg").append(res);
        $j("#response.alert").addClass(color).fadeOut(3500)
        setTimeout(deleteFile, 3500, file, elm);
    }
</script>