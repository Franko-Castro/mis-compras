import express from "express";
import { db } from "../db.js";

const router = express.Router();

router.post("/checkout", async (req, res) => {
  const { usuario_id, items, total, direccion } = req.body;

  if (!items || !items.length) {
    return res.status(400).json({ success: false, message: "El carrito está vacío." });
  }

  const conn = await db.getConnection();
  try {
    await conn.beginTransaction();

    // Crear pedido
    const [pedidoResult] = await conn.query(
      "INSERT INTO pedidos (id_usuario, total, fecha_pedido, estado, metodo_pago) VALUES (?, ?, NOW(), 'pendiente', 'Tarjeta')",
      [usuario_id || null, total]
    );

    const pedidoId = pedidoResult.insertId;

    // Insertar los productos del pedido
    for (const item of items) {
      await conn.query(
        "INSERT INTO detalle_pedido (id_pedido, id_producto, cantidad, precio_unitario, subtotal) VALUES (?, ?, ?, ?, ?)",
        [pedidoId, item.id, item.cantidad, item.precio, item.subtotal]
      );

      // Actualizar stock
      await conn.query("UPDATE productos SET stock = stock - ? WHERE id_producto = ?", [
        item.cantidad,
        item.id,
      ]);
    }

    await conn.commit();

    res.json({ success: true, message: "Pedido registrado con éxito", pedido_id: pedidoId });
  } catch (err) {
    await conn.rollback();
    console.error(err);
    res.status(500).json({ success: false, message: "Error al registrar el pedido" });
  } finally {
    conn.release();
  }
});

export default router;
