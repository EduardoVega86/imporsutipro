<?php require_once './Views/templates/header.php'; ?>
<link rel="stylesheet" type="text/css" href="<?php echo SERVERURL ?>/Views/Productos/css/marketplace.css">
<?php require_once './Views/Productos/Modales/descripcion_marketplace.php'; ?>

<div class="custom-container-fluid mt-4">
    <div class="row mb-3">
        <div class="col-md-4 mb-3 mb-md-0">
            <input type="text" class="form-control" placeholder="Código o Nombre">
        </div>
        <div class="col-md-4 mb-3 mb-md-0">
            <select class="form-control">
                <option>-- Selecciona Línea --</option>
                <option>Línea 1</option>
                <option>Línea 2</option>
                <option>Línea 3</option>
            </select>
        </div>
        <div class="col-md-3 mb-3 mb-md-0">
            <select class="form-control">
                <option>Selecciona una opción</option>
                <option>Opción 1</option>
                <option>Opción 2</option>
                <option>Opción 3</option>
            </select>
        </div>
        <div class="col-md-1">
            <button class="btn btn-warning w-100"><i class="fa fa-search"></i></button>
        </div>
    </div>
    <div id="card-container" class="card-container">
        <!-- Tarjetas de productos se insertarán aquí -->
    </div>
    <nav aria-label="Page navigation">
        <ul class="pagination justify-content-center">
            <li class="page-item">
                <button class="page-link" id="previous-page" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </button>
            </li>
            <li class="page-item">
                <button class="page-link" id="next-page" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </button>
            </li>
        </ul>
    </nav>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsPerPage = 10;
        let currentPage = 1;
        const products = [{
                img: "https://significado.com/wp-content/uploads/Imagen-Animada.jpg",
                title: "Producto 1",
                stock: 8,
                precioProveedor: 12.50,
                precioSugerido: 27.50,
                proveedor: "CLIJISPOREPRS"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            }, {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            }, {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },{
                img: "https://significado.com/wp-content/uploads/Imagen-Animada.jpg",
                title: "Producto 1",
                stock: 8,
                precioProveedor: 12.50,
                precioSugerido: 27.50,
                proveedor: "CLIJISPOREPRS"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },{
                img: "https://significado.com/wp-content/uploads/Imagen-Animada.jpg",
                title: "Producto 1",
                stock: 8,
                precioProveedor: 12.50,
                precioSugerido: 27.50,
                proveedor: "CLIJISPOREPRS"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            },
            {
                img: "https://img.freepik.com/foto-gratis/colores-arremolinados-interactuan-danza-fluida-sobre-lienzo-que-muestra-tonos-vibrantes-patrones-dinamicos-que-capturan-caos-belleza-arte-abstracto_157027-2892.jpg",
                title: "Producto 2",
                stock: 5,
                precioProveedor: 10.00,
                precioSugerido: 20.00,
                proveedor: "ANOTHERPROVIDER"
            }
        ];

        const cardContainer = document.getElementById('card-container');
    const previousPageButton = document.getElementById('previous-page');
    const nextPageButton = document.getElementById('next-page');

    function displayProducts(products, page = 1, perPage = productsPerPage) {
        cardContainer.innerHTML = '';
        const start = (page - 1) * perPage;
        const end = start + perPage;
        const paginatedProducts = products.slice(start, end);

        paginatedProducts.forEach(product => {
            const card = document.createElement('div');
            card.className = 'card card-custom';
            card.innerHTML = `
                <img src="${product.img}" class="card-img-top" alt="Product Image">
                <div class="card-body text-center d-flex flex-column justify-content-between">
                    <div>
                        <h5 class="card-title">${product.title}</h5>
                        <p class="card-text">Stock: <strong style="color:green">${product.stock}</strong></p>
                        <p class="card-text">Precio Proveedor: <strong>$${product.precioProveedor.toFixed(2)}</strong></p>
                        <p class="card-text">Precio Sugerido: <strong>$${product.precioSugerido.toFixed(2)}</strong></p>
                        <p class="card-text">Proveedor: <a href="#">${product.proveedor}</a></p>
                    </div>
                    <div>
                        <button class="btn btn-description" data-bs-toggle="modal" data-bs-target="#descripcion_productModal">Descripción</button>
                        <button class="btn btn-import">Importar</button>
                    </div>
                </div>
            `;
            cardContainer.appendChild(card);
        });
    }

    function updatePaginationButtons(totalProducts, perPage = productsPerPage) {
        const totalPages = Math.ceil(totalProducts / perPage);
        previousPageButton.disabled = currentPage === 1;
        nextPageButton.disabled = currentPage === totalPages;
    }

    previousPageButton.addEventListener('click', function () {
        if (currentPage > 1) {
            currentPage--;
            displayProducts(products, currentPage, productsPerPage);
            updatePaginationButtons(products.length, productsPerPage);
        }
    });

    nextPageButton.addEventListener('click', function () {
        const totalPages = Math.ceil(products.length / productsPerPage);
        if (currentPage < totalPages) {
            currentPage++;
            displayProducts(products, currentPage, productsPerPage);
            updatePaginationButtons(products.length, productsPerPage);
        }
    });

    displayProducts(products, currentPage, productsPerPage);
    updatePaginationButtons(products.length, productsPerPage);
});
</script>


<?php require_once './Views/templates/footer.php'; ?>