document.addEventListener("DOMContentLoaded", () => {
  const form = document.querySelector(".registro-form");

  form.addEventListener("submit", e => {
    e.preventDefault();

    const pass1 = form.querySelectorAll(".input")[2].value;
    const pass2 = form.querySelectorAll(".input")[3].value;

    if (pass1 !== pass2) {
      alert("⚠️ Las contraseñas no coinciden. Verifícalas.");
      return;
    }

    alert("🎉 ¡Cuenta creada exitosamente!");
    form.reset();
  });
});