
var request = new XMLHttpRequest();

request.open("GET",'http://localhost/api/producto.php',true);
var $lista = document.getElementById('productos');

function traerLista(){
    request.onreadystatechange = function(){
       if(request.status==200 && request.readyState==4){
            var respuesta = JSON.parse(request.responseText);
            console.log(respuesta.response);
            crearLista(respuesta.response);
       }
    };
    request.send(null);
};

function enlazarBotones(){
    var $arrayBotones = document.getElementsByClassName('btn_comprar');

    for (el of $arrayBotones) {
        el.onclick=function(event){
            console.log("id prod "+ event.target.value);
            var id=event.target.value;
            var ruta="id="+id;

            $.ajax({
                url:'http://localhost/api/detalles.php',
                type:'GET',
                data: ruta
            })
            .done(function(){
                location.href="http://localhost:3000/productox.html?id="+id;
            })
        }
        
    }
}

function crearLista(listapro){
    
    var lista = '';
    var $mostrar = document.getElementById('productos');
    
    listapro.forEach(function(producto, index){
        lista += '<article><img src="img/prod/' + producto.imagen + '" >' 
                +'<h2>'+ producto.nombre +'</h2>'
                +'<h3>$'+ producto.precio +'</h3> '
                +'<div class="botoncomprar">'
                +'<button class="btn_comprar" value='+producto.id_producto+' >Detalles</button>'
                +'</div></article>';            
    });
    $mostrar.innerHTML = lista;
    enlazarBotones(); 
}

window.onload = function(){
    traerLista();
};

