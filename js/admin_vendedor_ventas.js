const id_vendedor = localStorage.getItem("id_usuario");

async function cargarVentas() {
  const res = await fetch(`../php/vendedor_obtener_ventas.php?id_vendedor=${id_vendedor}`);
  const data = await res.json();

  const contenedor = document.getElementById("ventas");
  contenedor.innerHTML = "";

  data.ventas.forEach(v => {
    contenedor.innerHTML += `
      <div style="background:white;padding:15px;margin-bottom:10px;border-radius:10px;">
        
        <strong>Venta #${v.id_venta}</strong><br>
        Producto: ${v.producto}<br>
        Cantidad: ${v.cantidad}<br>
        Total: $${v.total}<br>
        Estado: <strong>${v.estado}</strong><br>

        ${
          v.estado === "verificado"
          ? "<p style='color:green;'>📦 Debes enviar este producto</p>"
          : ""
        }

      </div>
    `;
  });
}

cargarVentas();