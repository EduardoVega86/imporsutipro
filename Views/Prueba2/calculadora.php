<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>calculadora</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

</head>

<body>

    <main>
        <section>
            <article>
                <h1>Calculadora</h1>
                <div class="card">
                    <div class="card-body">
                        <form id="formCalculator" action="" method="POST">
                            <div class="form-group">
                                <label for="">Numero 1</label>
                                <input type="text" class="form-control" placeholder="ingresa un numero" name="num1">
                            </div>
                            <div class="form-group mb-4">
                                <label for="">Numero 2</label>
                                <input type="text" placeholder="ingresa un numero" class="form-control" name="num2">
                            </div>
                            <div class="form-group mb-4">
                                <label for="">Selecciona una operacion</label>
                                <select name="option" class="form-control" id="">
                                    <option value="1">Sumar</option>
                                    <option value="2">Restar</option>
                                    <option value="3">Multiplicar</option>
                                    <option value="4">Dividir</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-4">Calcular</button>
                            <div class="alert alert-success alert-result d-none" role="alert">
                                <span id="result"></span>
                            </div>

                        </form>
                    </div>
                </div>
            </article>
        </section>
    </main>
</body>

<script>
    document.addEventListener("DOMContentLoaded", () => {

        const form = document.querySelector("#formCalculator");

        form.addEventListener("submit", async function(e) {
            e.preventDefault();
            const formData = new FormData(form);
            const url = "https://desarrollo.imporsuitpro.com/Prueba2/calcular"
            const response = await fetch(url, {
                method: 'POST',
                headers: {
                    //'Content-Type': 'application/json'
                },
                body: formData


            });
            const data = await response.json();
            console.log(data);
            if (data.status) {
                document.querySelector("#result").textContent = data.resultado;
                document.querySelector(".alert-result").classList.remove("d-none")

            }
        })


    });
</script>

</html>