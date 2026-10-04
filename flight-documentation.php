<?php
/**
 * Virtual Page — Flight Documentation Betoambari (WAWB / BUW)
 * @package BMKG_Betoambari
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>
<main class="bmkg-main bmkg-page bmkg-flight-page">
	<section class="bmkg-flight-hero">
		<div class="bmkg-container bmkg-flight-hero__inner">
			<div><span class="bmkg-flight-kicker">AVIATION METEOROLOGY • WAWB / BUW</span><h1>Flight Documentation Betoambari</h1><p>Gerbang cepat produk meteorologi penerbangan resmi BMKG untuk Bandar Udara Betoambari, Baubau. Seluruh produk operasional dibuka dari sistem Aviation BMKG agar tidak menampilkan dokumen tiruan atau data kedaluwarsa.</p><div class="bmkg-flight-badges"><span>ICAO WAWB</span><span>IATA BUW</span><span>WITA UTC+8</span><span>TAF 24 Jam</span></div></div>
			<a class="bmkg-flight-cta" href="https://web-aviation.bmkg.go.id/" target="_blank" rel="noopener">Buka Aviation BMKG ↗</a>
		</div>
	</section>
	<section class="bmkg-section">
		<div class="bmkg-container">
			<header class="bmkg-section__head"><div><h2 class="bmkg-section__title">Dokumen &amp; Produk Operasional</h2><p class="bmkg-section__support">Pilih produk untuk membuka sumber resmi BMKG pada tab baru.</p></div><span class="bmkg-live-badge">OFFICIAL LINKS</span></header>
			<div class="bmkg-flight-grid">
				<a class="bmkg-flight-card" href="https://web-aviation.bmkg.go.id/web/metar_speci.php" target="_blank" rel="noopener"><span>01</span><h3>METAR / SPECI</h3><p>Observasi cuaca bandara dan laporan khusus ketika terjadi perubahan signifikan.</p><strong>Buka METAR/SPECI →</strong></a>
				<a class="bmkg-flight-card" href="https://web-aviation.bmkg.go.id/web/taf.php" target="_blank" rel="noopener"><span>02</span><h3>TAF</h3><p>Prakiraan terminal aerodrome untuk kebutuhan operasi penerbangan WAWB.</p><strong>Buka TAF →</strong></a>
				<a class="bmkg-flight-card" href="https://web-aviation.bmkg.go.id/web/sigmet.php" target="_blank" rel="noopener"><span>03</span><h3>SIGMET</h3><p>Informasi fenomena cuaca signifikan yang berpotensi memengaruhi keselamatan penerbangan.</p><strong>Buka SIGMET →</strong></a>
				<a class="bmkg-flight-card" href="https://web-aviation.bmkg.go.id/web/prediksi_cb.php" target="_blank" rel="noopener"><span>04</span><h3>Potensi CB</h3><p>Informasi potensi awan Cumulonimbus untuk kewaspadaan cuaca konvektif.</p><strong>Buka Potensi CB →</strong></a>
				<a class="bmkg-flight-card" href="https://web-aviation.bmkg.go.id/maps" target="_blank" rel="noopener"><span>05</span><h3>Aviation Maps</h3><p>Peta operasional untuk mendukung situational awareness meteorologi penerbangan.</p><strong>Buka Aviation Maps →</strong></a>
				<a class="bmkg-flight-card" href="https://web-aviation.bmkg.go.id/web/regulasi" target="_blank" rel="noopener"><span>06</span><h3>Regulasi &amp; Referensi</h3><p>Akses regulasi dan dokumen referensi layanan meteorologi penerbangan BMKG.</p><strong>Buka regulasi →</strong></a>
			</div>
		</div>
	</section>
	<section class="bmkg-section"><div class="bmkg-container"><div class="bmkg-flight-notice"><div><strong>Catatan operasional</strong><p>Gunakan produk dengan waktu berlaku yang sesuai rencana penerbangan dan selalu rujuk sistem resmi BMKG/briefing meteorologi untuk keputusan operasional.</p></div><a href="<?php echo esc_url( home_url( '/layanan-data/' ) ); ?>">Kontak &amp; layanan data →</a></div></div></section>
</main>
<?php get_footer(); ?>
