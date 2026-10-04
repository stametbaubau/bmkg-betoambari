<?php
/**
 * Template: Petir Realtime — Peta Sambaran Petir (embed sumber resmi BMKG)
 * @package BMKG_Betoambari
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();
?>

<main class="bmkg-main bmkg-page">
	<div class="bmkg-container">

		<nav class="bmkg-breadcrumb" aria-label="Breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Beranda</a>
			<span aria-hidden="true">&rsaquo;</span>
			<span class="bmkg-breadcrumb__current">Petir Realtime</span>
		</nav>

		<header class="bmkg-page__head">
			<h1 class="bmkg-page__title">Peta Sambaran Petir Realtime</h1>
			<p class="bmkg-page__subtitle">Pantauan aktivitas sambaran petir wilayah Sulawesi Tenggara dan sekitarnya</p>
		</header>

		<div class="bmkg-embed-card">
			<div class="bmkg-embed-card__head">
				<span class="bmkg-embed-card__badge" aria-hidden="true">
					<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg>
				</span>
				<span>Peta Sambaran Petir &mdash; Realtime</span>
			</div>
			<div class="bmkg-embed-card__frame">
				<iframe src="https://simora.bmkg.go.id/petir_upt/petir_stamet.betoambari/"
					title="Peta Sambaran Petir Realtime BMKG"
					loading="lazy"
					referrerpolicy="no-referrer"
					allowfullscreen></iframe>
			</div>
		</div>

		<div class="bmkg-service-actions" style="margin-top:14px;"><a href="https://lightning.bmkg.go.id/map/monitoring" target="_blank" rel="noopener">Buka Sistem Informasi Petir BMKG ↗</a><a href="https://www.bmkg.go.id/geofisika-potensial/petir-realtime" target="_blank" rel="noopener">Petir 30 Menit Terakhir ↗</a></div>

		<div class="bmkg-note bmkg-note--warning" style="margin-top:18px;">
			<h3>Keterangan</h3>
			<p>Peta menampilkan estimasi lokasi dan waktu sambaran petir (lightning) dari jaringan sensor BMKG. Titik/simbol menandakan sambaran terbaru beserta polaritas (CG+, CG&minus;, CLD). Data bersifat realtime dan diperbarui otomatis. Gunakan sebagai referensi kewaspadaan, bukan pengganti peringatan dini resmi.</p>
		</div>

	</div>
</main>

<?php get_footer(); ?>
