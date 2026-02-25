document.addEventListener('DOMContentLoaded', function () {
  const modal = document.getElementById('modal-registro');
  const cerrar = document.querySelector('.cerrar-modal');
  const form = document.getElementById('formulario-registro');

  // 🔹 Mostrar el modal con un retardo o un botón
  setTimeout(() => {
    modal.style.display = 'flex';
  }, 800); // aparece automáticamente 0.8s después de cargar

  // 🔹 Cerrar al hacer clic en la X
  cerrar.addEventListener('click', () => {
    modal.style.display = 'none';
  });

  // 🔹 Cerrar si hace clic fuera del recuadro
  window.addEventListener('click', (e) => {
    if (e.target === modal) modal.style.display = 'none';
  });

  // 🔹 Enviar formulario
  form.addEventListener('submit', async (e) => {
    e.preventDefault();
    const datos = new FormData(form);
    try {
      const resp = await fetch('php/registro.php', { method: 'POST', body: datos });
      const result = await resp.json();

      if (result.success) {
        alert('🎉 ¡Registro exitoso!');
        modal.style.display = 'none';
      } else {
        alert('⚠️ ' + result.message);
      }
    } catch (error) {
      alert('❌ Error al conectar con el servidor.');
      console.error(error);
    }
  });
});


document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('modal-acceso');
  const cerrar = document.getElementById('cerrar-modal');
  const tabLogin = document.getElementById('tab-login');
  const tabRegistro = document.getElementById('tab-registro');
  const formLogin = document.getElementById('form-login');
  const formRegistro = document.getElementById('form-registro');

  // ✅ Mostrar modal automáticamente al cargar (puedes quitarlo si prefieres abrirlo manualmente)
  modal.style.display = 'flex';

  // 🔄 Alternar pestañas
  tabLogin.addEventListener('click', () => {
    tabLogin.classList.add('active');
    tabRegistro.classList.remove('active');
    formLogin.classList.add('active');
    formRegistro.classList.remove('active');
  });
  tabRegistro.addEventListener('click', () => {
    tabRegistro.classList.add('active');
    tabLogin.classList.remove('active');
    formRegistro.classList.add('active');
    formLogin.classList.remove('active');
  });

  // ❌ Cerrar modal
  cerrar.addEventListener('click', () => modal.style.display = 'none');
  window.addEventListener('click', (e) => {
    if (e.target === modal) modal.style.display = 'none';
  });
});

