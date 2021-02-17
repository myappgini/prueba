const ajax_widget = function (data=false) {
    return $j.ajax({
      method: "post",
      url: "hooks/todos/functions_ajax.php",
      dataType: "json",
      data
    });
  }

  function info_box(){
      data={
          "cmd":"info-box"
      }
      ajax_widget(data).done(function({html, error}){
        console.log(html);
      })
  }