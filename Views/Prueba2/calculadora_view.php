<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<title>Calculadora</title>

<body>
    <h1>Calculadora Basica</h1>
    <form method="post" action="">
        <input type="number" name="option1" placeholder="Primer numero" required>
        <input type="number" name="option2" placeholder="Segundo numero" required>
        <select name="operacion" required>
            <option value="sumar">Sumar</option>
            <option value="restar">Restar</option>
            <option value="multiplicar">Multiplicar </option>
        </select>
        <button type="submit">Calcular</button>
    </form>
</body>
<script>
    document.addEventListener("DOMContentLoaded", () => {

        const form = document.querySelector("#formCalculator");

        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const url = "https://desarrollo.imporsuitpro.com/Prueba2/calcular_resultado"
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    //'Content-Type': 'application/json'
                },
                body: formData


            });
            const data = await response.json();
            console.log(data);
        })
    });
</script>

</html>