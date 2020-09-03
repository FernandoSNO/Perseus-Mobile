
var formulario=document.getElementById('formulario');
                
formulario.onclick=function() {
                   
    var $nombre=document.getElementById('nombre').value;
    var $apellido=document.getElementById('apellido').value;
    var $email=document.getElementById('email').value;
    var $tel=document.getElementById('telefono').value;
    var $tarjeta=document.getElementById('tarjeta').value;
    var ruta="nombre="+$nombre+"&apellido="+$apellido+"&email="+$email+"&tel"+$tel+"&tarjeta"+$tarjeta;

    $.ajax({
        url: 'http://localhost/api/form.php',
        type: 'POST',
        data: ruta
    })
    
        .done(function (res) {
            alert("Compra Realizada con Exito");
            window.location="http://localhost:3000/index.html";
        })
    };