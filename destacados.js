document.addEventListener('DOMContentLoaded', () => {
  const cont = document.getElementById('destacados-por-categoria');

  async function cargar() {
    try {
      const resp = await fetch('php/obtener_destacados_por_categoria.php');
      const data = await resp.json();

      if (!data || !data.exito) {
        cont.innerHTML = '<p>No se pudieron cargar los destacados.</p>';
        return;
      }

      cont.innerHTML = '';

      data.categorias.forEach(cat => {
        if (!cat.productos || cat.productos.length === 0) return;

        const section = document.createElement('div');
        section.className = 'categoria-destacada';

        const h3 = document.createElement('h3');
        h3.textContent = cat.categoria;
        h3.className = 'categoria-destacada-titulo';
        section.appendChild(h3);

        const row = document.createElement('div');
        row.className = 'grilla-productos categorias';

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
          vendedor.innerHTML = p.id_vendedor ? `Vendido por <a href="vendedor.html?id=${p.id_vendedor}"><strong>${p.vendedor}</strong></a>` : `<strong>${p.vendedor}</strong>`;

          const ver = document.createElement('a');
          ver.className = 'boton-pequeno';
          ver.href = `producto.html?id=${p.id_producto}`;
          ver.textContent = 'Ver';

          card.appendChild(img);
          card.appendChild(nombre);
          card.appendChild(precio);
          card.appendChild(vendedor);
          card.appendChild(ver);

          row.appendChild(card);
        });

        section.appendChild(row);
        cont.appendChild(section);
      });

    } catch (err) {
      console.error(err);
      cont.innerHTML = '<p>Error cargando destacados.</p>';
    }
  }

  cargar();
});