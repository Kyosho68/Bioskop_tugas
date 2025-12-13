(function () {
    const XHTML_NS = "http://www.w3.org/1999/xhtml";

    let promoList = []; // store all data

    function createEl(tag, attrs) {
        const el = document.createElementNS ? document.createElementNS(XHTML_NS, tag) : document.createElement(tag);
        if (attrs) {
            for (const k in attrs) el.setAttribute(k, attrs[k]);
        }
        return el;
    }

    function loadoptionPromo() {
        fetch("../PHP/fetch_data_film.php")
            .then(r => r.json())
            .then(data => {
                promoList = data; // save data for later use
                const select = document.getElementById("listFilm");

                select.innerHTML = `<option value="">--Pilih Film--</option>`;

                data.forEach(item => {
                    const option = createEl("option", { value: item.id });
                    option.textContent = item.id + ". " + item.judul;
                    select.appendChild(option);
                });
            });
    }

    // When selecting a promo
    document.addEventListener("change", function (e) {
        if (e.target.id === "listFilm") {
            const id = e.target.value;
            const promo = promoList.find(p => p.id === id);

            if (promo) {
                document.getElementById("film_id").value = promo.id;
                document.getElementById("film_id_n2").value = promo.id;
                document.getElementById("film_id_h2").value = promo.id;
                document.getElementById("film_n").value = promo.judul;
                document.getElementById("sinopsis_n").value = promo.sinopsis;
                document.getElementById("harga_n").value = promo.harga;
                document.getElementById("link_n").value = promo.poster; 
            }
        }
    });

    // run
    if (document.readyState === "loading")
        document.addEventListener("DOMContentLoaded", loadoptionPromo);
    else
        loadoptionPromo();
})();
