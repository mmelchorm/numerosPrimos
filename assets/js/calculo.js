function login(){
   let nombre = document.getElementById("nombre");
   if (nombre.value != '') {
        /////////// POST /////////
		var http = new FormData();
		http.append("request", "login");
		http.append("nombre", nombre.value);
		var request = new XMLHttpRequest();
		request.open("POST", "controller/ajax_calcular.php");
		request.send(http);
		request.onreadystatechange = function () {
			//sconsole.log( request.responseText);
			if (request.readyState != 4) return;
			if (request.status === 200) {
				resultado = JSON.parse(request.responseText);
				if (resultado.status !== true) {
					swal("Error", resultado.message, "error").then((value) => {
					});
					return;
				}
                codigo = resultado.codigo;
                nombre = resultado.nombre;
				swal("Excelente!", resultado.message, "success").then((value) => {
					document.getElementById("nombre").value = '';
					document.getElementById('codigo_usuario').value = codigo;
					document.getElementById('nombre_usuario').innerHTML = nombre;
					document.getElementById("login").className += " hide";
					get_intentos();
				});
			}
		};
   }else{
    swal('Ooopss', 'El campo del nombre esta vacio', 'error');
   }
}

function esPrimo(numero){
    for(let i = 2,raiz=Math.sqrt(numero); i <= raiz; i++)
        if(numero % i === 0) return false;
    return numero > 1;
} 

function calcular() {
	let numero = document.getElementById("numero").value;
	let usuario = document.getElementById("codigo_usuario").value;
	let resultado_primos = 0;
	let resultado_concat = '';
	if (usuario != '') {
		if (numero != "") {
			for (let x = 0; x <= numero; x++) {
				if (esPrimo(x)){
					//console.log("El número " + x + " es primo");
					resultado_primos+=x;
					resultado_concat+= x+',';
				}
			}
			resultado_concat = resultado_concat.substr(0, resultado_concat.length -1);
			//console.log(resultado_concat);
			document.getElementById("resultado").value = resultado_primos;
			document.getElementById("primos").value = resultado_concat;
			genera_intento(numero,resultado_primos,resultado_concat);
		}else{
			swal("Error", "Debe de ingresar un numero", "error")
		}
	}else{
		swal("Error", "No se puede generar el intento sin usuario", "error")
	}
}

function genera_intento(numero,sumatoria,primos){
	let usuario = document.getElementById("codigo_usuario").value;
	if (usuario != '' && primos != '') {
		/////////// POST /////////
		var http = new FormData();
		http.append("request", "intento");
		http.append("usuario", usuario);
		http.append("ingresado", numero);
		http.append("sumatoria", sumatoria);
		http.append("primos", primos);
		var request = new XMLHttpRequest();
		request.open("POST", "controller/ajax_calcular.php");
		request.send(http);
		request.onreadystatechange = function () {
			//sconsole.log( request.responseText);
			if (request.readyState != 4) return;
			if (request.status === 200) {
				resultado = JSON.parse(request.responseText);
				if (resultado.status !== true) {
					swal("Error", resultado.message, "error").then((value) => {
					});
					return;
				}
					get_intentos();
				
			}
		};
	}else{
	 swal('Ooopss', 'El campo del nombre esta vacio', 'error');
	}
}

function get_intentos(){
	let usuario = document.getElementById("codigo_usuario");
	let contenedor = document.getElementById("contenedor_listado");
	if (usuario.value != '') {
		/////////// POST /////////
		var http = new FormData();
		http.append("request", "get_intentos");
		http.append("usuario", usuario.value);
		var request = new XMLHttpRequest();
		request.open("POST", "controller/ajax_calcular.php");
		request.send(http);
		request.onreadystatechange = function () {
			//sconsole.log( request.responseText);
			if (request.readyState != 4) return;
			if (request.status === 200) {
				resultado = JSON.parse(request.responseText);
				if (resultado.status !== true) {
					swal("Error", resultado.message, "error").then((value) => {
					});
					return;
				}
				//swal("Excelente!", resultado.message, "success");
				contenedor.innerHTML = resultado.lista;
				
			}
		};
	}else{
	 swal('Ooopss', 'No hay usuario registrado', 'error');
	}
}

function get_intento_usuario(intento){
	let usuario = document.getElementById("codigo_usuario");
	if (usuario.value != '' && intento != '') {
		/////////// POST /////////
		var http = new FormData();
		http.append("request", "get_intento_usuario");
		http.append("usuario", usuario.value);
		http.append("intento", intento);
		var request = new XMLHttpRequest();
		request.open("POST", "controller/ajax_calcular.php");
		request.send(http);
		request.onreadystatechange = function () {
			//sconsole.log( request.responseText);
			if (request.readyState != 4) return;
			if (request.status === 200) {
				resultado = JSON.parse(request.responseText);
				if (resultado.status !== true) {
					swal("Error", resultado.message, "error").then((value) => {
					});
					return;
				}
				document.getElementById("numero").value = resultado.numero;
				document.getElementById("resultado").value = resultado.sumatoria;
				document.getElementById("primos").value = resultado.primos;
			}
		};
	}else{
	 swal('Ooopss', 'No hay usuario registrado', 'error');
	}
}

