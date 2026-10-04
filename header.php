<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=JetBrains+Mono:wght@500;700&display=swap" rel="stylesheet">
	<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<?php
/* ==================================================================
   TOP BAR — Tanggal + Standar Waktu Indonesia (mengikuti BMKG Pusat)
   ================================================================== */
?>
<div class="bmkg-topbar" aria-label="Informasi waktu standar Indonesia">
	<div class="bmkg-container bmkg-topbar__row">
		<span class="bmkg-topbar__date" id="bmkg-date"></span>
		<span class="bmkg-topbar__clock">
			STANDAR WAKTU INDONESIA
			<strong id="bmkg-wita">--:--:--</strong>
			<em>WITA</em>
			/
			<strong id="bmkg-utc">--:--:--</strong>
			<em>UTC</em>
		</span>
	</div>
</div>

<?php
/* ==================================================================
   MAIN HEADER — Logo + Nav + Contact Center (mengikuti BMKG Pusat)
   ================================================================== */
?>
<header class="bmkg-header" role="banner">
	<div class="bmkg-container bmkg-header__row">

		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="bmkg-header__brand" aria-label="Beranda BMKG Betoambari">
			<?php if ( function_exists( 'get_field' ) && get_field( 'nav_logo', 'option' ) ) : ?>
				<img src="<?php the_field( 'nav_logo', 'option' ); ?>" alt="" class="bmkg-header__logo" width="44" height="44">
			<?php else : ?>
				<img src="https://www.bmkg.go.id/asset/img/logo/logo-bmkg.png" alt="" class="bmkg-header__logo" width="44" height="44">
			<?php endif; ?>
			<div class="bmkg-header__brand-text">
				<strong>BMKG Betoambari</strong>
				<span>Stasiun Meteorologi Betoambari</span>
			</div>
		</a>

		<nav class="bmkg-nav" role="navigation" aria-label="Menu utama" id="bmkg-main-nav">
			<ul class="bmkg-nav__list">
				<li>
					<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Beranda</a>
				</li>

				<li class="bmkg-nav__dropdown">
					<button type="button" class="bmkg-nav__toggle" aria-expanded="false" data-dropdown="profil">
						Profil
						<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
					</button>
					<ul class="bmkg-nav__sub" data-menu="profil" data-title="Profil">
						<li class="bmkg-nav__sub-inner bmkg-mega">
							<div class="bmkg-mega__intro">
								<span class="bmkg-mega__intro-icon" aria-hidden="true"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 21h18"></path><path d="M5 21V7l7-4 7 4v14"></path><path d="M9 21v-6h6v6"></path></svg></span>
								<h4>Profil</h4>
								<p>Kenali BMKG dan Stasiun Meteorologi Betoambari.</p>
							</div>
							<div class="bmkg-mega__links">
								<a href="<?php echo esc_url( home_url( '/profil-bmkg' ) ); ?>"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><path d="M2 12h20"></path><path d="M12 2a15.3 15.3 0 014 10 15.3 15.3 0 01-4 10 15.3 15.3 0 01-4-10 15.3 15.3 0 014-10z"></path></svg></span><span class="bmkg-mega__text"><strong>Profil BMKG</strong><small>Sejarah, visi &amp; misi BMKG</small></span></a>
								<a href="<?php echo esc_url( home_url( '/profil-stasiun' ) ); ?>"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path><circle cx="12" cy="10" r="3"></circle></svg></span><span class="bmkg-mega__text"><strong>Profil Stasiun</strong><small>Stamet Betoambari</small></span></a>
								<a href="<?php echo esc_url( home_url( '/daftar-pegawai' ) ); ?>"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 00-3-3.87"></path><path d="M16 3.13a4 4 0 010 7.75"></path></svg></span><span class="bmkg-mega__text"><strong>Daftar Pegawai</strong><small>Personel stasiun</small></span></a>
								<a href="https://www.bmkg.go.id/" target="_blank" rel="noopener"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg></span><span class="bmkg-mega__text"><strong>BMKG Pusat</strong><small>Situs resmi bmkg.go.id</small></span></a>
							</div>
						</li>
					</ul>
				</li>

				<li class="bmkg-nav__dropdown">
					<button type="button" class="bmkg-nav__toggle" aria-expanded="false" data-dropdown="cuaca">
						Cuaca
						<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
					</button>
					<ul class="bmkg-nav__sub" data-menu="cuaca" data-title="Cuaca">
						<li class="bmkg-nav__sub-inner bmkg-mega">
							<div class="bmkg-mega__intro">
								<span class="bmkg-mega__intro-icon" aria-hidden="true"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19a4.5 4.5 0 100-9h-1.8A7 7 0 104 15.5"></path></svg></span>
								<h4>Cuaca</h4>
								<p>Informasi cuaca publik, maritim, penerbangan, dan pemantauan realtime.</p>
							</div>
							<div class="bmkg-mega__links">
								<a href="<?php echo esc_url( home_url( '/petir-realtime/' ) ); ?>"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"></polygon></svg></span><span class="bmkg-mega__text"><strong>Petir Realtime</strong><small>Peta sambaran petir Sultra</small></span></a>
								<a href="https://www.bmkg.go.id/cuaca/prakiraan-cuaca.bmkg?kab=Baubau" target="_blank" rel="noopener"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="4"></circle><path d="M12 2v2M12 20v2M4.93 4.93l1.41 1.41M17.66 17.66l1.41 1.41M2 12h2M20 12h2M6.34 17.66l-1.41 1.41M19.07 4.93l-1.41 1.41"></path></svg></span><span class="bmkg-mega__text"><strong>Prakiraan Cuaca</strong><small>Kota Baubau</small></span></a>
								<a href="https://maritim.bmkg.go.id/" target="_blank" rel="noopener"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 16c1.5 1.5 3.5 1.5 5 0s3.5-1.5 5 0 3.5 1.5 5 0 3.5-1.5 5 0"></path><path d="M2 20c1.5 1.5 3.5 1.5 5 0s3.5-1.5 5 0 3.5 1.5 5 0 3.5-1.5 5 0"></path></svg></span><span class="bmkg-mega__text"><strong>Cuaca Maritim</strong><small>Gelombang &amp; perairan</small></span></a>
								<a href="<?php echo esc_url( home_url( '/flight-documentation/' ) ); ?>"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"></path></svg></span><span class="bmkg-mega__text"><strong>Flight Documentation</strong><small>METAR, TAF, SIGMET WAWB</small></span></a>
								<a href="https://www.bmkg.go.id/cuaca/peringatan-dini-cuaca" target="_blank" rel="noopener"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg></span><span class="bmkg-mega__text"><strong>Peringatan Dini</strong><small>Cuaca ekstrem terkini</small></span></a>
								<a href="https://satelit.bmkg.go.id/" target="_blank" rel="noopener"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M13 7L9 3 5 7l4 4"></path><path d="M17 11l4 4-4 4-4-4"></path><path d="M8 12l4 4 6-6-4-4z"></path><path d="M16 8l3-3"></path><path d="M9 21a6 6 0 00-6-6"></path></svg></span><span class="bmkg-mega__text"><strong>Satelit Cuaca</strong><small>Citra satelit BMKG</small></span></a>
								<a href="https://www.bmkg.go.id/cuaca/karhutla" target="_blank" rel="noopener"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M12 22c4 0 7-3 7-7 0-5-4-7-3-12-4 2-7 6-7 10-1-1-2-2-2-4-2 2-3 4-3 6 0 4 4 7 8 7z"></path></svg></span><span class="bmkg-mega__text"><strong>Hotspot &amp; Karhutla</strong><small>Titik panas, asap &amp; FWI</small></span></a>
							</div>
						</li>
					</ul>
				</li>

				<li class="bmkg-nav__dropdown">
					<button type="button" class="bmkg-nav__toggle" aria-expanded="false" data-dropdown="iklim">
						Iklim
						<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
					</button>
					<ul class="bmkg-nav__sub" data-menu="iklim" data-title="Iklim">
						<li class="bmkg-nav__sub-inner bmkg-mega">
							<div class="bmkg-mega__intro">
								<span class="bmkg-mega__intro-icon" aria-hidden="true"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="M7 13l3-3 4 4 5-6"></path></svg></span>
								<h4>Iklim</h4>
								<p>Data, analisis, dan produk klimatologi wilayah Sulawesi Tenggara.</p>
							</div>
							<div class="bmkg-mega__links">
								<a href="https://iklim.bmkg.go.id/id/" target="_blank" rel="noopener"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z"></path><polyline points="14 2 14 8 20 8"></polyline><line x1="16" y1="13" x2="8" y2="13"></line><line x1="16" y1="17" x2="8" y2="17"></line></svg></span><span class="bmkg-mega__text"><strong>Data Iklim</strong><small>Portal iklim BMKG</small></span></a>
								<a href="<?php echo esc_url( home_url( '/iklim/produk-klimatologi/' ) ); ?>"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21.21 15.89A10 10 0 118 2.83"></path><path d="M22 12A10 10 0 0012 2v10z"></path></svg></span><span class="bmkg-mega__text"><strong>Produk Klimatologi</strong><small>Analisis &amp; buletin iklim</small></span></a>
								<a href="https://dataonline.bmkg.go.id/" target="_blank" rel="noopener"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg></span><span class="bmkg-mega__text"><strong>Data Online BMKG</strong><small>Akses data iklim stasiun</small></span></a>
							</div>
						</li>
					</ul>
				</li>

				<li><a href="https://inatews.bmkg.go.id/" target="_blank" rel="noopener">Gempa Bumi</a></li>

				<li class="bmkg-nav__dropdown">
					<button type="button" class="bmkg-nav__toggle" aria-expanded="false" data-dropdown="layanan">
						Layanan Publik
						<svg width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="6 9 12 15 18 9"></polyline></svg>
					</button>
					<ul class="bmkg-nav__sub" data-menu="layanan" data-title="Layanan Publik">
						<li class="bmkg-nav__sub-inner bmkg-mega">
							<div class="bmkg-mega__intro">
								<span class="bmkg-mega__intro-icon" aria-hidden="true"><svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17 21v-2a4 4 0 00-4-4H5a4 4 0 00-4 4v2"></path><circle cx="9" cy="7" r="4"></circle><path d="M23 21v-2a4 4 0 00-3-3.87"></path><path d="M16 3.13a4 4 0 010 7.75"></path></svg></span>
								<h4>Layanan Publik</h4>
								<p>Informasi, publikasi, akses data terbuka, dan kanal pengaduan.</p>
							</div>
							<div class="bmkg-mega__links">
								<a href="<?php echo esc_url( home_url( '/berita' ) ); ?>"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a2 2 0 01-2 2zm0 0a2 2 0 01-2-2v-9c0-1.1.9-2 2-2h2"></path><path d="M18 14h-8"></path><path d="M15 18h-5"></path><path d="M10 6h8v4h-8V6z"></path></svg></span><span class="bmkg-mega__text"><strong>Berita</strong><small>Kabar &amp; artikel stasiun</small></span></a>
								<a href="<?php echo esc_url( home_url( '/buletin' ) ); ?>"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"></path></svg></span><span class="bmkg-mega__text"><strong>Buletin</strong><small>Publikasi bulanan</small></span></a>
								<a href="<?php echo esc_url( home_url( '/layanan-data' ) ); ?>"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4"></path><polyline points="7 10 12 15 17 10"></polyline><line x1="12" y1="15" x2="12" y2="3"></line></svg></span><span class="bmkg-mega__text"><strong>Layanan Data</strong><small>Permohonan data &amp; PNBP</small></span></a>
								<a href="https://data.bmkg.go.id/" target="_blank" rel="noopener"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><ellipse cx="12" cy="5" rx="9" ry="3"></ellipse><path d="M21 12c0 1.66-4 3-9 3s-9-1.34-9-3"></path><path d="M3 5v14c0 1.66 4 3 9 3s9-1.34 9-3V5"></path></svg></span><span class="bmkg-mega__text"><strong>Data Terbuka</strong><small>Open data BMKG</small></span></a>
								<a href="https://ppid.bmkg.go.id/" target="_blank" rel="noopener"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="16" x2="12" y2="12"></line><line x1="12" y1="8" x2="12.01" y2="8"></line></svg></span><span class="bmkg-mega__text"><strong>PPID BMKG</strong><small>Informasi publik</small></span></a>
								<a href="https://jdih.bmkg.go.id/" target="_blank" rel="noopener"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 3h6a4 4 0 014 4v14a3 3 0 00-3-3H2z"></path><path d="M22 3h-6a4 4 0 00-4 4v14a3 3 0 013-3h7z"></path></svg></span><span class="bmkg-mega__text"><strong>JDIH</strong><small>Dokumen &amp; regulasi</small></span></a>
								<a href="https://www.lapor.go.id/instansi/badan-meteorologi-klimatologi-dan-geofisika" target="_blank" rel="noopener"><span class="bmkg-mega__icon" aria-hidden="true"><svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M21 11.5a8.38 8.38 0 01-.9 3.8 8.5 8.5 0 01-7.6 4.7 8.38 8.38 0 01-3.8-.9L3 21l1.9-5.7a8.38 8.38 0 01-.9-3.8 8.5 8.5 0 014.7-7.6 8.38 8.38 0 013.8-.9h.5a8.48 8.48 0 018 8v.5z"></path></svg></span><span class="bmkg-mega__text"><strong>Pengaduan (LAPOR!)</strong><small>Kanal aspirasi nasional</small></span></a>
							</div>
						</li>
					</ul>
				</li>
			</ul>
		</nav>

		<a href="tel:+62xxxxxxxxxx" class="bmkg-header__cta">
			<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
				<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6 19.79 19.79 0 01-3.07-8.67A2 2 0 014.11 2h3a2 2 0 012 1.72c.127.96.362 1.903.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0122 16.92z"></path>
			</svg>
			<span>Contact Center 196</span>
		</a>

		<button type="button" class="bmkg-header__burger" aria-controls="bmkg-main-nav" aria-expanded="false" aria-label="Buka menu navigasi">
			<span></span><span></span><span></span>
		</button>

	</div>
</header>

<?php /* Spacer below sticky header to prevent content jump */ ?>
<?php if ( ! is_front_page() ) : ?>
<div class="bmkg-header-spacer" aria-hidden="true"></div>
<?php endif; ?>

<script>
(function(){
	'use strict';

	/* ---- Update tanggal & jam WITA / UTC ---- */
	var hari = ['MINGGU','SENIN','SELASA','RABU','KAMIS','JUMAT','SABTU'];
	var bulan = ['JANUARI','FEBRUARI','MARET','APRIL','MEI','JUNI','JULI','AGUSTUS','SEPTEMBER','OKTOBER','NOVEMBER','DESEMBER'];

	function pad(n){ return n < 10 ? '0'+n : n; }

	function updateClock(){
		var now = new Date();
		var utc = now.getTime() + (now.getTimezoneOffset() * 60000);
		var wita = new Date(utc + 8*3600000);

		var dateEl = document.getElementById('bmkg-date');
		var witaEl = document.getElementById('bmkg-wita');
		var utcEl  = document.getElementById('bmkg-utc');

		if (dateEl) dateEl.textContent = hari[wita.getDay()] + ', ' + wita.getDate() + ' ' + bulan[wita.getMonth()] + ' ' + wita.getFullYear();
		if (witaEl) witaEl.textContent = pad(wita.getHours()) + ' : ' + pad(wita.getMinutes()) + ' : ' + pad(wita.getSeconds());

		var u = new Date(utc);
		if (utcEl) utcEl.textContent = pad(u.getHours()) + ' : ' + pad(u.getMinutes()) + ' : ' + pad(u.getSeconds());
	}
	updateClock();
	setInterval(updateClock, 1000);

	/* ---- Dropdown menu (desktop & mobile) ---- */
	document.querySelectorAll('.bmkg-nav__toggle').forEach(function(btn){
		btn.addEventListener('click', function(e){
			e.stopPropagation();
			var target = btn.dataset.dropdown;
			var sub = document.querySelector('[data-menu="'+target+'"]');
			var isOpen = btn.getAttribute('aria-expanded') === 'true';

			// close all
			document.querySelectorAll('.bmkg-nav__toggle').forEach(function(b){
				b.setAttribute('aria-expanded','false');
			});
			document.querySelectorAll('.bmkg-nav__sub').forEach(function(s){
				s.classList.remove('is-open');
			});

			if (!isOpen && sub){
				btn.setAttribute('aria-expanded','true');
				sub.classList.add('is-open');
			}
		});
	});

	document.addEventListener('click', function(){
		document.querySelectorAll('.bmkg-nav__toggle').forEach(function(b){
			b.setAttribute('aria-expanded','false');
		});
		document.querySelectorAll('.bmkg-nav__sub').forEach(function(s){
			s.classList.remove('is-open');
		});
	});

	/* ---- Mobile burger ---- */
	var burger = document.querySelector('.bmkg-header__burger');
	var nav    = document.getElementById('bmkg-main-nav');
	if (burger && nav){
		burger.addEventListener('click', function(){
			var expanded = burger.getAttribute('aria-expanded') === 'true';
			burger.setAttribute('aria-expanded', !expanded);
			nav.classList.toggle('is-open');
			burger.classList.toggle('is-active');
		});
	}

})();
</script>
