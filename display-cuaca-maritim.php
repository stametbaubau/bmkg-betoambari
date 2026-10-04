<?php
/**
 * Template Name: Display Cuaca Maritim (Standalone)
 *
 * Menampilkan Display.html asli milik Stamet Betoambari apa adanya (dashboard monitor 8 slide:
 * Prakiraan Pelabuhan, Tinggi Gelombang, Cuaca Rute Pelayaran, dll).
 * Menggunakan iframe full-screen untuk me-load source.
 * Dirender penuh tanpa header/footer tema karena ini display layar (100vw/100vh).
 * @package BMKG_Betoambari
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

/* Baca file sumber dashboard dari folder tema dan keluarkan apa adanya. */
$bmkg_display_file = get_template_directory() . '/display-maritim-source.html';
if ( file_exists( $bmkg_display_file ) ) {
	// Output langsung — dashboard punya <!DOCTYPE html> sendiri, jadi ini halaman penuh.
	echo file_get_contents( $bmkg_display_file ); // phpcs:ignore
	exit;
}

/* Fallback bila file sumber tidak ada */
get_header();
?>
<main class="bmkg-main bmkg-page">
	<div class="bmkg-container">
		<header class="bmkg-page__head">
			<h1 class="bmkg-page__title">Display Cuaca Maritim</h1>
		</header>
		<div class="bmkg-note bmkg-note--warning">
			<h3>Berkas dashboard belum tersedia</h3>
			<p>File <code>display-maritim-source.html</code> tidak ditemukan di folder tema. Silakan unggah ulang berkas dashboard maritim.</p>
		</div>
	</div>
</main>
<?php
get_footer();
