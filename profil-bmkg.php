<?php
/**
 * Template Name: Profil BMKG
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
?>

<main class="bmkg-main bmkg-page">
	<div class="bmkg-container">

		<header class="bmkg-page__head">
			<h1 class="bmkg-page__title"><?php the_title(); ?></h1>
			<p class="bmkg-page__subtitle">
				<?php echo esc_html( bmkg_field( 'page_subtitle', 'Badan Meteorologi, Klimatologi, dan Geofisika' ) ); ?>
			</p>
		</header>

		<?php /* ---- Logo + gambar utama ---- */ ?>
		<div class="bmkg-profile-hero">
			<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/Logo-BMKG-2010-2.png' ); ?>"
				alt="Logo BMKG" class="bmkg-profile-hero__logo" width="120" height="120">

			<?php if ( function_exists( 'get_field' ) && get_field( 'gambar_utama' ) ) : ?>
				<img src="<?php the_field( 'gambar_utama' ); ?>"
					alt="<?php echo esc_attr( bmkg_field( 'alt_gambar_utama', 'Gedung BMKG' ) ); ?>"
					class="bmkg-profile-hero__image bmkg-enlargeable" loading="lazy">
			<?php else : ?>
				<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/gedungbmkg.jpg' ); ?>"
					alt="Gedung BMKG"
					class="bmkg-profile-hero__image bmkg-enlargeable" loading="lazy">
			<?php endif; ?>
		</div>

		<?php /* ---- Tentang BMKG ---- */ ?>
		<article class="bmkg-prose-card">
			<h2>Tentang BMKG</h2>
			<div class="bmkg-prose">
				<?php if ( function_exists( 'get_field' ) && get_field( 'tentang_bmkg' ) ) : ?>
					<?php the_field( 'tentang_bmkg' ); ?>
				<?php else : ?>
					<p>Badan Meteorologi, Klimatologi, dan Geofisika (BMKG) adalah Lembaga Pemerintah Non Kementerian yang melaksanakan tugas pemerintahan di bidang meteorologi, klimatologi, kualitas udara, dan geofisika sesuai dengan ketentuan perundang-undangan yang berlaku.</p>
					<p>BMKG bertugas menyediakan data, informasi, dan layanan di bidang meteorologi, klimatologi, kualitas udara, dan geofisika untuk mendukung keselamatan masyarakat dan keberhasilan pembangunan nasional.</p>
				<?php endif; ?>
			</div>
		</article>

		<?php /* ---- Tugas & Fungsi ---- */ ?>
		<div class="bmkg-grid bmkg-grid--2 bmkg-profile-duo">

			<article class="bmkg-prose-card">
				<h2>Tugas</h2>
				<div class="bmkg-prose">
					<?php if ( function_exists( 'get_field' ) && get_field( 'tugas_bmkg' ) ) : ?>
						<?php the_field( 'tugas_bmkg' ); ?>
					<?php else : ?>
						<p>BMKG mempunyai tugas melaksanakan tugas pemerintahan di bidang meteorologi, klimatologi, kualitas udara, dan geofisika sesuai dengan ketentuan perundang-undangan.</p>
					<?php endif; ?>
				</div>
			</article>

			<article class="bmkg-prose-card">
				<h2>Fungsi</h2>
				<div class="bmkg-prose">
					<?php
					$fungsi_items = function_exists( 'get_field' ) ? get_field( 'fungsi_bmkg' ) : false;
					if ( $fungsi_items ) :
						?>
						<ul class="bmkg-check-list">
							<?php foreach ( $fungsi_items as $fungsi ) : ?>
								<li><?php echo esc_html( $fungsi['item_fungsi'] ); ?></li>
							<?php endforeach; ?>
						</ul>
					<?php else : ?>
						<ul class="bmkg-check-list">
							<li>Perumusan kebijakan nasional dan kebijakan umum di bidang meteorologi, klimatologi, dan geofisika.</li>
							<li>Perumusan kebijakan teknis di bidang meteorologi, klimatologi, dan geofisika.</li>
							<li>Koordinasi kebijakan, perencanaan, dan program di bidang meteorologi, klimatologi, dan geofisika.</li>
							<li>Pelaksanaan, pembinaan, dan pengendalian observasi serta pengolahan data dan informasi.</li>
							<li>Pelayanan data dan informasi di bidang meteorologi, klimatologi, dan geofisika.</li>
						</ul>
					<?php endif; ?>
				</div>
			</article>

		</div>

		<?php /* ---- Visi & Misi ---- */ ?>
		<article class="bmkg-prose-card">
			<h2>Visi &amp; Misi</h2>

			<div class="bmkg-vismis">
				<h3 class="bmkg-vismis__label">Visi</h3>
				<div class="bmkg-prose">
					<?php if ( function_exists( 'get_field' ) && get_field( 'visi_bmkg' ) ) : ?>
						<?php the_field( 'visi_bmkg' ); ?>
					<?php else : ?>
						<p>Mewujudkan BMKG yang andal, tanggap, dan mampu dalam rangka mendukung keselamatan masyarakat serta keberhasilan pembangunan nasional, dan berperan aktif di tingkat internasional.</p>
					<?php endif; ?>
				</div>
			</div>

			<div class="bmkg-vismis">
				<h3 class="bmkg-vismis__label">Misi</h3>
				<div class="bmkg-prose">
					<?php
					$misi_items = function_exists( 'get_field' ) ? get_field( 'misi_bmkg' ) : false;
					if ( $misi_items ) :
						?>
						<ol class="bmkg-ordered-list">
							<?php foreach ( $misi_items as $misi ) : ?>
								<li><?php echo esc_html( $misi['item_misi'] ); ?></li>
							<?php endforeach; ?>
						</ol>
					<?php else : ?>
						<ol class="bmkg-ordered-list">
							<li>Menyediakan data, informasi, dan jasa pelayanan meteorologi, klimatologi, dan geofisika yang andal dan terpercaya.</li>
							<li>Mengoptimalkan pemanfaatan data, informasi, dan jasa pelayanan meteorologi, klimatologi, dan geofisika.</li>
							<li>Meningkatkan kemampuan SDM, sarana, dan prasarana operasional.</li>
							<li>Meningkatkan penelitian, pengkajian, dan pengembangan meteorologi, klimatologi, dan geofisika.</li>
							<li>Memperkuat kelembagaan BMKG dan meningkatkan kerja sama internasional.</li>
						</ol>
					<?php endif; ?>
				</div>
			</div>
		</article>

		<?php /* ---- Sejarah ---- */ ?>
		<article class="bmkg-prose-card">
			<h2>Sejarah Singkat BMKG</h2>
			<div class="bmkg-prose">
				<?php if ( function_exists( 'get_field' ) && get_field( 'sejarah_bmkg' ) ) : ?>
					<?php the_field( 'sejarah_bmkg' ); ?>
				<?php else : ?>
					<p>Sejarah pengamatan meteorologi dan geofisika di Indonesia dimulai pada tahun 1841, diawali dengan pengamatan perorangan oleh Dr. Onnen, Kepala Rumah Sakit di Bogor. Kegiatan ini terus berkembang seiring meningkatnya kebutuhan data cuaca dan geofisika.</p>
					<p>Pada tahun 1866, kegiatan tersebut diresmikan menjadi instansi pemerintah dengan nama Magnetisch en Meteorologisch Observatorium, dipimpin oleh Dr. Bergsma. Tahun 1879 dibangun jaringan penakar hujan sebanyak 74 stasiun pengamatan di Jawa.</p>
					<p>Pengamatan gempa bumi dimulai pada tahun 1908 dengan pemasangan seismograf Wiechert di Jakarta. Pada masa pendudukan Jepang (1942&ndash;1945), instansi ini bernama Kisho Kauso Kusho.</p>
					<p>Setelah kemerdekaan tahun 1945, instansi dipecah menjadi Biro Meteorologi di Yogyakarta dan Jawatan Meteorologi dan Geofisika di Jakarta. Berdasarkan Undang-Undang Nomor 31 Tahun 2009 tentang Meteorologi, Klimatologi, dan Geofisika, BMKG menjadi Lembaga Pemerintah Non Kementerian yang dipimpin oleh seorang Kepala Badan.</p>
				<?php endif; ?>
			</div>
		</article>

	</div>
</main>

<?php get_footer(); ?>
