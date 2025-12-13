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
    // Ambil jadwal berdasarkan film
    function loadoptionJadwal() {

        // BACA film ID dari HIDDEN INPUT
        const filmId = document.getElementById("film_id").value;
        console.log("FILM ID TERBACA:", filmId);

        fetch("../PHP/fetch_data_jadwal.php?id_film=" + filmId)
            .then(r => r.json())
            .then(data => {
                promoList = data;
                const select = document.getElementById("listjadwal");

                select.innerHTML = `<option value="">--Pilih Jadwal--</option>`;

                data.forEach(item => {
                    const option = createEl("option", { value: item.id });
                    option.textContent =item.id + ". " + item.jadwal_p;
                    select.appendChild(option);
                });
            });
    }

    document.addEventListener("change", function (e) {
        if (e.target.id === "listjadwal") {
            const id = e.target.value;
            const promo = promoList.find(p => p.id == id);  // FIXED

            if (promo) {
                document.getElementById("id_tayang").value = promo.id;
                document.getElementById("jadwal_n").value = promo.jadwal_p;
                document.getElementById("jumlah_kursi_n").value = promo.jumlah;
            }
        }
    });



    document.getElementById("listFilm").addEventListener("change", function () {
        const selectedFilm = this.value;

        document.getElementById("film_id").value = selectedFilm;

        console.log("Hidden film_id diisi:", selectedFilm);

        
        loadoptionJadwal();
    });

})();
