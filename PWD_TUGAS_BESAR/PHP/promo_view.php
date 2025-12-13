<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Promo View</title>
    <style>
        .promo-item {
            margin-bottom: 20px;
        }
        .promo-item img {
            width: 250px;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <h2>Daftar Promo</h2>
    <div id="promoList"></div> <!-- Tempat menampilkan gambar -->

    <script>
        fetch('fetch_data_promo.php')
            .then(response => response.json())
            .then(data => {
                const promoList = document.getElementById('promoList');
                console.log("DATA DARI API:", data);

                data.forEach(item => {
                    promoList.innerHTML += `
                        <div class="promo-item">
                            <img src="${item.link}" alt="${item.nama}">
                            <p>${item.nama}</p>
                        </div>
                    `;
                });
            })
            .catch(error => {
                console.error("Error:", error);
            });
    </script>

</body>
</html>
