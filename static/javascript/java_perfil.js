document.addEventListener('DOMContentLoaded', () => {
    const btnEditar = document.getElementById('btn-editar');
    const btnCancelar = document.getElementById('btn-cancelar');
    const accionesEdicion = document.getElementById('acciones-edicion');
    const inputsEditables = document.querySelectorAll('.input-editable');
    const btnCambiarFoto = document.querySelector('.btn-cambiar-foto');
    const inputFoto = document.getElementById('input-foto');
    const imgPerfil = document.getElementById('img-perfil');

    if (btnEditar) {
        btnEditar.addEventListener('click', () => {
            // Habilitar inputs para edición
            inputsEditables.forEach(input => {
                input.removeAttribute('readonly');
            });

            // Ocultar botón de editar y mostrar botones de guardar/cancelar
            btnEditar.style.display = 'none';
            accionesEdicion.style.display = 'flex';

            // Mostrar el botón de la cámara para cambiar foto
            if (btnCambiarFoto) {
                btnCambiarFoto.style.display = 'flex';
            }
        });
    }

    if (btnCancelar) {
        btnCancelar.addEventListener('click', () => {
            // Recargar la página para descartar cambios no guardados
            window.location.reload();
        });
    }

    // Previsualización de la nueva foto al seleccionarla
    if (inputFoto) {
        inputFoto.addEventListener('change', (e) => {
            const archivo = e.target.files[0];
            if (archivo) {
                const lector = new FileReader();
                lector.onload = function(evento) {
                    imgPerfil.src = evento.target.result;
                }
                lector.readAsDataURL(archivo);
            }
        });
    }
});