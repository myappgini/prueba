t = 'contacto';
f = 'user';
users_dropdown(f, t);

$j(function () {
    $j('fieldset').append(add_object());
});


function add_object(){
    const p = $j('<p />',{
        class:"bg-primary", // utilizaremos la clase de bootstrap para colorear el fondo del objeto
        text:" this is my DIV",
    });
    return p;
}