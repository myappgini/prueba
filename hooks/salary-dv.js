$j(function() {
    $j("#monto").focusout(function() { //ejecuta esta función cuando el usuario deja el campo (pierde el foco)
        //obtengo el valor del monto, una vez que el usurio se retira del campo monto.
        let value = $j(this).val();

        //leo el valor del estring de rango para obtener los valors del rango
        let rango = $j("#rango").text();
        // la función getNumber devuelve una matris con todos los numero que encuentra en el rango
        rango = getNumbers(rango);
        //evalua el valor del campo con los valores obtenidos del rango, supongo que el primer valor enocntrado es el menor y el seundo es el mayor
        let test = value >= rango[0] && value <= rango[1];

        // test es verdadero sigue
        if (!test) {
            //si test es falso entra acá
            //muestro el alert y salgo, ver los comentarios dentro de la función para mas aydua.
            return show_alert("monto", "el campo Monto", "Valor de Monto fuera de rango", "warning");
        }
        // verifica que el campo no tenga la clase error, si la tiene se la saca
        if ($j('#monto').closest('.form-group').hasClass('has-error')) {
            $j('#monto').closest('.form-group').removeClass('has-error')
        }
        // verifica que el campo no tenga la clase warning, si la tiene se la saca
        if ($j('#monto').closest('.form-group').hasClass('has-warning')) {
            $j('#monto').closest('.form-group').removeClass('has-warning')
        }
    })

    // esta función se ejecuta cuando se hace click sobre el boton save changes.
    $j("#update").on('click', function() {
        //obtiene todos los campos con error, segunda verificación por cualquier cosa.
        let errors = $j('.has-error').length;
        console.log(errors, warnings);
        // si hay con errores regresa un false y no permite guardar
        if (errors > 0) return false;

        //verifica la fecha
        let date = $j('#date').text()
            //la fucnion check_dates compra fecha actual < que la ingresada, si es menor regresa un true, si es mayor un false
        let test = check_dates(date);
        if (!test) {
            //envia un alerte en caso de que la fecha ingresa sea menor
            return show_alert("date", "la fecha", "La fecha de pago es menor", "warning");
        }
    })
});

function getNumbers(inputString) {
    var regex = /\d+\.\d+|\.\d+|\d+/g,
        results = [],
        n;
    while (n = regex.exec(inputString)) {
        results.push(parseFloat(n[0]));
    }
    return results;
}

// esta fucnión regresa un true o un false, se puede utilizar para continuar o cancelar dependiendo si es danger o warning
function show_alert(field, caption, msg, color = "danger") {
    // field: nombre del campo al que se le va a poner la clase
    // caption, titulo de la ventana emergente
    // el mensaje del cuerpo de la ventana
    // color: toma por defecta danger, no es necesario pasarla en la llamada, tambien se puede poner warning
    modal_window({
        message: `<div class="alert alert-${color}"> ${msg}</div>`,
        title: caption,
        close: function() {
            let clase = (color === "danger") ? "has-error" : "has-warning";
            $j('#' + field).closest('.form-group').addClass(clase);
            if (color === "danger") $j('#' + field).focus();
        }
    });
    // si el color es distinto de danger deja continuar
    if (color === "danger") {
        return false;
    } else {
        return true;
    }
}

function check_dates(date) {
    date = date.split("/");
    var x = new Date();
    var y = new Date(date[2], date[1] - 1, date[0]);
    console.log(+x, +y);
    return x < y;
};