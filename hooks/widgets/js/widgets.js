const ajax_widget = function (data=false) {
    return $j.ajax({
      method: "post",
      url: "hooks/widgets/functions_ajax.php",
      dataType: "json",
      data
    });
  }

  function widget(widget=false,options={}){
    if (!options || !widget) return;
    return new Promise((resolve, reject) => {
        ajax_widget({
            "cmd": widget,
            "data": options,
        }).done(function(res){
            resolve(res);
        }).fail(function (e) {
            reject(e)
        });
    });
}