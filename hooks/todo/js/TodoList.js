const Ajax_Settings_todo = {
  method: "post",
  url: "hooks/todo/functions_ajax.php",
  dataType: "html",
};
const ajax_todo = function (data) {
  return $j.ajax({
    method: Ajax_Settings_todo.method,
    url: Ajax_Settings_todo.url,
    dataType: Ajax_Settings_todo.dataType,
    data
  });
}

$j(function () {
  const todo = $j.get("hooks/todo/templates/dropdown_menu.html");
  todo.done(function (res) {
    $j('.nav.navbar-nav.navbar-right').prepend(res);
  })
});

$j('.nav.navbar-nav.navbar-right').on('click', '.todo-dropdown-content', function () {
  $j('div.todo-content').html('Loading Content...');

  const data = {
    cmd: 'get-todo'
  }

  ajax_todo(data).done(function (res) {
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
  const data = {
    cmd: 'add-task',
    task:$j('.task-to-add').val()
  }
  ajax_todo(data).done(function (res) {
    $j('.todo-list').append(res);
  })
});

$j('body').on('click', '.todo-task-tool', function () {
  const $this = $j(this);
  const $li =   $this.closest('li');
  const data = {
    cmd: $this.data('cmd'),
    ix: $li.data('ix'),
  }
  ajax_todo(data).done( res=> {
    console.log(res);
  })
  $li.remove();

});

$j('body').on('click', '.todo-task-edit', function () {
  alert("edit task")
});

const tasks = {
  "tasks": [{
      "task": {
        "task": "esta es una nueva tarea 1",
        "complete": false,
        "added": "fecha y hora",
        "due": "fecha y hora de vencimiento",
        "edited": ["oldvalue1", "oldvalue2", "oldvalue3"],
        "deleted": false,
        "date_deleted": "feha y hora de borrado"
      }
    },
    {
      "task": {
        "task": "Esta es otra tarea 2",
        "complete": false,
        "added": "fecha y hora",
        "due": "fecha y hora de vencimiento",
        "edited": ["oldvalue1", "oldvalue2", "oldvalue3"],
        "deleted": false,
        "date_deleted": "feha y hora de borrado"
      }
    },
    {
      "task": {
        "task": "Esta es otra tarea 4",
        "complete": false,
        "added": "fecha y hora",
        "due": "fecha y hora de vencimiento",
        "edited": ["oldvalue1", "oldvalue2", "oldvalue3"],
        "deleted": false,
        "date_deleted": "feha y hora de borrado"
      }
    }
  ]
}