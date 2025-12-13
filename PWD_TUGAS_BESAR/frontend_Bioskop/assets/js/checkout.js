(function() {
  const bookingData = JSON.parse(sessionStorage.getItem('bookingData'));

  if (!bookingData) {
    alert('Data pemesanan tidak ditemukan!');
    window.location.href = 'home_screen.xml';
    return;
  }

  // Fetch detail film dan jadwal
  Promise.all([
    fetch('../PHP/fetch_data_film.php').then(r => r.json()),
    fetch('../PHP/fetch_data_jadwal.php?id_film=' + bookingData.id_film).then(r => r.json())
  ])
  .then(([films, jadwals]) => {
    const film = films.find(f => f.id == bookingData.id_film);
    const jadwal = jadwals.find(j => j.id == bookingData.id_penayangan);

    if (!film || !jadwal) {
      alert('Data tidak lengkap!');
      return;
    }

    const summary = document.getElementById('checkoutSummary');
    summary.innerHTML = `
      <div class="checkout-card">
        <img src="${film.poster}" alt="${film.judul}" class="checkout-poster" />
        <div class="checkout-details">
          <h3>${film.judul}</h3>
          <p><strong>Jadwal:</strong> ${jadwal.jadwal_p}</p>
          <p><strong>Kursi:</strong> ${bookingData.kursi.join(', ')}</p>
          <p><strong>Jumlah:</strong> ${bookingData.kursi.length} tiket</p>
          <p class="checkout-total">
            <strong>Total:</strong> Rp ${bookingData.total_harga.toLocaleString('id-ID')}
          </p>
        </div>
      </div>
    `;
  });

  // Proses pembayaran
  document.getElementById('btnBayar').addEventListener('click', function() {
    const payment = document.querySelector('input[name="payment"]:checked');
    if (!payment) {
      alert('Pilih metode pembayaran!');
      return;
    }

    this.disabled = true;
    this.textContent = 'Memproses...';

    const formData = new FormData();
    formData.append('id_penayangan', bookingData.id_penayangan);
    formData.append('total_harga', bookingData.total_harga);
    bookingData.kursi.forEach(k => formData.append('kursi[]', k));

    fetch('../PHP/pesan_tiket.php', {
      method: 'POST',
      body: formData
    })
    .then(r => r.json())
    .then(data => {
      if (data.success) {
        alert(data.message);
        sessionStorage.removeItem('bookingData');
        window.location.href = 'tiket.xml?kode=' + data.kode_tiket;
      } else {
        alert(data.error || 'Gagal memproses pembayaran');
        this.disabled = false;
        this.textContent = 'Bayar Sekarang';
      }
    })
    .catch(err => {
      alert('Terjadi kesalahan: ' + err.message);
      this.disabled = false;
      this.textContent = 'Bayar Sekarang';
    });
  });
})();