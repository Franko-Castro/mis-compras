import express from "express";
import { db } from "../db.js";
import bcrypt from "bcrypt";

const router = express.Router();

// Registrar nuevo usuario
router.post("/registro", async (req, res) => {
  const { nombre, correo, contraseña } = req.body;
  try {
    const hash = await bcrypt.hash(contraseña, 10);
    await db.query("INSERT INTO usuarios (nombre, correo, contraseña) VALUES (?, ?, ?)", [
      nombre,
      correo,
      hash,
    ]);
    res.json({ success: true, message: "Usuario registrado con éxito" });
  } catch (err) {
    console.error(err);
    res.status(500).json({ error: "Error al registrar usuario" });
  }
});

export default router;
