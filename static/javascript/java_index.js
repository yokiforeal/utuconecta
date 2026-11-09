const perfilBoton = document.getElementById('perfil_boton'); // --- BUSCA EN EL HTML UN ELEMENTO CON ESA ID Y LO GUARDA EN UNA CONSTANTE
const menuDesplegablePerfil = document.getElementById('menu_desplegable_perfil'); // --- BUSCA EN EL HTML UN ELEMENTO CON ESA ID Y LO GUARDA EN UNA CONSTANTE

perfilBoton.addEventListener('click', (event) => { // --- ESPERA A QUE EL USUARIO CLICKEE EL ICONO DEL PERFIL 
    event.stopPropagation(); // --- NO ENTENDÍ BIEN PERO ES ALGO PARA QUE NO SE CIERRE AL INSTANTE 
    menuDesplegablePerfil.classList.toggle('mostrar'); // --- LE AGREGA O QUITA LA CLASE "mostrar" AL MENÚ; FUNCIONA COMO UN INTERRUPTOR
});

document.addEventListener('click', (event) => { // --- ESPERA A QUE SE CLICKEE EN CUALQUIER OTRA PARTE DE LA PÁGINA
    if (!menuDesplegablePerfil.contains(event.target) && event.target !== perfilBoton) { 
        menuDesplegablePerfil.classList.remove('mostrar'); // REMUEVE LA CLASE "mostrar" ESCONDIENDO DE NUEVO EL MENÚ
    }
});

// --- FUNCIONES PARA EL MODAL DE POSTULACIÓN ---
function abrirModal(idLlamado) {
    const modal = document.getElementById('modalPostulacion');
    const inputId = document.getElementById('modal_id_llamado');
    if (modal && inputId) {
        inputId.value = idLlamado;
        modal.style.display = 'flex';
    }
}

function cerrarModal() {
    const modal = document.getElementById('modalPostulacion');
    if (modal) {
        modal.style.display = 'none';
    }
}

// --- DESAPARECER AUTOMÁTICAMENTE LAS ALERTAS DE ÉXITO O ERROR ---
document.addEventListener("DOMContentLoaded", function() {
    // Buscamos las alertas en el documento usando las clases CSS que creamos
    const alertas = document.querySelectorAll('.alerta-exito, .alerta-error');

    alertas.forEach(function(alerta) {
        // temporizador 
        setTimeout(function() {
            // transición fadeout antes de quitarla
            alerta.style.transition = "opacity 0.6s ease";
            alerta.style.opacity = "0";

            // que termine la animación de opacidad para eliminarla del DOM
            setTimeout(function() {
                alerta.remove();
            }, 600); // 600 milisegundos que dura la transición de fade out

        }, 4000); // tiempo visible del cartel coso pum
    });
});