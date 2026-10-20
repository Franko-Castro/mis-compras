import { test, expect } from '@playwright/test';

test('la página principal carga correctamente', async ({ page }) => {
  await page.goto('/');

  await expect(page).toHaveTitle('TiendaOnline - Inicio');

  const carrusel = page.locator('.carrusel');

  await expect(carrusel).toBeVisible();
  
});

//TEST DE PRUEBA DE INICO DE SESION DE USUARIO
test('la página de inicio de sesión carga correctamente', async ({ page }) => {

  await page.goto('http://localhost:8080/index.html');
  await page.getByRole('textbox', { name: 'Correo electrónico' }).click();
  await page.getByRole('textbox', { name: 'Correo electrónico' }).press('ControlOrMeta+V');
  await page.getByRole('textbox', { name: 'Correo electrónico' }).fill('franco120@gmail.com');
  await page.getByRole('textbox', { name: 'Contraseña' }).click();
  await page.getByRole('textbox', { name: 'Contraseña' }).fill('12345678');
  await page.getByRole('button', { name: 'Acceder' }).click();
});

//TEST DE PRUEBA DE REGISTRO DE USUARIO
test('la página de registro carga correctamente', async ({ page }) => {
  await page.goto('http://localhost:8080/index.html');
  await page.getByRole('button', { name: 'Crear Cuenta' }).click();
  await page.getByRole('textbox', { name: 'Nombre completo' }).click();
  await page.getByRole('textbox', { name: 'Nombre completo' }).fill('daniel');
  await page.getByRole('textbox', { name: 'Correo electrónico' }).click();
  await page.getByRole('textbox', { name: 'Correo electrónico' }).fill('dnaniel@gmail.com');
  await page.getByRole('textbox', { name: 'Contraseña' }).click();
  await page.getByRole('textbox', { name: 'Contraseña' }).fill('12345678');
  await page.getByRole('button', { name: 'Registrarse' }).click();
  await page.getByRole('button', { name: 'Cerrar' }).click();

});

//TEST DE PRUEBA DE BÚSQUEDA DE PRODUCTOS
test('prueba de búsqueda de productos', async ({ page }) => {
await page.goto('http://localhost:8080/index.html');
await page.getByRole('textbox', { name: 'Correo electrónico' }).click();
await page.getByRole('textbox', { name: 'Correo electrónico' }).fill('franco120@gmail.com');
await page.getByRole('textbox', { name: 'Contraseña' }).click();
await page.getByRole('textbox', { name: 'Contraseña' }).fill('12345678');
await page.getByRole('button', { name: 'Acceder' }).click();
await page.getByRole('textbox', { name: 'Buscar productos...' }).click();
await page.getByRole('textbox', { name: 'Buscar productos...' }).fill('trepadora');
await page.getByRole('button', { name: 'Buscar' }).click();
await page.getByRole('link', { name: 'Ver' }).first().click();
});

//TEST DE PRUEBA DE BÚSQUEDA DE PRODUCTOS al azar
test('prueba de búsqueda de productos al azar', async ({ page }) => {
  await page.goto('http://localhost:8080/index.html');
  await page.getByRole('textbox', { name: 'Correo electrónico' }).click();
  await page.getByRole('textbox', { name: 'Correo electrónico' }).fill('franco120@gmail.com');
  await page.getByRole('textbox', { name: 'Contraseña' }).click();
  await page.getByRole('textbox', { name: 'Contraseña' }).fill('12345678');
  await page.getByRole('button', { name: 'Acceder' }).click();
  await page.getByRole('link', { name: 'Productos' }).click();
  
  // Esperar a que los productos se carguen
  await page.waitForSelector('#productos-por-categoria .tarjeta-producto');
  
  const itemsContainer = await page.locator('#productos-por-categoria .tarjeta-producto').all();
  
  expect(itemsContainer.length).toBeGreaterThan(0);

  const randomIndex = Math.floor(Math.random() * itemsContainer.length);

  const randomItem = itemsContainer[randomIndex];

  const expectedName = await randomItem.locator('h3').innerText();
  const expectedPrice = await randomItem.locator('.precio-destacado').innerText();
  const expectedSeller = await randomItem.locator('.vendedor-nombre').innerText();

  console.log(`Nombre del producto: ${expectedName} precio: ${expectedPrice} vendedor: ${expectedSeller}`);
  
  // Validar que se obtuvieron datos
  expect(expectedName).toBeTruthy();
  expect(expectedPrice).toBeTruthy();
  expect(expectedSeller).toBeTruthy();
});

//TEST DE PRUEBA DE AGREGAR PRODUCTO AL CARRITO
test('prueba de agregar producto al carrito', async ({ page }) => {
  await page.goto('http://localhost:8080/index.html');
  await page.getByRole('textbox', { name: 'Correo electrónico' }).click();
  await page.getByRole('textbox', { name: 'Correo electrónico' }).fill('franco120@gmail.com');
  await page.getByRole('textbox', { name: 'Contraseña' }).click();
  await page.getByRole('textbox', { name: 'Contraseña' }).fill('12345678');
  await page.getByRole('button', { name: 'Acceder' }).click();
  await page.getByRole('textbox', { name: 'Buscar productos...' }).click();
  await page.getByRole('textbox', { name: 'Buscar productos...' }).fill('trepadora');
  await page.getByRole('button', { name: 'Buscar' }).click();
  await page.getByRole('link', { name: 'Ver' }).first().click();
  await page.getByRole('button', { name: 'Agregar al carrito' }).click();
});

//TEST DE PRUEBA DE ELIMINAR PRODUCTO DEL CARRITO
test('prueba de eliminar producto del carrito', async ({ page }) => { 
  await page.goto('http://localhost:8080/index.html');
  await page.getByRole('textbox', { name: 'Correo electrónico' }).click();
  await page.getByRole('textbox', { name: 'Correo electrónico' }).fill('franco120@gmail.com');
  await page.getByRole('textbox', { name: 'Contraseña' }).click();
  await page.getByRole('textbox', { name: 'Contraseña' }).fill('12345678');
  await page.getByRole('button', { name: 'Acceder' }).click();
  await page.getByRole('textbox', { name: 'Buscar productos...' }).click();
  await page.getByRole('textbox', { name: 'Buscar productos...' }).fill('trepadora');
  await page.getByRole('button', { name: 'Buscar' }).click();
  await page.getByRole('link', { name: 'Ver' }).first().click();
  await page.getByRole('button', { name: ' Agregar al carrito' }).click();
  await page.getByRole('link', { name: 'Carrito' }).click();
  page.once('dialog', dialog => {
    console.log(`Dialog message: ${dialog.message()}`);
    dialog.dismiss().catch(() => {});
  });
  await page.getByRole('button', { name: '×' }).click();
});

