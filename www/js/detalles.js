
var req = new XMLHttpRequest();
var url ='http://localhost/api/detalles.php'+window.location.search;
req.open("GET",url,true);
var $lista = document.getElementById('productos');
function traerLista(){
    req.onreadystatechange = function(){
       if(req.status==200 && req.readyState==4){
            var respuesta = JSON.parse(req.responseText);
            console.log(respuesta.response);
            crearLista(respuesta.response);
       }
    };
    req.send(null);
};
function crearLista(listapro){
    
    var lista = '';
    var $mostrar = document.getElementById('productos');
    
    listapro.forEach(function(producto, index){
        lista += '<article><img src="img/prod/' + producto.imagen + '" >' 
                +'<h2>'+ producto.nombre +'</h2>' 
                +'<h3>$'+ producto.precio +'</h3> '
                +'<div class="botoncomprar" onclick="redireccion()">'
                +'<button class="btn_comprar" id="comprar" >Comprar</button>'
                +'</div></article><br>';  

            });
    $mostrar.innerHTML = lista; 
}

window.onload = function(){
    traerLista();
};

function redireccion() {
    window.location="http://localhost:3000/form.html";
}
