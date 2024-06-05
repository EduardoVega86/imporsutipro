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
    <div id="pagination" class="pagination">
        <!-- Botones de paginación se insertarán aquí -->
    </div>
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
        const pagination = document.getElementById('pagination');

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

        function displayPagination(totalProducts, perPage = productsPerPage) {
            pagination.innerHTML = '';
            const totalPages = Math.ceil(totalProducts / perPage);

            for (let i = 1; i <= totalPages; i++) {
                const pageButton = document.createElement('button');
                pageButton.className = 'btn btn-light';
                pageButton.innerText = i;
                pageButton.addEventListener('click', function() {
                    currentPage = i;
                    displayProducts(products, currentPage, productsPerPage);
                });
                pagination.appendChild(pageButton);
            }
        }

        displayProducts(products, currentPage, productsPerPage);
        displayPagination(products.length, productsPerPage);
    });
</script>


<?php require_once './Views/templates/footer.php'; ?>