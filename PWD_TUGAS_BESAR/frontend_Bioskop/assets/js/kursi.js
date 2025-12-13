(function() {
  const urlParams = new URLSearchParams(window.location.search);
  const idPenayangan = urlParams.get('id_penayangan');
  const idFilm = urlParams.get('id_film');
  
  let selectedSeats = [];
  let hargaTiket = 0;

  if (!idPenayangan || !idFilm) {
    alert('Data tidak lengkap!');
    window.location.href = 'home_screen.xml';
    return;
  }

  // Fetch harga tiket dari film
  fetch('../PHP/fetch_data_film.php')
    .then(r => r.json())
    .then(data => {
      const film = data.find(f => f.id == idFilm);
      if (film) {
        hargaTiket = parseInt(film.harga);
      }
    });

  // Fetch kursi
  fetch('../PHP/get_kursi.php?id_penayangan=' + idPenayangan)
    .then(r => r.json())
    .then(data => {
      if (data.error) {
        alert(data.error);
        return;
      }

      const grid = document.getElementById('seatGrid');
      grid.innerHTML = '';

      data.forEach(kursi => {
        const seatEl = document.createElement('div');
        seatEl.className = 'seat ' + kursi.status;
        seatEl.textContent = kursi.nomor;
        seatEl.dataset.nomor = kursi.nomor;

        if (kursi.status === 'tersedia') {
          seatEl.addEventListener('click', function() {
            toggleSeat(this);
          });
        }

        grid.appendChild(seatEl);
      });
    });

  function toggleSeat(seatEl) {
    const nomor = seatEl.dataset.nomor;
    
    if (seatEl.classList.contains('selected')) {
      seatEl.classList.remove('selected');
      selectedSeats = selectedSeats.filter(s => s !== nomor);
    } else {
      seatEl.classList.add('selected');
      selectedSeats.push(nomor);
    }

    updateSummary();
  }

  function updateSummary() {
    const summary = document.getElementById('summary');
    const count = selectedSeats.length;

    if (count === 0) {
      summary.style.display = 'none';
      return;
    }

    summary.style.display = 'block';
    document.getElementById('selectedSeats').textContent = selectedSeats.join(', ');
    document.getElementById('seatCount').textContent = count;
    document.getElementById('totalPrice').textContent = (hargaTiket * count).toLocaleString('id-ID');
  }

  document.getElementById('btnCheckout').addEventListener('click', function() {
    if (selectedSeats.length === 0) {
      alert('Pilih kursi terlebih dahulu!');
      return;
    }

    // Simpan data ke sessionStorage
    const bookingData = {
      id_penayangan: idPenayangan,
      id_film: idFilm,
      kursi: selectedSeats,
      total_harga: hargaTiket * selectedSeats.length
    };

    sessionStorage.setItem('bookingData', JSON.stringify(bookingData));
    window.location.href = 'checkout.xml';
  });
})();