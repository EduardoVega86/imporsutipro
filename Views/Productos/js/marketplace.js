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
                    <button class="btn btn-description" data-bs-toggle="modal" data-bs-target="#descripcion_productModal">Descripción</button>
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