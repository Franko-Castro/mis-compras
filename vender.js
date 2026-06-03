document.getElementById("form-vender").addEventListener("submit", async (e) => {
  e.preventDefault();

  const id_vendedor = localStorage.getItem("id_usuario");
  if (!id_vendedor) {
    alert("⚠️ Debes iniciar sesión para publicar productos.");
    return;
  }

  const formData = new FormData(e.target);
  formData.append("id_vendedor", id_vendedor);

  const response = await fetch("php/vender.php", {
    method: "POST",
    body: formData
  });

  const result = await response.json();
  alert(result.message);
  if (result.success) e.target.reset();
});
