document.addEventListener('DOMContentLoaded', () => {
    const btnEditar = document.getElementById('btn-editar');
    const btnCancelar = document.getElementById('btn-cancelar');
    const accionesEdicion = document.getElementById('acciones-edicion');
    const inputsEditables = document.querySelectorAll('.input-editable');
    const btnCambiarFoto = document.querySelector('.btn-cambiar-foto');

    // Activar modo edición
    btnEditar.addEventListener('click', () => {
        btnEditar.style.display = 'none';
        accionesEdicion.style.display = 'inline-block';
        if (btnCambiarFoto) btnCambiarFoto.style.display = 'flex';

        inputsEditables.forEach(input => {
            input.removeAttribute('readonly');
            input.classList.add('modo-edicion');
        });
    });

    // Cancelar modo edición
    btnCancelar.addEventListener('click', () => {
        btnEditar.style.display = 'inline-block';
        accionesEdicion.style.display = 'none';
        if (btnCambiarFoto) btnCambiarFoto.style.display = 'none';

        inputsEditables.forEach(input => {
            input.setAttribute('readonly', true);
            input.classList.remove('modo-edicion');
        });
    });
});