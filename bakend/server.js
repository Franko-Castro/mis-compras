import express from "express";
import cors from "cors";
import bodyParser from "body-parser";
import productosRoutes from "./routes/productos.js";
import pedidosRoutes from "./routes/pedidos.js";
import usuariosRoutes from "./routes/usuarios.js";

const app = express();
const PORT = 4000; // puedes cambiarlo si lo deseas

app.use(cors());
app.use(bodyParser.json());

// Rutas principales
app.use("/api/productos", productosRoutes);
app.use("/api/pedidos", pedidosRoutes);
app.use("/api/usuarios", usuariosRoutes);

app.get("/", (req, res) => {
  res.send("Servidor de Tienda Online funcionando ✅");
});

app.listen(PORT, () => {
  console.log(`🚀 Servidor escuchando en http://localhost:${PORT}`);
});
