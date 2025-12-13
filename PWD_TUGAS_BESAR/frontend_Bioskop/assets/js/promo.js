(function () {
  // XHTML namespace for elements when page served as application/xhtml+xml
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

  function loadPromo() {

    fetch("../PHP/fetch_data_promo.php")
      .then(response => {
        if (!response.ok) throw new Error("Network response was not OK: " + response.status);
        return response.json();
      })
      .then(data => {
        console.log("DATA :", data);
        const container = document.getElementById("promoContainer");
        // clear old content
        container.innerHTML = "";

        

        data.forEach(item => {
          const div = createEl("div", { "class": "promo-item" });
          
          const img = createEl("img", { src: item.link || "", alt: item.nama || "promo" });
          // add loading attribute for performance
          try { img.setAttribute("loading", "lazy"); } catch (e) {}
          div.appendChild(img);
          container.appendChild(div);
        });
      });
    }


    

  // start when DOM fully parsed
  if (document.readyState === "loading") {
    document.addEventListener("DOMContentLoaded", () => {
        loadPromo();
    });
} else {
    loadPromo();
}
})();
