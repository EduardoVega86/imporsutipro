document.addEventListener('DOMContentLoaded', function() {
    const productsPerPage = 10;
    let currentPage = 1;
    let products = [];

    const cardContainer = document.getElementById('card-container');
    const pagination = document.getElementById('pagination');

    async function fetchProducts() {
        try {
            const response = await fetch('' + SERVERURL + 'marketplace/obtener_productos');
            products = await response.json();
            displayProducts(products, currentPage, productsPerPage);
            createPagination(products.length, productsPerPage);
        } catch (error) {
            console.error('Error al obtener los productos:', error);
        }
    }

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
                    <h5 class="card-title">${product.nombre_producto}</h5>
                    <p class="card-text">Código: <strong>${product.codigo_producto}</strong></p>
                    <p class="card-text">Descripción: <strong>${product.descripcion_producto}</strong></p>
                    <p class="card-text">Categoría: <strong>${product.id_linea_producto}</strong></p>
                </div>
                <div>
                    <button class="btn btn-description" onclick="agregarModal_marketplace(${product.id_producto})">Descripción</button>
                    <button class="btn btn-import">Importar</button>
                </div>
            </div>
        `;
            cardContainer.appendChild(card);
        });
    }

    function createPagination(totalProducts, perPage = productsPerPage) {
        pagination.innerHTML = '';
        const totalPages = Math.ceil(totalProducts / perPage);

        const previousPageItem = document.createElement('li');
        previousPageItem.className = 'page-item';
        previousPageItem.innerHTML = `
        <button class="page-link" aria-label="Previous">
            <span aria-hidden="true">&laquo;</span>
        </button>
    `;
        previousPageItem.addEventListener('click', function() {
            if (currentPage > 1) {
                currentPage--;
                displayProducts(products, currentPage, productsPerPage);
                createPagination(totalProducts, perPage);
            }
        });
        pagination.appendChild(previousPageItem);

        for (let i = 1; i <= totalPages; i++) {
            const pageItem = document.createElement('li');
            pageItem.className = `page-item ${i === currentPage ? 'active' : ''}`;
            pageItem.innerHTML = `
            <button class="page-link">${i}</button>
        `;
            pageItem.addEventListener('click', function() {
                currentPage = i;
                displayProducts(products, currentPage, productsPerPage);
                createPagination(totalProducts, perPage);
            });
            pagination.appendChild(pageItem);
        }

        const nextPageItem = document.createElement('li');
        nextPageItem.className = 'page-item';
        nextPageItem.innerHTML = `
        <button class="page-link" aria-label="Next">
            <span aria-hidden="true">&raquo;</span>
        </button>
    `;
        nextPageItem.addEventListener('click', function() {
            if (currentPage < totalPages) {
                currentPage++;
                displayProducts(products, currentPage, productsPerPage);
                createPagination(totalProducts, perPage);
            }
        });
        pagination.appendChild(nextPageItem);

        updatePaginationButtons(totalPages);
    }

    function updatePaginationButtons(totalPages) {
        const previousPageItem = pagination.querySelector('.page-item:first-child');
        const nextPageItem = pagination.querySelector('.page-item:last-child');

        previousPageItem.classList.toggle('disabled', currentPage === 1);
        nextPageItem.classList.toggle('disabled', currentPage === totalPages);
    }

    fetchProducts();
});

function agregarModal_marketplace(id) {
    $.ajax({
        type: "POST",
        url: SERVERURL + "marketplace/obtener_producto/" + id,
        dataType: 'json',
        success: function (response) {
            console.log(response); // Depuración: Mostrar la respuesta en la consola
  
            if (response) {
                // Obtener el primer objeto de la respuesta
                const data = response;
  
                // Verificar que los elementos existen antes de asignarles valores
                if ($('.informacion_producto').length > 0) {
                    console.log('Elementos encontrados, actualizando valores...');
                    
                    // Llenar los elementos del modal con los datos recibidos
                    $('.informacion_producto').html(`
                        <h3 class="mb-3" style="text-decoration:underline;"><strong>Información</strong></h3>
                        <p class="texto_modal"><strong>Código del Producto:</strong> ${data.id_producto}</p>
                        <p class="texto_modal"><strong>Nombre Producto:</strong> ${data.nombre_producto}</p>
                        <p class="texto_modal"><strong>Precio Proveedor:</strong> $${data.costo_producto}</p>
                        <p class="texto_modal"><strong>Precio Sugerido:</strong> $${data.pcp}</p>
                        <p class="texto_modal"><strong>Stock:</strong> <span class="text-success"><strong>${data.saldo_stock}</strong></span></p>
                        <p class="texto_modal"><strong>Proveedor:</strong> <a href="${data.url_proveedor}" target="_blank">${data.url_proveedor}</a></p>
                        <p class="texto_modal"><a href="tel:${data.telefono_proveedor}">${data.telefono_proveedor}</a></p>
                    `);

                    // Suponiendo que hay un array de imágenes en la respuesta
                    if (data.imagenes && data.imagenes.length > 0) {
                        let carouselItems = '';
                        let thumbnails = '';
                        data.imagenes.forEach((imagen, index) => {
                            const activeClass = index === 0 ? 'active' : '';
                            carouselItems += `
                                <div class="carousel-item ${activeClass}">
                                    <img src="${imagen.url}" class="d-block w-100 fixed-size-img" alt="Product Image ${index + 1}">
                                </div>
                            `;
                            thumbnails += `
                                <img src="${imagen.url}" class="img-thumbnail mx-1" alt="Thumbnail ${index + 1}" data-bs-target="#productCarousel" data-bs-slide-to="${index}">
                            `;
                        });
                        $('.carousel-inner').html(carouselItems);
                        $('.carousel-thumbnails').html(thumbnails);
                    }

                    // Abrir el modal
                    $('#descripcion_productModal').modal('show');
                } else {
                    console.error("El elemento de información del producto no se encontró en el DOM.");
                }
            } else {
                console.error("La respuesta está vacía o tiene un formato incorrecto.");
            }
        },
        error: function (xhr, status, error) {
            console.error("Error en la solicitud AJAX:", error);
            alert("Hubo un problema al obtener la información del producto");
        },
    });
}
