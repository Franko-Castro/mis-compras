const params = new URLSearchParams(window.location.search);
const idProducto = params.get("id");

const form = document.getElementById("form-editar");
const idInput = document.getElementById("id_producto");
const nombre = document.getElementById("nombre");
const precio = document.getElementById("precio");
const descripcion = document.getElementById("descripcion");
const imagen = document.getElementById("imagen");
const preview = document.getElementById("preview");

// Cargar producto
fetch(`php/obtener_producto.php?id=${idProducto}`)
  .then(res => res.json())
  .then(data => {
    if (!data.exito) {
      alert("Error al cargar producto");
      return;
    }

    idInput.value = data.producto.id_producto;
    nombre.value = data.producto.nombre;
    precio.value = data.producto.precio;
    descripcion.value = data.producto.descripcion;
    preview.src = "imagenes/" + data.producto.imagen;
  });

// Preview imagen
imagen.addEventListener("change", e => {
  const file = e.target.files[0];
  if (file) preview.src = URL.createObjectURL(file);
});

// Enviar formulario
form.addEventListener("submit", e => {
  e.preventDefault();

  const formData = new FormData(form);

  fetch("php/actualizar_producto.php", {
    method: "POST",
    body: formData
  })
  .then(res => res.json())
  .then(data => {
    if (data.exito) {
      alert("Producto actualizado");
      window.location.href = "perfil.html";
    } else {
      alert(data.mensaje);
    }
  });
});
