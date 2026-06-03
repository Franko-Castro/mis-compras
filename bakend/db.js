import mysql from "mysql2/promise";

// Cambia los valores según tu entorno local
export const db = await mysql.createPool({
  host: "localhost",
  user: "root",
  password: "",
  database: "tienda_online",
  waitForConnections: true,
  connectionLimit: 10,
  queueLimit: 0,
});
