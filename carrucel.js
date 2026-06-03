document.addEventListener("DOMContentLoaded", () => {
  const slides = document.querySelectorAll(".slide");
  const siguiente = document.querySelector(".siguiente");
  const anterior = document.querySelector(".anterior");
  const indicadores = document.querySelector(".indicadores");

  let indice = Array.from(slides).findIndex(s => s.classList.contains('activo'));
  if (indice === -1) indice = 0;

  // Crear botones de indicador
  slides.forEach((_, i) => {
    const btn = document.createElement("button");
    if (i === indice) btn.classList.add("activo");
    indicadores.appendChild(btn);
  });

  const botones = indicadores.querySelectorAll("button");

  function mostrarSlide(n) {
    slides.forEach((s, i) => {
      s.classList.toggle('activo', i === n);
    });

    botones.forEach(b => b.classList.remove("activo"));
    botones[n].classList.add("activo");

    indice = n;
  }

  function siguienteSlide() {
    indice = (indice + 1) % slides.length;
    mostrarSlide(indice);
  }

  function anteriorSlide() {
    indice = (indice - 1 + slides.length) % slides.length;
    mostrarSlide(indice);
  }

  siguiente.addEventListener("click", siguienteSlide);
  anterior.addEventListener("click", anteriorSlide);
  botones.forEach((b, i) => b.addEventListener("click", () => mostrarSlide(i)));

  // Reproducción automática
  setInterval(siguienteSlide, 5000);

  mostrarSlide(indice);
});
