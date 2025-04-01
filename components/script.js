document.addEventListener("DOMContentLoaded", () => {
  cargarSelects();

  document
    .getElementById("formularioProducto")
    .addEventListener("submit", async (e) => {
      e.preventDefault();

      if (!validarFormulario()) return;

      const materialesSeleccionados = Array.from(
        document.querySelectorAll('input[name="material"]:checked')
      ).map((cb) => cb.value);
      const data = {
        codigo: document.getElementById("codigo").value,
        nombre: document.getElementById("nombre").value,
        bodega: document.getElementById("bodega").value,
        sucursal: document.getElementById("sucursal").value,
        moneda: document.getElementById("moneda").value,
        precio: document.getElementById("precio").value,
        descripcion: document.getElementById("descripcion").value,
        materiales: materialesSeleccionados,
      };

      const response = await fetch("../php/guardar_producto.php", {
        method: "POST",
        headers: { "Content-Type": "application/json" },
        body: JSON.stringify(data),
      });

      const result = await response.json();
      if (result.status === "success") {
        alert("Producto guardado con éxito.");
      } else {
        alert("Error: " + result.message);
      }
    });
});

function validarFormulario() {
  const codigo = document.getElementById("codigo").value.trim();
  const nombre = document.getElementById("nombre").value.trim();
  const precio = document.getElementById("precio").value.trim();
  const descripcion = document.getElementById("descripcion").value.trim();
  const materiales = document.querySelectorAll(
    'input[name="material"]:checked'
  );
  const bodega = document.getElementById("bodega").value;
  const sucursal = document.getElementById("sucursal").value;
  const moneda = document.getElementById("moneda").value;

  const regexCodigo = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{5,15}$/;
  const regexPrecio = /^\d+(\.\d{1,2})?$/;

  if (!codigo)
    return alert("El código del producto no puede estar en blanco."), false;
  if (!regexCodigo.test(codigo))
    return (
      alert(
        "El código del producto debe contener letras y números (5-15 caracteres)."
      ),
      false
    );

  if (!nombre)
    return alert("El nombre del producto no puede estar en blanco."), false;
  if (nombre.length < 2 || nombre.length > 50)
    return (
      alert("El nombre del producto debe tener entre 2 y 50 caracteres."), false
    );

  if (!bodega) return alert("Debe seleccionar una bodega."), false;
  if (!sucursal) return alert("Debe seleccionar una sucursal."), false;
  if (!moneda) return alert("Debe seleccionar una moneda."), false;

  if (!precio)
    return alert("El precio del producto no puede estar en blanco."), false;
  if (!regexPrecio.test(precio))
    return (
      alert("El precio debe ser un número positivo con hasta dos decimales."),
      false
    );

  if (materiales.length < 2)
    return (
      alert("Debe seleccionar al menos dos materiales para el producto."), false
    );

  if (!descripcion)
    return (
      alert("La descripción del producto no puede estar en blanco."), false
    );
  if (descripcion.length < 10 || descripcion.length > 1000)
    return (
      alert("La descripción debe tener entre 10 y 1000 caracteres."), false
    );

  return true;
}

function cargarSelects() {
  fetch("../php/obtener_bodegas.php")
    .then((res) => res.json())
    .then((data) => {
      const select = document.getElementById("bodega");
      data.forEach((bodega) => {
        const option = document.createElement("option");
        option.value = bodega.id;
        option.textContent = bodega.nombre;
        select.appendChild(option);
      });
    });

  document.getElementById("bodega").addEventListener("change", function () {
    const idBodega = this.value;
    fetch(`../php/obtener_sucursales.php?id=${idBodega}`)
      .then((res) => res.json())
      .then((data) => {
        const select = document.getElementById("sucursal");
        select.innerHTML = '<option value="">Seleccione una sucursal</option>';
        data.forEach((sucursal) => {
          const option = document.createElement("option");
          option.value = sucursal.id;
          option.textContent = sucursal.nombre;
          select.appendChild(option);
        });
      });
  });

  fetch("../php/obtener_monedas.php")
    .then((res) => res.json())
    .then((data) => {
      const select = document.getElementById("moneda");
      data.forEach((moneda) => {
        const option = document.createElement("option");
        option.value = moneda.id;
        option.textContent = moneda.nombre;
        select.appendChild(option);
      });
    });
}
