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
        fetch("../PHP/fetch_data_promo.php")
            .then(r => r.json())
            .then(data => {
                promoList = data; // save data for later use
                const select = document.getElementById("listPromo");

                select.innerHTML = `<option value="">--Pilih Promo--</option>`;

                data.forEach(item => {
                    const option = createEl("option", { value: item.id });
                    option.textContent = item.id + ". " + item.nama;
                    select.appendChild(option);
                });
            });
    }

    // When selecting a promo
    document.addEventListener("change", function (e) {
        if (e.target.id === "listPromo") {
            const id = e.target.value;
            const promo = promoList.find(p => p.id === id);

            if (promo) {
                document.getElementById("promo_id").value = promo.id;
                document.getElementById("promo_n").value = promo.nama;
                document.getElementById("link").value = promo.link; 
            }
        }
    });

    // run
    if (document.readyState === "loading")
        document.addEventListener("DOMContentLoaded", loadoptionPromo);
    else
        loadoptionPromo();
})();
