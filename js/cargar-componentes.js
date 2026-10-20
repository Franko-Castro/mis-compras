// Función para cargar un componente HTML
async function cargarComponente(contenedorId, rutaArchivo) {
    try {
        const url = new URL(rutaArchivo, new URL('.', window.location.href));
        const respuesta = await fetch(url.toString());
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
    await Promise.all([
        cargarComponente('contenedor-navegacion', 'navegacion.html'),
        cargarComponente('contenedor-pie', 'pie-pagina.html')
    ]);

    inicializarNavegacion();
    resaltarPaginaActual();
    actualizarMenuSegunSesion();

    
    inicializarBuscador();
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

function inicializarBuscador() {
    const buscador = document.getElementById("buscador");
    const contenedor = document.getElementById("contenedor-productos");
    const formBuscador = buscador ? buscador.closest("form") : null;

    if (!buscador || !contenedor) return; // evita errores

    let timeout;
    const ejecutarBusqueda = async () => {
        const texto = buscador.value.trim();

        if (texto === "") {
            contenedor.innerHTML = "";
            return;
        }

        const respuesta = await fetch(`php/buscar_productos.php?q=${encodeURIComponent(texto)}`);
        const data = await respuesta.json();

        if (!data.exito) {
            contenedor.innerHTML = "<p>No hay resultados</p>";
            return;
        }

        contenedor.innerHTML = "";

        data.productos.forEach(p => {
            const imgPath = p.imagen || "";
            contenedor.innerHTML += `
                <a href="producto.html?id=${p.id_producto}" class="producto-link">
                    <div class="producto">
                        <img src="imagenes/${imgPath}" width="80" 
                             onerror="this.onerror=null; this.src='imagendes de productos/${imgPath}'; this.onerror=()=>this.src='imagenes/placeholder.png'">
                        <h4>${p.nombre}</h4>
                        <p>$${p.precio}</p>
                    </div>
                </a>
            `;
        });
    };

    buscador.addEventListener("keyup", () => {
        clearTimeout(timeout);

        timeout = setTimeout(async () => {
            await ejecutarBusqueda();
        }, 300);
    });

    if (formBuscador) {
        formBuscador.addEventListener("submit", (e) => {
            e.preventDefault();
            const texto = buscador.value.trim();
            if (texto === "") {
                ejecutarBusqueda();
                return;
            }
            window.location.href = `buscar.html?q=${encodeURIComponent(texto)}`;
        });
    }
}
if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", cargarTodosLosComponentes);
} else {
    cargarTodosLosComponentes();
}
