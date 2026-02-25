document.addEventListener('DOMContentLoaded', () => {
  const modal = document.getElementById('modal-acceso');
  const cerrar = document.querySelector('.cerrar');
  const formLogin = document.getElementById('form-login');
  const formRegistro = document.getElementById('form-registro');
  const btnLogin = document.getElementById('btn-login');

  // Mostrar modal automáticamente al cargar
  modal.style.display = 'flex';

  cerrar.onclick = () => modal.style.display = 'none';
  window.onclick = (e) => { if (e.target === modal) modal.style.display = 'none'; };

  document.getElementById('mostrar-registro').onclick = () => {
    formLogin.style.display = 'none';
    formRegistro.style.display = 'block';
  };

  document.getElementById('mostrar-login').onclick = () => {
    formRegistro.style.display = 'none';
    formLogin.style.display = 'block';
  };

  // Envío del formulario de registro
  document.getElementById('registro-form').addEventListener('submit', async (e) => {
    e.preventDefault();
    const formData = new FormData(e.target);

    const resp = await fetch('php/registro.php', { method: 'POST', body: formData });
    const data = await resp.json();

    if (data.success) {
      alert('🎉 Registro exitoso. ¡Bienvenido!');
      modal.style.display = 'none';
    } else {
      alert(data.message);
    }
  });

  // Login básico (demo)
  btnLogin.onclick = () => {
    alert('✅ Acceso exitoso (demo)');
    modal.style.display = 'none';
  };
});
