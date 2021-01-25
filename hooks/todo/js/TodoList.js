const settings = {
  method: "post",
  url: "hooks/todo/functions_ajax.php",
  dataType: "html",
};

$j(function () {
  const todo = $j.get("hooks/todo/templates/dropdown_menu.html");
  todo.done(function (res) {
    $j('.nav.navbar-nav.navbar-right').prepend(res);
  })
});

$j('.nav.navbar-nav.navbar-right').on('click', '.todo-dropdown-content', function () {
  const {
    method,
    url,
    dataType
  } = settings;

  const todo = $j.ajax({
    method,
    url,
    dataType,
    data: {
      cmd: 'get-todo'
    }
  });

  todo.done(function (res) {
    $j('div.todo-content').html(res);
    $j('.todo-list').sortable({
      placeholder: 'sort-highlight',
      handle: '.handle',
      forcePlaceholderSize: true,
      zIndex: 999999
    });
  })
  $j(this).parent().toggleClass('open');

})
$j('body').on('click', '.close-remove', function () {
  $j('.dropdown.todo-dropdown').removeClass('open');
});
$j('body').on('click', '.add-todo-task', function () {
  const task = $j('.task-to-add').val();
  console.log("adding task: " + task)
});
$j('body').on('click','.todo-task-action',function(){

});