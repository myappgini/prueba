<?php
$currDir = dirname(__FILE__);
$base_dir = realpath("{$currDir}/../..");
if (!isset($_REQUEST['tn'])) {
    die("You can't call this file directly.");
}
include $currDir . '/MultipleUpload.php';
if (!function_exists('makeSafe')) {
    include "$base_dir/lib.php";
}

$mu = new MultipleUpload();
$tn = Request::val('tn');
$fn = Request::val('fn');
$id = Request::val('id');

$url = "hooks/multipleUpload/MultipleUpload.php?&tn={$tn}&fn={$fn}&id={$id}&cmd=uploading";

echo '<!-- dropzone control multipleupload -->';
?>
<div class="dz-container">
    <div class="btn-group-vertical btn-group-lg" style="width: 100%;">
        <button class="btn btn-info col-lg-12" type="button" onclick="openGalery({fn:'<?php echo $fn; ?>'});">Open Galary</button>
    </div>
    <p></p>
    <div id="response" class="row"></div>
    <div id="my-awesome-dropzone" class="dropzone">
        <i class="glyphicon glyphicon-upload"></i>
    </div>
</div>
<script>
    // debugger
    var ext = '<?php echo implode("|",$mu->extensions); ?>';
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
                    var dismiss = $j("<button />", {
                        class: "close",
                        type: "button",
                        "data-dismiss": "alert",
                        "aria-label": "Close"
                    }).append('<span aria-hidden="true">&times;</span>')
                    var successMsg = "<strong> Upload OK </strong>"+ (response.isRenamed ? "<br>File name exist, new name: " + response.fileName + "." : response.fileName) ;
                    var successDiv = $j("<div />", {
                        class: "alert alert-success alert-dismissible",
                        role: "alert"
                    }).append(successMsg).append(dismiss);

                    $j("#response").append(successDiv);
                    successDiv.fadeOut(60000); //close after 1 minute.
                    setTimeout(deleteFile, 2500, file, this);
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
                if ($j.type(response) === "string") {
                    response = "Error: " + response; //dropzone sends it's own error messages in string
                } else {
                    response = response['error'];
                }
                $j("#response").html("<div class='alert alert-danger'>" + response + "</div>");
                $j(".dropzone").css("border", "3px dotted red");

                setTimeout(deleteFile, 4000, file, this);
            });
        }
    })

    function deleteFile(file, elm) {
        elm.removeFile(file);
    }
</script>