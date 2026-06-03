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

          const imgPath = p.imagen || "";
          const precioFormateado = `$${parseFloat(p.precio).toLocaleString()}`;
          
          card.innerHTML = `
            <img src="imagenes/${imgPath}" alt="${p.nombre}" 
                 onerror="this.onerror=null; this.src='imagendes de productos/${imgPath}'; this.onerror=()=>this.src='imagenes/placeholder.png'">
            <h3>${p.nombre}</h3>
            <p class="precio-destacado">${precioFormateado}</p>
            <p class="vendedor-nombre">
              Vendido por <a href="vendedor.html?id=${p.id_vendedor}"><strong>${p.vendedor}</strong></a>
            </p>
            <div style="display: flex; margin-top: 10px;">
                <a href="producto.html?id=${p.id_producto}" class="boton-pequeno" style="flex: 1; text-align: center; display: flex; align-items: center; justify-content: center; background: #3b82f6; color: white; text-decoration: none; border-radius: 4px; padding: 8px;">Ver</a>
            </div>
          `;

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