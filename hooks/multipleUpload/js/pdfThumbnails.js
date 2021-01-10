var createPDFThumbnails = function (parent = "") {

    if (typeof pdfjsLib === 'undefined') {
        throw Error("pdf.js is not loaded. Please include it before pdfThumbnails.js.");
    }
    pdfjsLib.disableWorker = true;

    var nodesArray = $j(`${parent}img[data-pdffile]`);

    $j(nodesArray).each(function () {
        var $this = $j(this);
        var data = $this.data();
        data.pdfpage = $j(this).attr("data-pdfpage");
        data.pdfpage = data.pdfpage >= 1 ? data.pdfpage : 1;

        pdfjsLib.getDocument(data.pdffile).promise.then(function (pdf) {
            var size = pdf.numPages;
            var text = size > 1 ? `${size} pages` : "one page";
            $this.attr('data-pdfsize', size);
            $j('.code-pdfsize-' + data.ix).text(text);
            $j('#pdf-page-' + data.ix).attr("data-max-page", size);

            pdf.getPage(parseInt(data.pdfpage)).then(function (page) {
                var canvas = document.createElement("canvas");
                var viewport = page.getViewport({
                    scale: 1
                });
                var context = canvas.getContext('2d');

                canvas.height = viewport.height;
                canvas.width = viewport.width;

                page.render({
                    canvasContext: context,
                    viewport: viewport,
                }).promise.then(function () {
                    $this.attr('src', canvas.toDataURL("image/png"));
                });

            }).catch(function () {
                console.log(`pdfThumbnails error: could not open page ${data.pdfpage} of document  ${data.pdffile} . Not a pdf ?`);
            });
        }).catch(function () {
            console.log(`pdfThumbnails error: could not find or open document ${data.pdffile}. Not a pdf ?`);
        });
    });
};
