(function () {

    obtenerTareas();
    let tareas = [];
    let filtradas = [];

    //! Boton para mostrar el modal de Agregar tarea
    const nuevaTareaBtn = document.querySelector('#agregar-tarea');
    nuevaTareaBtn.addEventListener('click', function () {
        mostrarFormulario(false);
    });
    const filtros = document.querySelectorAll('#filtros input[type="radio"]');
    filtros.forEach(radio => { //itera en cada uno de los filtros
        radio.addEventListener('input', filtrarTareas);
    });


    function filtrarTareas(e) {
        const filtro = e.target.value;

        if (filtro !== '') {
            filtradas = tareas.filter(tarea => tarea.estado === filtro); //todo filtradas obtiene el valor de las tareas en memoria que cumplan con el filtro
        } else {
            filtradas = [];
        }
        mostrarTareas();
        //   console.log(filtradas);
    }
    async function obtenerTareas() {
        try {
            const id = obtenerProyecto();
            const url = `/api/tareas?id=${id}`;
            const respuesta = await fetch(url);
            const resultado = await respuesta.json();
            tareas = resultado.tareas
            mostrarTareas();
        } catch (error) {
            console.log(error);
        }
    }
    //! Mostrar tareas
    function mostrarTareas() {
        limpiarTareas();
        totalPendientes();
        totalCompletadas();

        const arrayTareas = filtradas.length ? filtradas : tareas;

        if (arrayTareas.length === 0) {
            const contenedorTareas = document.querySelector('#listado-tareas');
            const textoNoTareas = document.createElement('LI');
            textoNoTareas.textContent = 'No hay tareas';
            textoNoTareas.classList.add('no-tareas');

            contenedorTareas.appendChild(textoNoTareas);
            return;
        }

        const estados = {
            0: 'Pendiente',
            1: 'Completa'
        }
        arrayTareas.forEach(tarea => {
            const contenedorTarea = document.createElement('LI');
            contenedorTarea.dataset.tareaId = tarea.id; //tarea.id es el id instanciado por tarea de foreach
            contenedorTarea.classList.add('tarea');

            const nombreTarea = document.createElement('P');
            nombreTarea.textContent = tarea.nombre; //tarea.nombre es el nombre instanciado por tarea de foreach
            nombreTarea.ondblclick = function () {
                mostrarFormulario(editar = true, {
                    ...tarea
                });
            }

            const opcionesDiv = document.createElement('DIV');
            opcionesDiv.classList.add('opciones');

            const btnEstadoTarea = document.createElement('BUTTON');
            btnEstadoTarea.classList.add('estado-tarea');
            btnEstadoTarea.classList.add(`${estados[tarea.estado].toLowerCase()}`); //? tolowercase: los hace minusculas
            btnEstadoTarea.textContent = estados[tarea.estado]; //0: pendiente, 1: completa
            btnEstadoTarea.dataset.estadoTarea = tarea.estado;
            btnEstadoTarea.ondblclick = function () {
                cambiarEstadoTarea({
                    ...tarea
                }); //sacar un objeto de tarea sacar una copia y modificar esa copia
            }


            const btnEliminarTarea = document.createElement('BUTTON');
            btnEliminarTarea.classList.add('eliminar-tarea');
            btnEliminarTarea.dataset.idTarea = tarea.id;
            btnEliminarTarea.textContent = 'Eliminar';
            btnEliminarTarea.ondblclick = function () {
                confirmarEliminarTarea({
                    ...tarea
                }); //sacar un objeto de tarea sacar una copia y modificar esa copia
            }

            /*  console.log(contenedorTarea);
            console.log(btnEstadoTarea);
             console.log(nombreTarea);
             console.log(btnEliminarTarea); */
            opcionesDiv.appendChild(btnEstadoTarea);
            opcionesDiv.appendChild(btnEliminarTarea);

            contenedorTarea.appendChild(nombreTarea);
            contenedorTarea.appendChild(opcionesDiv);

            const listadoTareas = document.querySelector('#listado-tareas');
            listadoTareas.appendChild(contenedorTarea)

            //console.log(listadoTareas);
        });

    }
    //! revisar si hay tareas pendientes
    function totalPendientes() {
        const totalPendientes = tareas.filter(tarea => tarea.estado === "0")
        const pendientesRadio=document.querySelector('#pendientes');
        //desabilitar cuando  total pendientes este vacio
        if(totalPendientes.length===0){
            pendientesRadio.disabled=true;
        }else{
            pendientesRadio.disabled=false;
        }
    }
    //! revisar si hay tareas completadas
    function totalCompletadas() {
        const totalCompletadas = tareas.filter(tarea => tarea.estado === "1")
       const completadasRadio=document.querySelector('#completadas');
       //desabilitar cuando  totalCompletadas este vacio
       if(totalCompletadas.length===0){
        completadasRadio.disabled=true;
       }else{
        completadasRadio.disabled=false;
       }
    }

    function mostrarFormulario(editar = false, tarea = {}) {
        const modal = document.createElement('DIV');
        modal.classList.add('modal');
        modal.innerHTML = `
            <form class="formulario">
                <legend>${editar ? 'Editar tarea':'Añade nueva tarea'} </legend>
                <div class="campo">
                    <label for="tarea">Tarea</label>
                    <input type="text" name="tarea" id="tarea" value="${tarea.nombre ? tarea.nombre : ''}" placeholder="${tarea.nombre ? 'Edita la tarea' : 'Escribe el nombre de la tarea'}">
                </div>
                <div class="opciones">
                    <input type="submit" class="submit-nueva-tarea" value="${tarea.nombre ? 'Guardar Cambios' : 'Añadir Tarea'}">
                    <button type="button" class="cerrar-modal">Cancelar</button>
                </div>
            </form>
            `;
        setTimeout(() => {
            //lo que este en setTimeout se ejecutara al final
            const formulario = document.querySelector('.formulario');
            formulario.classList.add('animar');
        }, 0);

        //todo DELEGATION
        modal.addEventListener('click', function (e) {
            e.preventDefault();
            if (e.target.classList.contains('cerrar-modal')) {
                const formulario = document.querySelector('.formulario');
                formulario.classList.add('cerrar');
                console.log('eliminando .............');
                setTimeout(() => {
                    modal.remove();
                }, 1200);

            }
            if (e.target.classList.contains('submit-nueva-tarea')) {
                const nombreTarea = document.querySelector('#tarea').value.trim();
                if (nombreTarea === '') {
                    //! Mostrar alerta de error
                    mostrarAlerta('No has ingresado el nombre de la tarea', 'error', document.querySelector('.formulario legend'));
                    return;
                }
                if (editar === true) {
                    //!reeescribir el nombre de la tarea
                    tarea.nombre = nombreTarea
                    actualizarTarea(tarea);
                } else {
                    agregarTarea(nombreTarea);
                }
                //agregarTarea(tarea);



            }
        });

        document.querySelector('.dashboard').appendChild(modal);
    }

    //? trim(): elimina espacio al incio y al final


    //! Muestra un mensaje en la interfaz
    function mostrarAlerta(mensaje, tipo, referencia) {
        //previene la creacion de multiples alertas
        const alertaPrevia = document.querySelector('.alerta');
        if (alertaPrevia) {
            alertaPrevia.remove();
        }

        const alerta = document.createElement('DIV');
        alerta.classList.add('alerta', tipo);
        alerta.textContent = mensaje;
        //!inserta el codigo antes del leyend
        referencia.parentElement.insertBefore(alerta, referencia.nextElementSibling);


        //!Eliminar aleerta despues de unso segundos

        setTimeout(() => {
            alerta.remove();
        }, 5000);
    }
    //!Consultar servidor para añadir tarea
    async function agregarTarea(tarea) {
        //  console.log(tarea);
        //! Contruir la peticion
        const datos = new FormData();
        datos.append('nombre', tarea);
        // console.log(obtenerProyecto());
        datos.append('proyectoId', obtenerProyecto());

        // return;

        try {
            const url = '/api/tarea'; //url para la peticion
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });

            const resultado = await respuesta.json();


            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.formulario legend'));
            if (resultado.tipo === 'exito') {
                const modal = document.querySelector('.modal');
                setTimeout(() => {
                    modal.remove();
                }, 3000);

                //!Agregar el objeto de tareas a la global tareas
                const tareaObj = {
                    id: String(resultado.id),
                    nombre: tarea,
                    estado: "0",
                    proyectoId: resultado.proyectoId
                }
                tareas = [...tareas, tareaObj]; //todo tareas toma el valor del arreglo ...tareas(variable global) y del objeto tareaObj; 
                mostrarTareas();
                console.log(tareaObj);
            }
        } catch (error) {
            console.log(error);
        }
    }



    //! Cambiar estado de la tarea
    function cambiarEstadoTarea(tarea) {
        const nuevoEstado = tarea.estado === "1" ? "0" : "1"; //CAMBIO DE ESTADO
        tarea.estado = nuevoEstado;
        actualizarTarea(tarea);
    }
    //!Enviar peticion al servidor para cambair tarea
    async function actualizarTarea(tarea) {
        //? destruct: extraye las variables de una instancia hecha 


        const {
            estado,
            id,
            nombre,
            proyectoId
        } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = '/api/tarea/actualizar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();

            //! asignamos el nuevo valor de estado a la tarea seleccionada
            if (resultado.respuesta.tipo === 'exito') {
                Swal.fire(
                    resultado.respuesta.mensaje,
                    resultado.respuesta.mensaje,
                    'success'
                );
                const modal = document.querySelector('.modal');
                if (modal) {
                    modal.remove();
                }
                tareas = tareas.map(tareaMemoria => {
                    if (tareaMemoria.id === id) {
                        tareaMemoria.estado = estado;
                        tareaMemoria.nombre = nombre;
                    }
                    return tareaMemoria; //le da a tareas el valor obtenido por tareaMemoria ya modificado
                });
                mostrarTareas();
            }
        } catch (error) {
            console.log(error);
        }
    }
    async function confirmarEliminarTarea(tarea) {
        Swal.fire({
            title: '¿Eliminar Tarea?',
            showCancelButton: true,
            confirmButtonText: 'Si',
            cancelButtonText: 'No'
        }).then((result) => {
            /* Read more about isConfirmed, isDenied below */
            if (result.isConfirmed) {
                eliminarTarea(tarea);
            }
        })
    }
    async function eliminarTarea(tarea) {
        const {
            estado,
            id,
            nombre
        } = tarea;

        const datos = new FormData();
        datos.append('id', id);
        datos.append('nombre', nombre);
        datos.append('estado', estado);
        datos.append('proyectoId', obtenerProyecto());

        try {
            const url = '/api/tarea/eliminar';
            const respuesta = await fetch(url, {
                method: 'POST',
                body: datos
            });
            const resultado = await respuesta.json();
            if (resultado.resultado === true) {
                /*            mostrarAlerta(resultado.mensaje, resultado.tipo, document.querySelector('.contenedor-nueva-tarea')); */
                //todo traer todas las tareas que sean diferentes a la que se desea eliminar
                Swal.fire('¡Eliminado!', resultado.mensaje, 'success'); // alerta
                tareas = tareas.filter(tareaMemoria => tareaMemoria.id !== tarea.id);
                mostrarTareas(tareas)
            }
        } catch (error) {
            console.log(error);
        }
    }

    function obtenerProyecto() {
        const proyectoParams = new URLSearchParams(window.location.search); //obtiene una clave(window.location.searh(localizacion por url))
        const proyecto = Object.fromEntries(proyectoParams.entries()); //obtiene el valor(localizacion) de una variable
        return proyecto.id;
    }


    //! limpiar tareas que existan
    function limpiarTareas() {
        const listadoTareas = document.querySelector('#listado-tareas');
        //mientras listadoTareas tenga elementos ejecutar
        while (listadoTareas.firstChild) {
            listadoTareas.removeChild(listadoTareas.firstChild);
        };


    }
})(); //? (): EJECUTA LA FUNCION INMEDIATAMENTE