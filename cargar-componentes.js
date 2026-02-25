// Función para cargar un componente HTML
async function cargarComponente(contenedorId, rutaArchivo) {
    try {
        const respuesta = await fetch(rutaArchivo);
        if (!respuesta.ok) {
            throw new Error(`Error al cargar ${rutaArchivo}: ${respuesta.status}`);
        }
        const html = await respuesta.text();
        const contenedor = document.getElementById(contenedorId);
        if (contenedor) {
            contenedor.innerHTML = html;
        }
    } catch (error) {
        console.error('Error cargando componente:', error);
    }
}

// Función para cargar todos los componentes
async function cargarTodosLosComponentes() {
    // Cargar navegación y pie
    await Promise.all([
        cargarComponente('contenedor-navegacion', 'navegacion.html'),
        cargarComponente('contenedor-pie', 'pie-pagina.html')
    ]);

    // Inicializar menú
    inicializarNavegacion();
    resaltarPaginaActual();
    actualizarMenuSegunSesion();

    // 🔥 Control de sesión (AHORA el menú ya existe)
    actualizarMenuSegunSesion();
}

// Función para inicializar la navegación móvil
function inicializarNavegacion() {
    const botonHamburguesa = document.querySelector('.boton-hamburguesa');
    const menuPrincipal = document.querySelector('.menu-principal');

    if (botonHamburguesa && menuPrincipal) {
        botonHamburguesa.addEventListener('click', () => {
            botonHamburguesa.classList.toggle('activo');
            menuPrincipal.classList.toggle('activo');
        });
    }

    // Cerrar menú móvil al hacer click en un enlace
    const enlacesNav = document.querySelectorAll('.enlace-nav');
    enlacesNav.forEach(enlace => {
        enlace.addEventListener('click', () => {
            if (botonHamburguesa && menuPrincipal) {
                botonHamburguesa.classList.remove('activo');
                menuPrincipal.classList.remove('activo');
            }
        });
    });
}

// Resalta la página actual en la navegación
function resaltarPaginaActual() {
    const paginaActual = window.location.pathname.split('/').pop();
    const enlacesNav = document.querySelectorAll('.enlace-nav');

    enlacesNav.forEach(enlace => {
        const href = enlace.getAttribute('href');
        if (href === paginaActual || (paginaActual === '' && href === 'index.html')) {
            enlace.classList.add('activo');
        }
    });
}

// 🔐 Mostrar/ocultar opciones del menú según login
function actualizarMenuSegunSesion() {
    const id_usuario = localStorage.getItem("id_usuario");

    const perfil = document.getElementById("menu-perfil");
    const login = document.getElementById("menu-login");
    const cerrarSesion = document.getElementById("menu-cerrar");

    if (id_usuario) {
        if (perfil) perfil.style.display = "block";
        if (login) login.style.display = "none";
        if (cerrarSesion) cerrarSesion.style.display = "block";
    } else {
        if (perfil) perfil.style.display = "none";
        if (login) login.style.display = "block";
        if (cerrarSesion) cerrarSesion.style.display = "none";
    }
}

// Cargar componentes al iniciar
document.addEventListener('DOMContentLoaded', cargarTodosLosComponentes);
