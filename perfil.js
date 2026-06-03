document.addEventListener("DOMContentLoaded", () => {

    const id_usuario = localStorage.getItem("id_usuario");

    if (!id_usuario) {
        alert("No has iniciado sesión.");
        window.location.href = "login.html";
        return;
    }

    /* =========================
       CARGAR DATOS DEL USUARIO
    ========================== */
    fetch("php/obtener_usuario.php?id=" + id_usuario)
        .then(res => res.json())
        .then(data => {
            const usuario = data.usuario || {};
            document.getElementById("nombre").textContent = usuario.nombre || "";
            document.getElementById("email").textContent = usuario.email || "";
            document.getElementById("fecha").textContent = usuario.fecha_registro || "";
        })
        .catch(err => console.error("Error usuario:", err));

    /* =========================
       CARGAR PRODUCTOS
    ========================== */
    fetch("php/mis_productos.php?id=" + id_usuario)
        .then(res => res.json())
        .then(data => {

            const productos = (data && data.productos) ? data.productos : [];
            const contenedor = document.getElementById("lista-productos");
            contenedor.innerHTML = "";

            if (!productos.length) {
                contenedor.innerHTML = "<p>No tienes productos publicados.</p>";
                return;
            }

            productos.forEach(prod => {
                contenedor.innerHTML += `
                    <div class="producto-card">

                        <a href="php/producto.php?id=${prod.id_producto}">
                            <img 
                                src="${prod.imagen}"
                                alt="${prod.nombre}"
                                class="producto-imagen"
                                onerror="this.onerror=null;this.src='../imagenes/default-product.svg'"
                            >
                        </a>

                        <h4>
                            <a href="php/producto.php?id=${prod.id_producto}">
                                ${prod.nombre}
                            </a>
                        </h4>

                        <p>$${prod.precio}</p>

                        <a href="php/producto.php?id=${prod.id_producto}" 
                           class="btn-simple btn-ver">
                           Ver producto
                        </a>

                    </div>
                `;
            });

        })
        .catch(err => console.error("Error productos:", err));

});
