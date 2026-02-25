document.addEventListener('DOMContentLoaded', () => {
  const cont = document.getElementById('productos-por-categoria');
  const filtroCheckbox = document.getElementById('filtro-mis');
  const mensaje = document.getElementById('filtro-mensaje');

  let allData = null; // cache de datos

  function render(categorias, soloMios = false) {
    cont.innerHTML = '';

    if (soloMios) {
      // Si no hay ninguna categoría con productos del usuario
      const total = categorias.reduce((acc, c) => acc + c.productos.length, 0);
      if (total === 0) {
        cont.innerHTML = '<p>No tienes productos publicados.</p>';
        return;
      }
    }

    categorias.forEach(cat => {
      if (!cat.productos || cat.productos.length === 0) return;

      const section = document.createElement('section');
      section.className = 'categoria-section';

      const h2 = document.createElement('h2');
      h2.className = 'categoria-titulo';
      h2.textContent = cat.categoria;
      section.appendChild(h2);

      const grid = document.createElement('div');
      grid.className = 'grilla-productos categorias';

      cat.productos.forEach(p => {
        const card = document.createElement('div');
        card.className = 'tarjeta-producto';

        const img = document.createElement('img');
        img.src = p.imagen ? `imagenes/${p.imagen}` : 'imagenes/default-product.svg';
        img.alt = p.nombre;
        img.onerror = () => img.src = 'imagenes/default-product.svg';

        const nombre = document.createElement('h3');
        nombre.textContent = p.nombre;

        const precio = document.createElement('p');
        precio.className = 'precio-destacado';
        precio.textContent = `$${parseFloat(p.precio).toFixed(2)}`;

        const vendedor = document.createElement('p');
        vendedor.className = 'vendedor-nombre';
        if (p.id_vendedor) {
          vendedor.innerHTML = `Publicado por <a href="vendedor.html?id=${p.id_vendedor}"><strong>${p.vendedor}</strong></a>`;
        } else {
          vendedor.innerHTML = `Publicado por <strong>${p.vendedor}</strong>`;
        }

        const ver = document.createElement('a');
        ver.className = 'boton-pequeno';
        ver.href = `producto.html?id=${p.id_producto}`;
        ver.textContent = 'Ver';

        card.appendChild(img);
        card.appendChild(nombre);
        card.appendChild(precio);
        card.appendChild(vendedor);
        card.appendChild(ver);

        grid.appendChild(card);
      });

      section.appendChild(grid);
      cont.appendChild(section);
    });
  }

  async function cargar() {
    try {
      const resp = await fetch('php/obtener_productos_por_categoria.php');
      const data = await resp.json();

      if (!data || !data.exito || !data.categorias) {
        cont.innerHTML = '<p>No se pudieron cargar los productos.</p>';
        return;
      }

      allData = data.categorias;

      // render inicial
      render(allData);

    } catch (err) {
      console.error(err);
      cont.innerHTML = '<p>Error cargando productos.</p>';
    }
  }

  function aplicarFiltroSoloMios(activo) {
    const idUsuario = localStorage.getItem('id_usuario');
    if (activo) {
      if (!idUsuario) {
        mensaje.textContent = 'Inicia sesión para ver tus productos';
        filtroCheckbox.checked = false;
        return;
      }

      mensaje.textContent = '';

      // Filtrar por usuario y remover categorías sin productos
      const filtradas = allData.map(cat => ({
        categoria: cat.categoria,
        productos: cat.productos.filter(p => p.id_vendedor && String(p.id_vendedor) === String(idUsuario))
      })).filter(c => c.productos.length > 0);

      if (filtradas.length === 0) {
        cont.innerHTML = '<p>No tienes productos publicados.</p>';
        return;
      }

      render(filtradas, true);
    } else {
      mensaje.textContent = '';
      render(allData);
    }
  }

  // Event listener del checkbox
  filtroCheckbox.addEventListener('change', (e) => {
    aplicarFiltroSoloMios(e.target.checked);
  });

  // Cargar datos por primera vez
  cargar();
});