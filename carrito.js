(function () {
  const STORAGE_KEY = 'miTienda_carrito_v1';

 
  function getCart() {
    const raw = localStorage.getItem(STORAGE_KEY);
    try {
      return raw ? JSON.parse(raw) : [];
    } catch (e) {
      console.error('Error parseando carrito:', e);
      return [];
    }
  }

  function saveCart(cart) {
    localStorage.setItem(STORAGE_KEY, JSON.stringify(cart));
  }

  function findIndex(cart, id) {
    return cart.findIndex(item => String(item.id) === String(id));
  }

  function parsePrice(v) {
    const n = Number(String(v).replace(/[^0-9.-]+/g, ''));
    return isNaN(n) ? 0 : n;
  }

  // ---- API global ----
  window.carrito = {
    addItem(product) {
      const cart = getCart();
      const idx = findIndex(cart, product.id);
      const precio = parsePrice(product.precio);

      if (idx > -1) {
        cart[idx].cantidad += 1;
        cart[idx].subtotal = +(cart[idx].cantidad * cart[idx].precio).toFixed(2);
      } else {
        cart.push({
          id: String(product.id),
          nombre: product.nombre,
          precio: +precio,
          imagen: product.imagen || '',
          cantidad: 1,
          subtotal: +precio
        });
      }
      saveCart(cart);
      if (typeof renderCart === 'function') renderCart();
    },

    removeItem(id) {
      const cart = getCart().filter(p => String(p.id) !== String(id));
      saveCart(cart);
      if (typeof renderCart === 'function') renderCart();
    },

    clear() {
      localStorage.removeItem(STORAGE_KEY);
      if (typeof renderCart === 'function') renderCart();
    },

    getItems: getCart,

    getTotal() {
      return getCart().reduce((sum, i) => sum + i.subtotal, 0).toFixed(2);
    },

    async checkout(extraData = {}) {
      const cart = getCart();
      if (!cart.length) {
        alert('Tu carrito está vacío.');
        return;
      }

      const usuario_id = localStorage.getItem("usuario_id",1) || null;
      const total = this.getTotal();

      const payload = {
        usuario_id,
        items: cart,
        total,
        direccion: extraData.direccion || null
      };

      try {
        const resp = await fetch('php/checkout.php', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        });

        const data = await resp.json();

        if (data.success) {
          this.clear();

          // ✅ Modal bonito de éxito
          const modal = document.createElement('div');
          modal.innerHTML = `
            <div style="
              position:fixed;top:0;left:0;width:100%;height:100%;
              background:rgba(0,0,0,0.6);
              display:flex;align-items:center;justify-content:center;
              z-index:9999;">
              <div style="
                background:#fff;padding:40px 50px;border-radius:16px;
                text-align:center;box-shadow:0 5px 15px rgba(0,0,0,0.3);
                font-family:'Poppins',sans-serif;">
                <div style="font-size:60px;">🎉</div>
                <h2 style="color:#2e7d32;">¡Compra realizada con éxito!</h2>
                <p style="color:#444;">Tu pedido ha sido registrado correctamente.</p>
                <p style="font-size:14px;color:#666;">Serás redirigido en unos segundos...</p>
              </div>
            </div>`;
          document.body.appendChild(modal);

          setTimeout(() => {
            window.location.href = 'gracias.html';
          }, 3000);
        } else {
          alert('⚠️ No se pudo completar la compra: ' + (data.message || 'Error desconocido.'));
        }
      } catch (e) {
        console.error('Error en checkout:', e);
        alert('⚠️ Error de conexión con el servidor.');
      }
    }
  };

  // ---- Detectar botones de "Agregar al carrito" ----
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.agregar-carrito');
    if (!btn) return;

    const { id, nombre, precio, imagen } = btn.dataset;
    window.carrito.addItem({ id, nombre, precio, imagen });
    btn.textContent = 'Añadido ✓';
    setTimeout(() => (btn.textContent = 'Agregar al Carrito'), 900);
  });

  // ---- Render carrito (solo en carrito.html) ----
  window.renderCart = function () {
    const lista = document.querySelector('.carrito-lista');
    const totalSpan = document.querySelector('.carrito-total span');
    if (!lista) return;

    const cart = getCart();
    lista.innerHTML = '';

    if (!cart.length) {
      lista.innerHTML = '<p>Tu carrito está vacío.</p>';
      if (totalSpan) totalSpan.textContent = '$0.00';
      return;
    }

    cart.forEach(item => {
      const div = document.createElement('div');
      div.className = 'carrito-item';
      div.innerHTML = `
        <img class="carrito-imagen" src="${item.imagen}" alt="${item.nombre}" onerror="this.onerror=null;this.src='imagenes/default-product.svg'">
        <div class="carrito-info">
          <h3>${item.nombre}</h3>
          <p>$${(+item.precio).toFixed(2)}</p>
          <p>Cantidad: <span class="cantidad">${item.cantidad}</span></p>
          <p>Subtotal: $${(+item.subtotal).toFixed(2)}</p>
        </div>
        <div class="carrito-acciones">
          <button class="carrito-eliminar" data-id="${item.id}" title="Eliminar producto" aria-label="Eliminar producto">&times;</button>
        </div>`; 
      lista.appendChild(div);
    });

    if (totalSpan) totalSpan.textContent = `$${window.carrito.getTotal()}`;
  };

  // Delegación: eliminar producto al click en el botón
  document.addEventListener('click', (e) => {
    const btn = e.target.closest('.carrito-eliminar');
    if (!btn) return;

    const id = btn.dataset.id;
    if (!id) return;

    if (confirm('¿Eliminar este producto del carrito?')) {
      window.carrito.removeItem(id);
    }
  });

  document.addEventListener('DOMContentLoaded', () => {
    renderCart();
    const btnFinalizar = document.querySelector('.carrito-btn');
    if (btnFinalizar) btnFinalizar.addEventListener('click', () => carrito.checkout());
  });
})();
