<?php require_once './Views/templates/header.php'; ?>
<?php require_once './Views/Productos/css/landing.php'; ?>
<?php

if ($data == 0) {
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
                <h3 class="text-center">Landing</h3>
                <div class="editor-container">
                    <textarea id="text-editor"></textarea>
                    <button class="accept-btn" id="accept-btn">Aceptar</button>
                    <div class="html-output" id="html-output"></div>
                </div>
            </div>
        </div>
    <?php
}
    ?>
    <script>
        tinymce.init({
            selector: '#text-editor',
            menubar: false,
            plugins: [
                'advlist autolink lists link image charmap print preview anchor',
                'searchreplace visualblocks code fullscreen',
                'insertdatetime media table paste code help wordcount'
            ],
            toolbar: 'undo redo | formatselect | image | media | link | code | table | bold italic backcolor | \
                              alignleft aligncenter alignright alignjustify | \
                              bullist numlist outdent indent | removeformat | help'
        });

        document.getElementById('accept-btn').addEventListener('click', () => {
            const editorContent = tinymce.get('text-editor').getContent();
            document.getElementById('html-output').innerHTML = editorContent;
        });
        <?php require_once './Views/templates/footer.php'; ?>