const mobileMenuBtn=document.querySelector("#mobile-menu"),sidebar=document.querySelector(".sidebar"),cerrarMenuBtn=document.querySelector("#cerrar-menu");
//! mostrar menu
mobileMenuBtn&&(mobileMenuBtn.addEventListener("click",(function(){sidebar.classList.toggle("mostrar")})),
//! eliminar menu
cerrarMenuBtn&&cerrarMenuBtn.addEventListener("click",(function(){sidebar.classList.add("ocultar"),setTimeout(()=>{sidebar.classList.remove("mostrar"),sidebar.classList.remove("ocultar")},1e3)})));
//!elimina la clase de mostrar en un tamaño de tablet
const anchoPantalla=document.body.clientWidth;window.addEventListener("resize",(function(){document.body.clientWidth>=768&&sidebar.classList.remove("mostrar")}));