$j(function() {
    const max = 5000;
    $j("td.salary-monto").each(function() {
        var obj = $j(this);
        obj.css({ position: "relative" });
        obj.children('a').css({ position: "absolute", "z-index": "1" });
        var value = obj.text();
        var rel = value / max;
        var w = obj.width();
        var h = "100%"; //obj.height();
        var $container = $j("<div/>", {
            "style": "border:1px solid #d3d3d3; position:absolute; top:3px; left:3px; z-index:0;"
        }).width(w).height(h);
        var $bar = $j("<div/>", { class: "bg-info" }).width(rel * 100 + "%").height("100%");
        obj.append($container.append($bar))
    });
});