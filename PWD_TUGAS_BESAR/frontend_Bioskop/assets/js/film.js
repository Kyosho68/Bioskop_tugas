(function () {
  const XHTML_NS = "http://www.w3.org/1999/xhtml";

  function createEl(tag, attrs) {
    const el = document.createElementNS ? document.createElementNS(XHTML_NS, tag) : document.createElement(tag);
    if (attrs) {
      for (const k in attrs) {
        if (attrs.hasOwnProperty(k)) el.setAttribute(k, attrs[k]);
      }
    }
    return el;
  }

  function loadfilm() {
    fetch("../PHP/fetch_data_film.php")
      .then(response => {
        if (!response.ok) throw new Error("Network response was not OK: " + response.status);
        return response.json();
      })
      .then(data => {
        console.log("DATA FILM:", data);

        const container = document.getElementById("filmContainer");
        if (!container) return console.error("filmContainer not found!");

        container.innerHTML = "";

        data.forEach(item => {
          const div = createEl("div", { "class": "film-item" });

          const img = createEl("img", {
            src: item.poster || "",
            alt: item.judul || ""
          });

          const title = createEl("h3");
          title.textContent = item.judul || "-";
        

          const price = createEl("p");
          price.textContent = "Harga: Rp " + (item.harga || "0");

          const description = createEl("p");
          description.textContent = item.sinopsis || "-";
          div.appendChild(img);
          div.appendChild(title);
          div.appendChild(price);
          div.appendChild(description);
          container.appendChild(div);
          
          div.addEventListener("click", function () {
            console.log("FILM DIKLIK:", item.id);

            // Go to detail page
            window.location.href = "../PHP/film_detail.php?id=" + item.id;
        });
          


          
        });
      })
      .catch(err => {
        console.error("ERROR loading film:", err);
        const container = document.getElementById("filmContainer");
        if (container) container.textContent = "Gagal memuat film: " + err.message;
      });
  }

  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", loadfilm);
  } else {
    loadfilm();
  }
})();