document.addEventListener('DOMContentLoaded', function () {
    // Elementos del DOM
    const modal = document.getElementById('modal-registro');
    const btnRegistro = document.getElementById('btn-registro'); // Asegúrate de tener este ID en tu botón de registro
    const spanCerrar = document.getElementsByClassName('cerrar-modal')[0];

    // Abrir el modal
    if (btnRegistro) {
        btnRegistro.onclick = function () {
            modal.style.display = 'block';
        }
    }

    // Cerrar el modal con la 'x'
    if (spanCerrar) {
        spanCerrar.onclick = function () {
            modal.style.display = 'none';
        }
    }

    // Cerrar el modal al hacer clic fuera de él
    window.onclick = function (event) {
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }

    // Manejar el envío del formulario
    const form = document.getElementById('formulario-registro');
    if (form) {
        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Prevenir el envío real del formulario
            alert('¡Gracias por registrarte!');
            modal.style.display = 'none';
            // Aquí podrías agregar código para enviar los datos a un servidor
        });
    }
});
