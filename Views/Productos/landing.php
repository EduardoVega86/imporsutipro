<?php require_once './Views/templates/header.php'; ?>
<?php
print_r($data);
$productoExiste =  $data->productoExiste();
if ($productoExiste == 0) {
?>

    <div class="container">
        <!-- no existe el producto -->
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">No existe el producto</h1>
            </div>

        </div>
    </div>
<?php
} else {
?>
    <div class="container">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center">Landing</h1>
            </div>
        </div>
    <?php
}
    ?>

    <?php require_once './Views/templates/footer.php'; ?>