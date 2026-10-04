<?php
/**
 * 404 Template
 *
 * @package BMKG_Betoambari
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="bmkg-main bmkg-page">
	<div class="bmkg-container">

		<div class="bmkg-empty-state bmkg-empty-state--404">
			<svg width="64" height="64" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<circle cx="11" cy="11" r="8"></circle>
				<line x1="21" y1="21" x2="16.65" y2="16.65"></line>
				<line x1="8" y1="11" x2="14" y2="11"></line>
			</svg>
			<h1>Halaman Tidak Ditemukan</h1>
			<p>Maaf, halaman yang Anda cari tidak tersedia atau telah dipindahkan.</p>
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bmkg-btn-primary">Kembali ke Beranda</a>
		</div>

	</div>
</main>

<?php get_footer(); ?>
