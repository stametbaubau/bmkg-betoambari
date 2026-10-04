<?php
/**
 * Template Name: Layanan Data
 *
 * White theme BMKG. Tanpa gradient / inline style / Font Awesome.
 *
 * @package BMKG_Betoambari
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$tpl = get_template_directory_uri();

/* Jenis data yang tersedia */
$bmkg_jenis_data = array(
	array( 'judul' => 'Data Cuaca',          'ket' => 'Suhu, kelembaban, curah hujan, dan angin.' ),
	array( 'judul' => 'Data Klimatologi',    'ket' => 'Data iklim bulanan dan tahunan.' ),
	array( 'judul' => 'Data Maritim',        'ket' => 'Tinggi gelombang dan arus laut.' ),
	array( 'judul' => 'Data Kualitas Udara', 'ket' => 'Parameter kualitas udara sesuai ketersediaan jaringan pengamatan BMKG.' ),
	array( 'judul' => 'Data Sambaran Petir', 'ket' => 'Untuk penelitian dan klaim asuransi.' ),
	array( 'judul' => 'Data Lainnya',        'ket' => 'Radiasi matahari, evaporasi, dan lainnya.' ),
);
?>

<main class="bmkg-main bmkg-page">
	<div class="bmkg-container">

		<header class="bmkg-page__head">
			<h1 class="bmkg-page__title">Layanan Data BMKG</h1>
			<p class="bmkg-page__subtitle">Prosedur permohonan data dan informasi BMKG Betoambari</p>
		</header>

		<?php /* ---- Maklumat Pelayanan ---- */ ?>
		<article class="bmkg-prose-card">
			<h2>Maklumat Pelayanan</h2>
			<figure class="bmkg-data-figure">
				<img src="<?php echo esc_url( $tpl . '/assets/images/MaklumatPelayanan.png' ); ?>"
					alt="Maklumat Pelayanan BMKG" class="bmkg-enlargeable" loading="lazy">
			</figure>
		</article>

		<?php /* ---- Alur Permohonan (Tab) ---- */ ?>
		<article class="bmkg-prose-card">
			<h2>Alur Permohonan Data</h2>

			<div class="bmkg-filter" role="tablist" aria-label="Jenis permohonan">
				<button type="button" class="bmkg-filter__btn is-active" role="tab" aria-selected="true" data-tab="gratis">Data Gratis</button>
				<button type="button" class="bmkg-filter__btn" role="tab" aria-selected="false" data-tab="pnbp">Data PNBP</button>
			</div>

			<div class="bmkg-tabpanel is-active" data-panel="gratis">
				<figure class="bmkg-data-figure">
					<img src="<?php echo esc_url( $tpl . '/assets/images/alur-gratis.jpg' ); ?>"
						alt="Alur Permohonan Data Gratis" class="bmkg-enlargeable" loading="lazy">
				</figure>
				<div class="bmkg-note bmkg-note--success">
					<h3>Informasi Data Gratis</h3>
					<p>Untuk kegiatan pendidikan dan penelitian nonkomersial, permohonan disertai dengan:</p>
					<ol class="bmkg-ordered-list">
						<li>
							Pengantar tertulis dari kepala sekolah pada sekolah yang bersangkutan; atau rektor/dekan pada perguruan tinggi yang bersangkutan.
						</li>
						<li>
							Proposal penelitian yang telah disetujui pembimbing atau promotor, untuk satu jenis informasi pada satu judul penelitian, dengan cakupan wilayah paling banyak 3 lokasi dan periode waktu paling lama 5 tahun.
						</li>
					</ol>
				</div>
			</div>

			<div class="bmkg-tabpanel" data-panel="pnbp" hidden>
				<?php if ( file_exists( get_template_directory() . '/assets/images/alur-pnbp.jpg' ) ) : ?>
					<figure class="bmkg-data-figure"><img src="<?php echo esc_url( $tpl . '/assets/images/alur-pnbp.jpg' ); ?>" alt="Alur Permohonan Data PNBP" class="bmkg-enlargeable" loading="lazy"></figure>
				<?php else : ?>
					<div class="bmkg-flow-steps" aria-label="Alur permohonan data PNBP"><div><strong>1</strong><span>Ajukan permohonan</span></div><div><strong>2</strong><span>Verifikasi jenis &amp; volume data</span></div><div><strong>3</strong><span>Penetapan tarif PNBP</span></div><div><strong>4</strong><span>Data diserahkan</span></div></div>
				<?php endif; ?>
				<div class="bmkg-note bmkg-note--warning">
					<h3>Informasi Data PNBP</h3>
					<ul class="bmkg-check-list">
						<li>Data untuk keperluan komersial atau bisnis.</li>
						<li>Data realtime dan historis lengkap.</li>
						<li>Tarif mengikuti ketentuan PNBP BMKG yang berlaku.</li>
						<li>Proses sesuai kesepakatan.</li>
					</ul>
				</div>
			</div>
		</article>

		<?php /* ---- Jenis Data ---- */ ?>
		<article class="bmkg-prose-card">
			<h2>Jenis Data yang Tersedia</h2>
			<div class="bmkg-data-grid">
				<?php foreach ( $bmkg_jenis_data as $d ) : ?>
					<div class="bmkg-data-item">
						<h3><?php echo esc_html( $d['judul'] ); ?></h3>
						<p><?php echo esc_html( $d['ket'] ); ?></p>
					</div>
				<?php endforeach; ?>
			</div>
		</article>

		<?php /* ---- Kontak ---- */ ?>
		<section class="bmkg-contact-block" aria-label="Kontak layanan data">
			<h2 class="bmkg-contact-block__title">Hubungi Kami</h2>
			<div class="bmkg-contact-block__grid">
				<div class="bmkg-contact-block__item">
					<span class="bmkg-contact-block__icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
					</span>
					<h3>Email</h3>
					<p>stamet.baubau@bmkg.go.id</p>
				</div>
				<div class="bmkg-contact-block__item">
					<span class="bmkg-contact-block__icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 013.07 4.18 2 2 0 015.11 2h3a2 2 0 012 1.72c.127.96.362 1.9.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0122 16.92z"></path></svg>
					</span>
					<h3>Telepon</h3>
					<p>(0402) 2823606</p>
				</div>
				<div class="bmkg-contact-block__item">
					<span class="bmkg-contact-block__icon" aria-hidden="true">
						<svg width="22" height="22" viewBox="0 0 24 24" fill="currentColor"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
					</span>
					<h3>WhatsApp</h3>
					<p>0811-4037-700</p>
				</div>
			</div>
			<a href="https://dataonline.bmkg.go.id" target="_blank" rel="noopener" class="bmkg-contact-block__cta">
				Akses Data Online BMKG
				<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M18 13v6a2 2 0 01-2 2H5a2 2 0 01-2-2V8a2 2 0 012-2h6"></path><polyline points="15 3 21 3 21 9"></polyline><line x1="10" y1="14" x2="21" y2="3"></line></svg>
			</a>
			<div class="bmkg-service-actions bmkg-service-actions--center"><a href="https://ptsp.bmkg.go.id/" target="_blank" rel="noopener">PTSP Online BMKG</a><a href="https://data.bmkg.go.id/prakiraan-cuaca/" target="_blank" rel="noopener">Dokumentasi Data Terbuka</a></div>
		</section>

	</div>
</main>

<script>
(function(){
	'use strict';
	var btns   = document.querySelectorAll('.bmkg-filter__btn[data-tab]');
	var panels = document.querySelectorAll('.bmkg-tabpanel');
	btns.forEach(function(btn){
		btn.addEventListener('click', function(){
			var tab = btn.dataset.tab;
			btns.forEach(function(b){ b.classList.remove('is-active'); b.setAttribute('aria-selected','false'); });
			btn.classList.add('is-active');
			btn.setAttribute('aria-selected','true');
			panels.forEach(function(p){
				var on = (p.dataset.panel === tab);
				p.classList.toggle('is-active', on);
				if (on) { p.removeAttribute('hidden'); } else { p.setAttribute('hidden',''); }
			});
		});
	});
})();
</script>

<?php get_footer(); ?>
