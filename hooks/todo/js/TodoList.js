const ajax_todo = function (data) {
  return $j.ajax({
    method: "post",
    url: "hooks/todo/functions_ajax.php",
    dataType: "html",
    data
  });
}

$j(function () {
  ajax_todo({
    cmd: 'option-todo'
  }).done(function (res) {
    $j('nav .navbar-collapse').append(res);
    get_values();
  });
});

$j('body').on('click', '.todo-dropdown-content', function () {
  $j('div.todos-content').html('Loading Content...');
  ajax_todo({
    cmd: 'get-todo'
  }).done(function (res) {
    $j('div.todos-content').html(res);
    $j('.todo-list').sortable({
      placeholder: 'sort-highlight',
      handle: '.handle',
      forcePlaceholderSize: true,
      zIndex: 999999
    });
    get_values();
  })
  $j(this).closest('li').toggleClass('open');
})

$j('body').on('click', '.close-remove', function () {
  $j('.dropdown.todo-dropdown').removeClass('open');
});

$j('body').on('click', '.todo-task-check', function () {
  const $this = $j(this);
  const $li = $this.closest('li');

  const data = {
    cmd: $this.data('cmd'),
    ix: $li.data('ix'),
    complete: $this.is(":checked") ? true : false,
  }

  ajax_todo(data).done(function (res) {
    console.log(res);
    complete ? $li.addClass('done') : $li.removeClass('done');
    get_values();
  })
});

$j('body').on('click', '.add-todo-task', function () {
  const $this = $j(this);
  const data = {
    cmd: $this.data('cmd'),
    task: $j('.task-to-add').val()
  }
  ajax_todo(data).done(function (res) {
    $j('.todo-list').append(res);
    get_values()
    $j(".form-control.task-to-add").select();
  })
});

$j('body').on('click', '.todo-task-delete', function () {
  const $this = $j(this);
  const $li = $this.closest('li');
  const data = {
    cmd: $this.data('cmd'),
    ix: $li.data('ix'),
  }
  if (data.cmd != 'delete-task') return;
  ajax_todo(data).done(res => {
    console.log(res);
    get_values()
  })
  $li.remove();
});

$j('body').on('click', '.task-text', function () {
  const $li = $j(this).closest('li');
  btn = $li.find('.todo-task-edit');
  btn.trigger('click');
})

$j('body').on('click', '.todo-task-edit', function () {

  const $this = $j(this);
  const $li = $this.closest('li');
  const $span = $li.children('span.task-text');
  let tb = $li.find('input:text');

  if (tb.length) {
    $this.removeClass('glyphicon-ok').addClass('glyphicon-pencil');
    let text = tb.val();
    $span.text(text); //remove text box & put its current value as text to the div
    const data = {
      cmd: $this.data('cmd'),
      ix: $li.data('ix'),
    }
    data.newtext = text;
    ajax_todo(data).done(function (res) {
      console.log(res)
    });
  } else {
    let text = $span.text();
    $this.removeClass('glyphicon-pencil').addClass('glyphicon-ok');
    tb = $j('<input>').prop({
      'type': 'text',
      'value': text.trim(), //set text box value from div current text
      'style': 'color: #333;',
      'class': 'input-edit-task',
    });
    $span.empty().append(tb); //add new text box
    tb.focus(); //put text box on focus
  }
});

$j(document).keyup(function (e) {
  if ($j(".input-edit-task").is(":focus") && (e.keyCode == 13)) {
    $j('i.todo-task-edit.glyphicon-ok').trigger('click');
  }
  if ($j(".form-control.task-to-add").is(":focus") && (e.keyCode == 13)) {
    $j('button.add-todo-task').trigger('click');
  }
});

$j("body").on('focusout', '.input-edit-task', function () {
  $j('i.todo-task-edit.glyphicon-ok').trigger('click');
})

function get_values() {
  ajax_todo({
    cmd: 'get-values'
  }).done(function (res) {
    let data = JSON.parse(res);
    console.log('update');
    $j('.label-tasks').text(`${data.completed}/${data.listed}`);
    $j('.label-trash').text(`${data.deleted}`);
    $j('.progress-bar').css('width', `${data.completed/data.listed*100}%`).attr('aria-valuenow', data.completed / data.listed); //.text(`${data.completed/data.listed*100}%`);
  })
}

//122---115---133--145---150---151---149
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