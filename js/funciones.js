"use strict";

// ---------------------------------------------------------
// 1. MENÚ PARA PANTALLAS PEQUEÑAS
// ---------------------------------------------------------
const botonMenu = document.querySelector(".boton-menu");
const menuPrincipal = document.querySelector("#menu-principal");

if (botonMenu && menuPrincipal) {
    botonMenu.addEventListener("click", function () {
        const abierto = menuPrincipal.classList.toggle("menu-abierto");
        botonMenu.setAttribute("aria-expanded", String(abierto));
    });
}

// ---------------------------------------------------------
// 2. FILTRO DE PRODUCTOS POR CATEGORÍA
// ---------------------------------------------------------
const botonesFiltro = document.querySelectorAll(".filtro");
const productos = document.querySelectorAll(".tarjeta-producto[data-categoria]");
const resultadoFiltro = document.querySelector("#resultado-filtro");
const sinResultados = document.querySelector("#sin-resultados");

botonesFiltro.forEach(function (boton) {
    boton.addEventListener("click", function () {
        const categoria = boton.dataset.filtro;
        let cantidadVisible = 0;

        botonesFiltro.forEach(function (elemento) {
            elemento.classList.remove("activo");
        });

        boton.classList.add("activo");

        productos.forEach(function (producto) {
            const coincide = categoria === "todos" || producto.dataset.categoria === categoria;
            producto.hidden = !coincide;

            if (coincide) {
                cantidadVisible++;
            }
        });

        if (resultadoFiltro) {
            resultadoFiltro.textContent = `Se muestran ${cantidadVisible} producto(s).`;
        }

        if (sinResultados) {
            sinResultados.hidden = cantidadVisible !== 0;
        }
    });
});

// ---------------------------------------------------------
// 3. CÁLCULO Y VALIDACIÓN DEL FORMULARIO DE COMPRA
// ---------------------------------------------------------
const formularioCompra = document.querySelector("#formulario-compra");
const precio = document.querySelector("#precio");
const cantidad = document.querySelector("#cantidad");
const totalMostrado = document.querySelector("#total-mostrado");
const mensajeValidacion = document.querySelector("#mensaje-validacion");

function formatearPesos(valor) {
    return new Intl.NumberFormat("es-CO", {
        style: "currency",
        currency: "COP",
        maximumFractionDigits: 0
    }).format(valor);
}

function calcularTotal() {
    if (!precio || !cantidad || !totalMostrado) {
        return;
    }

    const valorPrecio = Number(precio.value);
    const valorCantidad = Number(cantidad.value);
    const total = valorPrecio * valorCantidad;

    totalMostrado.value = formatearPesos(total);
}

if (cantidad) {
    cantidad.addEventListener("input", calcularTotal);
    calcularTotal();
}

if (formularioCompra) {
    formularioCompra.addEventListener("submit", function (evento) {
        mensajeValidacion.textContent = "";

        if (!formularioCompra.checkValidity()) {
            evento.preventDefault();
            mensajeValidacion.textContent = "Revise los campos obligatorios antes de continuar.";
            formularioCompra.reportValidity();
            return;
        }

        const confirmar = window.confirm("¿Desea registrar esta compra?");

        if (!confirmar) {
            evento.preventDefault();
            mensajeValidacion.textContent = "La compra no fue enviada.";
        }
    });
}
