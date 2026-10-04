BMKG BETOAMBARI V4 — UPGRADE 2026

FITUR UTAMA
- Prakiraan 43 kelurahan / 8 kecamatan Kota Baubau menggunakan Data Terbuka BMKG (ADM4).
- Detail cuaca: suhu, kelembapan, angin, arah angin, visibilitas, tutupan awan.
- Waktu lokal WITA (UTC+8).
- Peta cuaca kecamatan, Radar, Satelit Himawari-9, gempa, kualitas udara resmi BMKG.
- Hotspot / Karhutla, sebaran asap dan Fire Weather Index.
- Petir realtime + tautan resmi Sistem Informasi Petir BMKG.
- Layanan Data + kontak UPT BMKG Betoambari.
- Flight Documentation WAWB/BUW: METAR/SPECI, TAF, SIGMET, CB dan Aviation Maps.
- Tidak membuat data cuaca/PM2.5 simulasi bila API tidak tersedia.

INSTALASI WORDPRESS
1. WordPress Admin > Appearance/Tampilan > Themes/Tema > Add New/Tambah Baru > Upload Theme/Unggah Tema.
2. Unggah ZIP tema ini lalu aktifkan.
3. WAJIB setelah aktivasi/update: Settings/Pengaturan > Permalinks > klik Save Changes/Simpan Perubahan satu kali. Ini mengaktifkan URL virtual /flight-documentation/, /petir-realtime/, dan /display-cuaca-maritim/.
4. Buka beranda lalu pastikan status Prakiraan Cuaca menampilkan "Data BMKG aktif".
5. Jika hosting gratis memblokir outbound request ke api.bmkg.go.id, tema tidak memalsukan data; seluruh kelurahan tetap tampil dengan status data belum tersedia.

CATATAN ASET
- assets/images/ikm-poster.png belum ada pada paket asal. Tema menampilkan placeholder yang rapi sampai poster resmi ditambahkan.
- assets/images/alur-pnbp.jpg belum ada pada paket asal. Tema otomatis menampilkan alur PNBP berbasis HTML sebagai fallback.

SUMBER DATA
- Prakiraan: https://data.bmkg.go.id/prakiraan-cuaca/
- Aviation: https://web-aviation.bmkg.go.id/
- Petir: https://lightning.bmkg.go.id/map/monitoring
- Kualitas udara: https://www.bmkg.go.id/kualitas-udara/pm25
- Karhutla: https://www.bmkg.go.id/cuaca/karhutla
