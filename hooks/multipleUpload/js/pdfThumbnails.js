var createPDFThumbnails = function () {

    if (typeof pdfjsLib === 'undefined') {
        throw Error("pdf.js is not loaded. Please include it before pdfThumbnails.js.");
    }
    // pdfjsLib.GlobalWorkerOptions.workerSrc = pdfjsWorker;
    pdfjsLib.disableWorker = true;

    var nodesArray = Array.prototype.slice.call(document.querySelectorAll('img[data-pdf-file]'));

    nodesArray.forEach(function (element) {
        var filePath = element.getAttribute('data-pdf-file');

        pdfjsLib.getDocument(filePath).promise.then(function (pdf) {
            pdf.getPage(1).then(function (page) {
                var canvas = document.createElement("canvas");
                var scale = 1;
                var viewport = page.getViewport({
                    scale: scale,
                });
                var context = canvas.getContext('2d');

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                page.render({
                    canvasContext: context,
                    viewport: viewport,
                }).promise.then(function () {
                    url = canvas.toDataURL("image/png");
                    element.src = url;
                });

            }).catch(function () {
                console.log("pdfThumbnails error: could not open page 1 of document " + filePath + ". Not a pdf ?");
            });
        }).catch(function () {
            console.log("pdfThumbnails error: could not find or open document " + filePath + ". Not a pdf ?");
        });
    });
};

// if (
//     document.readyState === "complete" ||
//     (document.readyState !== "loading" && !document.documentElement.doScroll)
// ) {
//     setTimeout(() => {
//         createPDFThumbnails();
//     }, 700);
// } else {
//     document.addEventListener("DOMContentLoaded", function() {
//         setTimeout(() => {
//             createPDFThumbnails();
//         }, 700);
//     });
// }