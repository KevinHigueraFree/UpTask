!function(){!async function(){try{const t="/api/tareas?id="+d(),a=await fetch(t),o=await a.json();e=o.tareas,n()}catch(e){console.log(e)}}
//! Mostrar tareas
();let e=[],t=[];document.querySelector("#agregar-tarea").addEventListener("click",(function(){o(!1)}));function a(a){const o=a.target.value;t=""!==o?e.filter(e=>e.estado===o):[],n()}function n(){!
//! limpiar tareas que existan
function(){const e=document.querySelector("#listado-tareas");for(;e.firstChild;)e.removeChild(e.firstChild)}(),
//! revisar si hay tareas pendientes
function(){const t=e.filter(e=>"0"===e.estado),a=document.querySelector("#pendientes");0===t.length?a.disabled=!0:a.disabled=!1}
//! revisar si hay tareas completadas
(),function(){const t=e.filter(e=>"1"===e.estado),a=document.querySelector("#completadas");0===t.length?a.disabled=!0:a.disabled=!1}();const a=t.length?t:e;if(0===a.length){const e=document.querySelector("#listado-tareas"),t=document.createElement("LI");return t.textContent="No hay tareas",t.classList.add("no-tareas"),void e.appendChild(t)}const r={0:"Pendiente",1:"Completa"};a.forEach(t=>{const a=document.createElement("LI");a.dataset.tareaId=t.id,a.classList.add("tarea");const s=document.createElement("P");s.textContent=t.nombre,s.ondblclick=function(){o(editar=!0,{...t})};const i=document.createElement("DIV");i.classList.add("opciones");const l=document.createElement("BUTTON");l.classList.add("estado-tarea"),l.classList.add(""+r[t.estado].toLowerCase()),l.textContent=r[t.estado],l.dataset.estadoTarea=t.estado,l.ondblclick=function(){!
//! Cambiar estado de la tarea
function(e){const t="1"===e.estado?"0":"1";e.estado=t,c(e)}
//!Enviar peticion al servidor para cambair tarea
({...t})};const m=document.createElement("BUTTON");m.classList.add("eliminar-tarea"),m.dataset.idTarea=t.id,m.textContent="Eliminar",m.ondblclick=function(){!async function(t){Swal.fire({title:"¿Eliminar Tarea?",showCancelButton:!0,confirmButtonText:"Si",cancelButtonText:"No"}).then(a=>{a.isConfirmed&&async function(t){const{estado:a,id:o,nombre:r}=t,c=new FormData;c.append("id",o),c.append("nombre",r),c.append("estado",a),c.append("proyectoId",d());try{const a="/api/tarea/eliminar",o=await fetch(a,{method:"POST",body:c}),r=await o.json();!0===r.resultado&&(Swal.fire("¡Eliminado!",r.mensaje,"success"),e=e.filter(e=>e.id!==t.id),n())}catch(e){console.log(e)}}(t)})}({...t})},i.appendChild(l),i.appendChild(m),a.appendChild(s),a.appendChild(i);document.querySelector("#listado-tareas").appendChild(a)})}function o(t=!1,a={}){const o=document.createElement("DIV");o.classList.add("modal"),o.innerHTML=`\n            <form class="formulario">\n                <legend>${t?"Editar tarea":"Añade nueva tarea"} </legend>\n                <div class="campo">\n                    <label for="tarea">Tarea</label>\n                    <input type="text" name="tarea" id="tarea" value="${a.nombre?a.nombre:""}" placeholder="${a.nombre?"Edita la tarea":"Escribe el nombre de la tarea"}">\n                </div>\n                <div class="opciones">\n                    <input type="submit" class="submit-nueva-tarea" value="${a.nombre?"Guardar Cambios":"Añadir Tarea"}">\n                    <button type="button" class="cerrar-modal">Cancelar</button>\n                </div>\n            </form>\n            `,setTimeout(()=>{document.querySelector(".formulario").classList.add("animar")},0),o.addEventListener("click",(function(s){if(s.preventDefault(),s.target.classList.contains("cerrar-modal")){document.querySelector(".formulario").classList.add("cerrar"),console.log("eliminando ............."),setTimeout(()=>{o.remove()},1200)}if(s.target.classList.contains("submit-nueva-tarea")){const o=document.querySelector("#tarea").value.trim();if(""===o)
//! Mostrar alerta de error
return void r("No has ingresado el nombre de la tarea","error",document.querySelector(".formulario legend"));!0===t?(
//!reeescribir el nombre de la tarea
a.nombre=o,c(a)):
//!Consultar servidor para añadir tarea
async function(t){
//! Contruir la peticion
const a=new FormData;a.append("nombre",t),a.append("proyectoId",d());try{const o="/api/tarea",c=await fetch(o,{method:"POST",body:a}),d=await c.json();if(r(d.mensaje,d.tipo,document.querySelector(".formulario legend")),"exito"===d.tipo){const a=document.querySelector(".modal");setTimeout(()=>{a.remove()},3e3);
//!Agregar el objeto de tareas a la global tareas
const o={id:String(d.id),nombre:t,estado:"0",proyectoId:d.proyectoId};e=[...e,o],n(),console.log(o)}}catch(e){console.log(e)}}(o)}})),document.querySelector(".dashboard").appendChild(o)}
//! Muestra un mensaje en la interfaz
function r(e,t,a){const n=document.querySelector(".alerta");n&&n.remove();const o=document.createElement("DIV");o.classList.add("alerta",t),o.textContent=e,
//!inserta el codigo antes del leyend
a.parentElement.insertBefore(o,a.nextElementSibling),
//!Eliminar aleerta despues de unso segundos
setTimeout(()=>{o.remove()},5e3)}async function c(t){const{estado:a,id:o,nombre:r,proyectoId:c}=t,s=new FormData;s.append("id",o),s.append("nombre",r),s.append("estado",a),s.append("proyectoId",d());try{const t="/api/tarea/actualizar",c=await fetch(t,{method:"POST",body:s}),d=await c.json();
//! asignamos el nuevo valor de estado a la tarea seleccionada
if("exito"===d.respuesta.tipo){Swal.fire(d.respuesta.mensaje,d.respuesta.mensaje,"success");const t=document.querySelector(".modal");t&&t.remove(),e=e.map(e=>(e.id===o&&(e.estado=a,e.nombre=r),e)),n()}}catch(e){console.log(e)}}function d(){const e=new URLSearchParams(window.location.search);return Object.fromEntries(e.entries()).id}document.querySelectorAll('#filtros input[type="radio"]').forEach(e=>{e.addEventListener("input",a)})}();