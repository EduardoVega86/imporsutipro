document.getElementById("full").addEventListener("click", function () {
    document.getElementById("full").checked ? document.getElementById("input-full").classList.remove("hidden-all") : document.getElementById("input-full").classList.add("hidden-all");
});