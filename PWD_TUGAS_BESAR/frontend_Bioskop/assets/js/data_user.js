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
    fetch("../PHP/fetch_data_user.php")
        .then(r => r.json())
        .then(data => {
            const select = document.getElementById("profil");
            
        if (!select) return console.error("profil not found!");

            
            select.innerHTML = ""; // Clear old options
            const user = data[0];
                const div = createEl("div", { "class": "profile-container" });

                const username = createEl("p");
                username.textContent =  user.username;
                // const profilePic =  createEl("img", {
                //         src:"../frontend_Bioskop/assets/profile/"+ user.foto || "",
                //         class: "profile-picture",
                //         alt: user.username || ""
                        
                // });
                
                div.appendChild(username);
                // div.appendChild(profilePic);
                select.appendChild(div);

    });

    document.addEventListener("DOMContentLoaded", function () {
    loadUser();
    });

    function loadUser() {
        fetch("../PHP/fetch_data_user.php")
            .then(res => res.json())
            .then(data => {
                console.log(data);

                if (data.length > 0) {
                    document.getElementById("username").value = data[0].username;
                    document.getElementById("email").value = data[0].email;
                }
            });
    }

}

    

    // run
    if (document.readyState === "loading")
        document.addEventListener("DOMContentLoaded", loadoptionPromo);
    else
        loadUser();
        loadoptionPromo();
})();
