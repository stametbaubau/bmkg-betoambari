<?php
/**
 * Template Name: Profil Stasiun
 *
 * Mengikuti standar desain BMKG Pusat & Kalteng:
 * white theme, card polos, tanpa gradient / inline style / Font Awesome.
 *
 * @package BMKG_Betoambari
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

/* Helper: ambil ACF field dengan fallback default */
if ( ! function_exists( 'bmkg_field' ) ) {
	function bmkg_field( $name, $default = '' ) {
		if ( function_exists( 'get_field' ) ) {
			$val = get_field( $name );
			if ( ! empty( $val ) ) {
				return $val;
			}
		}
		return $default;
	}
}

/* Data profil (label => nilai) untuk daftar rapi */
$bmkg_profil = array(
	array( 'label' => 'Nama Satuan Kerja', 'value' => bmkg_field( 'nama_satuan_kerja', 'Stasiun Meteorologi Betoambari' ) ),
	array( 'label' => 'Klasifikasi',       'value' => bmkg_field( 'klasifikasi', 'Kelas III' ) ),
	array( 'label' => 'Balai Besar Wilayah','value' => bmkg_field( 'balai_besar_wilayah', 'Balai Besar MKG Wilayah IV Makassar' ) ),
	array( 'label' => 'Alamat',            'value' => bmkg_field( 'alamat_stasiun', 'Jl. Dayanu Ikhsanuddin, Kel. Katobengke, Kec. Betoambari, Kota Baubau, Sulawesi Tenggara' ) ),
	array( 'label' => 'Koordinat / Elevasi','value' => bmkg_field( 'koordinat_elevasi', '-5.470879° LS ; 122.620000° BT / 30 m' ) ),
	array( 'label' => 'Tahun Operasi',     'value' => bmkg_field( 'tahun_operasi', '2000' ) ),
	array( 'label' => 'Tupoksi Inti',      'value' => bmkg_field( 'tupoksi_inti', 'Mengamati dan memprediksi unsur cuaca; memberikan layanan data dan informasi meteorologi untuk penerbangan, pelayaran/maritim, dan masyarakat umum; menyebarluaskan peringatan dini cuaca ekstrem di wilayah Kota Baubau.' ) ),
);
?>

<main class="bmkg-main bmkg-page">
	<div class="bmkg-container">

		<header class="bmkg-page__head">
			<h1 class="bmkg-page__title"><?php the_title(); ?></h1>
			<p class="bmkg-page__subtitle">
				<?php echo esc_html( bmkg_field( 'page_subtitle', 'Stasiun Meteorologi Betoambari' ) ); ?>
			</p>
		</header>

		<?php /* ---- Card profil utama: info + foto ---- */ ?>
		<div class="bmkg-profile">
			<div class="bmkg-profile__header">
				<h2><?php echo esc_html( bmkg_field( 'nama_stasiun', 'Stasiun Meteorologi Betoambari' ) ); ?></h2>
				<p><?php echo esc_html( bmkg_field( 'deskripsi_stasiun', 'Unit Pelaksana Teknis BMKG' ) ); ?></p>
			</div>

			<div class="bmkg-profile__content">
				<dl class="bmkg-profile__list">
					<?php foreach ( $bmkg_profil as $item ) : ?>
						<div class="bmkg-profile__item">
							<dt><?php echo esc_html( $item['label'] ); ?></dt>
							<dd><?php echo esc_html( $item['value'] ); ?></dd>
						</div>
					<?php endforeach; ?>
				</dl>

				<?php if ( function_exists( 'get_field' ) && get_field( 'gambar_kantor' ) ) : ?>
				<figure class="bmkg-profile__figure">
					<img src="<?php the_field( 'gambar_kantor' ); ?>"
						alt="Kantor Stasiun Meteorologi Betoambari"
						class="bmkg-enlargeable" loading="lazy">
					<figcaption>Kantor Stasiun Meteorologi Betoambari</figcaption>
				</figure>
				<?php endif; ?>
			</div>
		</div>

		<?php /* ---- Kontak ---- */ ?>
		<section class="bmkg-contact-block" aria-label="Kontak stasiun">
			<h2 class="bmkg-contact-block__title">Hubungi Kami</h2>
			<div class="bmkg-contact-block__grid">

				<div class="bmkg-contact-block__item">
					<span class="bmkg-contact-block__icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 013.07 4.18 2 2 0 015.11 2h3a2 2 0 012 1.72c.127.96.362 1.9.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0122 16.92z"></path>
						</svg>
					</span>
					<h3>Telepon</h3>
					<p><?php echo esc_html( bmkg_field( 'telepon_stasiun', '(0402) 2823606' ) ); ?></p>
				</div>

				<div class="bmkg-contact-block__item">
					<span class="bmkg-contact-block__icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
							<polyline points="22,6 12,13 2,6"></polyline>
						</svg>
					</span>
					<h3>Email</h3>
					<p><?php echo esc_html( bmkg_field( 'email_stasiun', 'stamet.baubau@bmkg.go.id' ) ); ?></p>
				</div>

				<div class="bmkg-contact-block__item">
					<span class="bmkg-contact-block__icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
							<circle cx="12" cy="12" r="10"></circle>
							<polyline points="12 6 12 12 16 14"></polyline>
						</svg>
					</span>
					<h3>Jam Operasional</h3>
					<p>Senin&ndash;Jumat: 08.00&ndash;16.00 WITA<br>Layanan Informasi: 24 Jam</p>
				</div>

			</div>
		</section>

		<?php /* ---- Galeri foto (opsional dari ACF) ---- */ ?>
		<?php
		$gallery = function_exists( 'get_field' ) ? get_field( 'galeri_foto' ) : false;
		if ( $gallery ) :
			?>
			<section class="bmkg-gallery" aria-label="Galeri foto">
				<h2 class="bmkg-section__title bmkg-section__title--center">Galeri Foto</h2>
				<div class="bmkg-gallery__grid">
					<?php foreach ( $gallery as $image ) : ?>
						<figure class="bmkg-gallery__item">
							<img src="<?php echo esc_url( $image['sizes']['large'] ); ?>"
								alt="<?php echo esc_attr( $image['alt'] ); ?>"
								class="bmkg-enlargeable" loading="lazy">
						</figure>
					<?php endforeach; ?>
				</div>
			</section>
		<?php endif; ?>

	</div>
</main>




<?php get_footer(); ?>
