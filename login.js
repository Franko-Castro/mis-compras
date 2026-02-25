// 🧠 login.js — Maneja el inicio de sesión de usuarios

document.addEventListener("DOMContentLoaded", () => {
  const formLogin = document.getElementById("form-login");

  if (!formLogin) {
    console.error("No se encontró el formulario de login (id='form-login')");
    return;
  }

  formLogin.addEventListener("submit", async (e) => {
    e.preventDefault();

    const formData = new FormData(formLogin);

    try {
      const response = await fetch("php/login.php", {
        method: "POST",
        body: formData,
      });

      // Leer siempre la respuesta como texto primero (evita fallos si el servidor imprime warnings)
      const text = await response.text();

      if (!response.ok) {
        console.error("Error HTTP al llamar a php/login.php:", response.status, text);
        alert("Error en el servidor (HTTP " + response.status + "). Revisa la consola.");
        return;
      }

      let data;
      try {
        data = JSON.parse(text);
        console.log("🔹 Respuesta del servidor (JSON):", data);
      } catch (parseErr) {
        console.error("Respuesta del servidor no es JSON:", text);
        alert("Respuesta inválida del servidor — revisa la consola.");
        return;
      }

      // ✅ Si el login fue exitoso
      if (data.éxito || data.success) {
        const usuario = data.usuario || {};

        // Guardar los datos en localStorage
        localStorage.setItem("id_usuario", usuario.id);
        localStorage.setItem("nombre_usuario", usuario.nombre);

        // Mostrar un mensaje elegante
        const modal = document.createElement("div");
        modal.innerHTML = `
          <div style="
            position:fixed;top:0;left:0;width:100%;height:100%;
            background:rgba(0,0,0,0.6);
            display:flex;align-items:center;justify-content:center;
            z-index:9999;">
            <div style="
              background:#fff;padding:40px 50px;border-radius:16px;
              text-align:center;box-shadow:0 6px 18px rgba(0,0,0,0.3);
              font-family:'Poppins',sans-serif;">
              <div style="font-size:60px;color:#4CAF50;">✅</div>
              <h2 style="color:#2e7d32;">¡Inicio de sesión exitoso!</h2>
              <p>Bienvenido, <strong>${usuario.nombre}</strong>.</p>
              <p style="font-size:14px;color:#666;">Serás redirigido al inicio en unos segundos...</p>
            </div>
          </div>
        `;
        document.body.appendChild(modal);

        // Redirigir después de unos segundos
        setTimeout(() => {
          localStorage.setItem("id_usuario", data.usuario.id);
          window.location.href = "perfil.html";

        }, 2500);
      }
      // ⚠️ Si hubo un error (correo o contraseña incorrectos)
      else {
        alert(data.mensaje || data.message || "❌ Error al iniciar sesión.");
      }

    } catch (error) {
      console.error("❌ Error en login:", error);
      alert("Error de conexión con el servidor.");
    }
  });
});
