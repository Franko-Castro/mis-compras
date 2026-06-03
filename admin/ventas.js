
//  protección admin
const rol = localStorage.getItem("rol");
if (rol !== "admin") {
  window.location.href = "../index.html";
}

//  cargar ventas
async function cargarVentas() {
  try {
    const res = await fetch("../php/admin_obtener_ventas.php");
    const data = await res.json();

    const contenedor = document.getElementById("ventas");
    contenedor.innerHTML = "";

    if (!data.ventas || data.ventas.length === 0) {
      contenedor.innerHTML = "<p>No hay ventas registradas</p>";
      return;
    }

    data.ventas.forEach(v => {

      contenedor.innerHTML += `
        <div class="venta-card">
          
          <strong>Venta #${v.id_venta}</strong>

          <div class="venta-info">
            Cliente: ${v.nombre}<br>
            Total: $${v.total}<br>
            <span class="estado ${v.estado}">${v.estado}</span>
          </div>

          ${
            v.comprobante 
            ? `<img class="venta-img" src="../comprobantes/${v.comprobante}" onerror="this.src='../imagenes/default.png'">`
            : "<p>Sin comprobante</p>"
          }

          <div class="botones">

            ${
              v.estado === "pagado" 
              ? `
                <button class="btn-verificar" onclick="cambiarEstado(${v.id_venta}, 'verificado')">
                   Verificar
                </button>

                <button class="btn-rechazar" onclick="cambiarEstado(${v.id_venta}, 'rechazado')">
                  Rechazar
                </button>
              ` 
              : ""
            }

            ${
              v.estado === "verificado"
              ? `
                <button class="btn-entregado" onclick="cambiarEstado(${v.id_venta}, 'entregado')">
                   Marcar entregado
                </button>
              `
              : ""
            }

          </div>

        </div>
      `;
    });

  } catch (error) {
    console.error("Error cargando ventas:", error);
  }
}

//  cambiar estado
async function cambiarEstado(id, estado) {
  try {
    const formData = new FormData();
    formData.append("id_venta", id);
    formData.append("estado", estado);

    const res = await fetch("../php/actualizar_estado_venta.php", {
      method: "POST",
      body: formData
    });

    const data = await res.json();

    if (data.exito) {
      cargarVentas(); // 🔄 recargar
    } else {
      alert("Error al actualizar estado");
    }

  } catch (error) {
    console.error("Error:", error);
  }
}

//  iniciar
cargarVentas();
