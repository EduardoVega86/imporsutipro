<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>calculadora</title>
</head>

<body>

    <main>
        <section>
            <article>
                <h1>Calculadora</h1>
                <div class="">
                    <form action="formCalculator" method="POST">
                        <div class="form-group">
                            <input type="text" placeholder="ingresa un numero" name="num1">
                        </div>
                        <div class="form-group">
                            <input type="text" placeholder="ingresa un numero" name="num2">
                        </div>
                        <div class="form-group">
                            <select name="" id="">
                                <option value="1">Sumar</option>
                                <option value="2">Restar</option>
                                <option value="3">Multiplicar</option>
                                <option value="4">Dividir</option>
                            </select>
                        </div>
                    </form>
                </div>
            </article>
        </section>
    </main>
</body>

<script>
    document.addEventListener("DOMContentLoaded", (event) => {

        const form = document.querySelector("#formCalculator");

        form.addEventListener("submit", async function() {
            const url = "https://new.imporsuitpro.com/Prueba2/calcular"
            const response = await fetch('url', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(params)
            });

            const data = await response.json();
            console.log(data)
        })


    });
</script>

</html>