const params = new URLSearchParams(window.location.search);
const idProducto = params.get("id");

const form       = document.getElementById("form-editar");
const idInput    = document.getElementById("id_producto");
const nombre     = document.getElementById("nombre");
const precio     = document.getElementById("precio");
const descripcion= document.getElementById("descripcion");
const imagen     = document.getElementById("imagen");
const preview    = document.getElementById("preview");
const btnGuardar = form.querySelector("button[type='submit']");

// ── Cargar datos del producto ─────────────────────────────────────
if (!idProducto) {
  alert("No se especificó un producto para editar.");
  window.location.href = "perfil.html";
} else {
  fetch(`php/obtener_producto.php?id=${idProducto}`)
    .then(res => res.json())
    .then(data => {
      if (!data.exito) {
        alert("No se pudo cargar el producto. Puede que no exista.");
        window.location.href = "perfil.html";
        return;
      }
      const p = data.producto;
      idInput.value     = p.id_producto;
      nombre.value      = p.nombre;
      precio.value      = p.precio;
      descripcion.value = p.descripcion;
      
      if (p.imagen) {
        preview.src = "imagenes/" + p.imagen;
        preview.onerror = () => { preview.src = "imagenes/default-product.svg"; };
      }
    })
    .catch(() => {
      alert("Error de conexión al cargar el producto.");
      window.location.href = "perfil.html";
    });
}

// ── Preview de nueva imagen ───────────────────────────────────────
imagen.addEventListener("change", e => {
  const file = e.target.files[0];
  if (file) preview.src = URL.createObjectURL(file);
});

// ── Envío del formulario ──────────────────────────────────────────
form.addEventListener("submit", e => {
  e.preventDefault();

  // Estado de carga en el botón
  const textoOriginal = btnGuardar.innerHTML;
  btnGuardar.innerHTML = '<i class="fas fa-circle-notch fa-spin"></i> Guardando...';
  btnGuardar.disabled = true;

  const formData = new FormData(form);

  fetch("php/actualizar_producto.php", {
    method: "POST",
    body: formData
  })
  .then(res => {
    // Detectar si la respuesta no es JSON válido (ej. PHP con errores)
    const contentType = res.headers.get("content-type") || "";
    if (!contentType.includes("application/json")) {
      return res.text().then(text => {
        throw new Error("Respuesta inesperada del servidor: " + text.substring(0, 200));
      });
    }
    return res.json();
  })
  .then(data => {
    if (data.exito) {
      btnGuardar.innerHTML = '<i class="fas fa-check"></i> ¡Guardado!';
      btnGuardar.style.background = "#27ae60";
      setTimeout(() => window.location.href = "perfil.html", 1200);
    } else {
      alert("Error al guardar: " + (data.mensaje || "Problema desconocido."));
      btnGuardar.innerHTML = textoOriginal;
      btnGuardar.disabled = false;
    }
  })
  .catch(err => {
    console.error("Error de guardado:", err);
    alert("Error de conexión. Revisa la consola para más detalles.\n\n" + err.message);
    btnGuardar.innerHTML = textoOriginal;
    btnGuardar.disabled = false;
  });
});
