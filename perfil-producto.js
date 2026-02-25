document.addEventListener("DOMContentLoaded", async () => {
    const params = new URLSearchParams(window.location.search);
    const id = params.get('id');
    
    if (!id) {
        mostrarError("ID de producto no proporcionado.");
        return;
    }

    try {
        const resp = await fetch(`php/obtener_producto.php?id=${id}`);
        
        if (!resp.ok) {
            throw new Error(`Error HTTP: ${resp.status}`);
        }
        
        const data = await resp.json();
        
        if (!data.exito || !data.producto) {
            mostrarError(data.mensaje || "Producto no encontrado");
            return;
        }
        
        mostrarProducto(data.producto);
        
    } catch (error) {
        console.error("Error:", error);
        mostrarError(`Error al cargar el producto: ${error.message}`);
    }
});

function mostrarProducto(producto) {
    document.getElementById("perfil-producto").innerHTML = `
        <div class="producto-detalle">
            <img src="${producto.imagen_url}" alt="${producto.nombre}">
            <div class="producto-info">
                <h2>${producto.nombre}</h2>
                <p class="descripcion">${producto.descripcion}</p>
                <p class="precio">$${producto.precio}</p>
                <div class="vendedor-info">
                    <h3>Vendedor: ${producto.vendedor}</h3>
                    <p>Contacto: ${producto.telefono_vendedor || 'No disponible'}</p>
                </div>
            </div>
        </div>
    `;
}

function mostrarError(mensaje) {
    document.getElementById("perfil-producto").innerHTML = `
        <div class="error">
            <p>${mensaje}</p>
            <a href="index.html" class="btn-volver">Volver al inicio</a>
        </div>
    `;
}