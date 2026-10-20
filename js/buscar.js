function crearTarjetaProducto(p) {
  const archivo = (p.imagen || "").trim();
  const rutas = [
    "",
    "imagendes de productos/",
    "imagenes/",
    "php/uploads/",
    "uploads/"
  ];

  const div = document.createElement("div");
  div.className = "tarjeta-producto";
  div.innerHTML = `
    <a href="producto.html?id=${p.id_producto}">
        <img src="" alt="${p.nombre}">
    </a>
    <h3>${p.nombre}</h3>
    <p class="precio-destacado">$${p.precio}</p>
    <div style="display: flex; gap: 8px; margin-top: 10px;">
        <a class="boton-pequeno" href="producto.html?id=${p.id_producto}" style="flex: 1; text-align: center; display: flex; align-items: center; justify-content: center; background: #3b82f6; color: white; text-decoration: none; border-radius: 4px;">Ver</a>
        ${parseInt(p.verificado) === 1 ? `
        <button class="boton-pequeno agregar-carrito" 
                data-id="${p.id_producto}" 
                data-nombre="${p.nombre}" 
                data-precio="${p.precio}" 
                data-imagen="imagenes/${archivo}"
                style="flex: 1; border: none; cursor: pointer; background: #f97316; color: white; border-radius: 4px;">
            + Carrito
        </button>
        ` : `
        <a href="https://wa.me/${p.whatsapp ? p.whatsapp.replace(/\D/g, '') : ''}?text=${encodeURIComponent('Hola, me interesa el producto: ' + p.nombre)}" 
           target="_blank"
           class="boton-pequeno" 
           style="flex: 1; text-align: center; display: flex; align-items: center; justify-content: center; background: #25d366; color: white; text-decoration: none; border-radius: 4px;">
            <i class="fab fa-whatsapp" style="margin-right: 5px;"></i> WhatsApp
        </a>
        `}
    </div>
  `;

  const img = div.querySelector("img");
  let idx = 0;

  const intentarSiguiente = () => {
    if (!archivo || idx >= rutas.length) {
      img.src = "imagenes/placeholder.png";
      return;
    }
    img.src = rutas[idx] + archivo;
    idx += 1;
  };

  img.onerror = intentarSiguiente;
  intentarSiguiente();

  return div;
}


async function buscarPorTexto(texto) {
  const resp = await fetch(`php/buscar_productos.php?q=${encodeURIComponent(texto)}`);
  if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
  return await resp.json();
}

async function buscarPorCategoria(idCategoria) {
  const resp = await fetch(`php/buscar_por_categoria.php?id=${encodeURIComponent(idCategoria)}`);
  if (!resp.ok) throw new Error(`HTTP ${resp.status}`);
  return await resp.json();
}

async function obtenerNombreCategoria(id) {
  try {
    const resp = await fetch("php/obtener_categorias.php");
    if (!resp.ok) return null;
    const data = await resp.json();
    if (data.exito && data.categorias) {
      const cat = data.categorias.find(c => String(c.id_categoria) === String(id));
      return cat ? cat.nombre : null;
    }
  } catch (e) {
    console.error("Error al obtener categorias:", e);
  }
  return null;
}

async function cargarResultados() {
  const params = new URLSearchParams(window.location.search);
  const q = params.get("q");
  const categoria = params.get("categoria");

  const titulo = document.getElementById("titulo-resultados");
  const estado = document.getElementById("estado-resultados");
  const grilla = document.getElementById("grilla-resultados");

  estado.textContent = "Cargando...";
  grilla.innerHTML = "";

  try {
    let data;

    if (q) {
      titulo.textContent = `Resultados para "${q}"`;
      data = await buscarPorTexto(q);
    } else if (categoria) {
      const nombre = await obtenerNombreCategoria(categoria);
      titulo.textContent = nombre ? `Categoria: ${nombre}` : "Categoria";
      data = await buscarPorCategoria(categoria);
    } else {
      titulo.textContent = "Resultados";
      estado.textContent = "No hay una busqueda activa.";
      return;
    }

    if (!data.exito || !data.productos || data.productos.length === 0) {
      estado.textContent = "No hay resultados.";
      return;
    }

    estado.textContent = "";
    data.productos.forEach(p => grilla.appendChild(crearTarjetaProducto(p)));
  } catch (error) {
    console.error("Error en la busqueda:", error);
    estado.textContent = "Error cargando resultados.";
  }
}

document.addEventListener("DOMContentLoaded", cargarResultados);
