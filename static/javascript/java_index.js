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