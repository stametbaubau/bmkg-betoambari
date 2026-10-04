<?php
/**
 * Front Page — BMKG Stasiun Meteorologi Betoambari
 * @package BMKG_Betoambari
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }
get_header();

$bmkg_titik = function_exists( 'bmkg_betoambari_kecamatan_titik' ) ? bmkg_betoambari_kecamatan_titik() : array();

/* ---- Peringatan Dini: ArcGIS Nowcasting, filter SULAWESI TENGGARA ---- */
function bmkg_betoambari_get_nowcast_sultra() {
	$cached = get_transient( 'bmkg_nowcast_sultra_v2' );
	if ( false !== $cached ) { return $cached; }
	$url = 'https://nowcasting.bmkg.go.id/arcgis/rest/services/production/nowcasting_publik_phase2/MapServer/2/query'
		. '?f=json&orderByFields=no_sorting%20ASC'
		. '&outFields=idlaporan,namaprovinsi,namakecamatan,waktupembuatan,namakotakab,waktuberlaku,waktuberakhir,kategoridampak,tipearea,kodewilayah,no_sorting'
		. '&returnGeometry=false&spatialRel=esriSpatialRelIntersects&where=1%3D1';
	$res = wp_remote_get( $url, array( 'timeout' => 10, 'sslverify' => false ) );
	if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) { return array(); }
	$body = json_decode( wp_remote_retrieve_body( $res ), true );
	if ( empty( $body['features'] ) ) { set_transient( 'bmkg_nowcast_sultra_v2', array(), 5 * MINUTE_IN_SECONDS ); return array(); }
	$laporan = array();
	foreach ( $body['features'] as $f ) {
		$a = isset( $f['attributes'] ) ? $f['attributes'] : array();
		$prov = strtoupper( trim( isset( $a['namaprovinsi'] ) ? $a['namaprovinsi'] : '' ) );
		if ( 'SULAWESI TENGGARA' !== $prov && 'SULTRA' !== $prov ) { continue; }
		$id = $a['idlaporan'];
		if ( ! isset( $laporan[ $id ] ) ) {
			$draw = isset( $a['kategoridampak'] ) ? $a['kategoridampak'] : '';
			$darr = json_decode( str_replace( "'", '"', $draw ), true );
			$laporan[ $id ] = array(
				'dampak'   => is_array( $darr ) ? $darr : array( $draw ),
				'berlaku'  => isset( $a['waktuberlaku'] ) ? (int) $a['waktuberlaku'] : 0,
				'berakhir' => isset( $a['waktuberakhir'] ) ? (int) $a['waktuberakhir'] : 0,
				'wilayah'  => array(),
			);
		}
		$kec = trim( isset( $a['namakecamatan'] ) ? $a['namakecamatan'] : '' );
		$kab = trim( isset( $a['namakotakab'] ) ? $a['namakotakab'] : '' );
		if ( $kec ) { $laporan[ $id ]['wilayah'][ $kec ] = $kab; }
	}
	$laporan = array_values( $laporan );
	set_transient( 'bmkg_nowcast_sultra_v2', $laporan, 5 * MINUTE_IN_SECONDS );
	return $laporan;
}
$bmkg_alerts = bmkg_betoambari_get_nowcast_sultra();

function bmkg_ms_to_wita( $ms ) { return $ms ? gmdate( 'j M Y, H:i', (int) ( $ms / 1000 ) + 8 * 3600 ) . ' WITA' : '-'; }
function bmkg_dampak_label( $d ) {
	$m = array( 'HujanSedang-Lebat' => 'Hujan Sedang–Lebat', 'Kilat/Petir' => 'Kilat/Petir', 'AnginKencang' => 'Angin Kencang' );
	return isset( $m[ $d ] ) ? $m[ $d ] : preg_replace( '/(?<!^)([A-Z])/', ' $1', $d );
}

/* ---- Gempa terkini (autogempa) ---- */
function bmkg_betoambari_get_gempa() {
	$cached = get_transient( 'bmkg_autogempa_v2' );
	if ( false !== $cached ) { return $cached; }
	$res = wp_remote_get( 'https://data.bmkg.go.id/DataMKG/TEWS/autogempa.json', array( 'timeout' => 8 ) );
	if ( is_wp_error( $res ) || 200 !== wp_remote_retrieve_response_code( $res ) ) { return false; }
	$body = json_decode( wp_remote_retrieve_body( $res ), true );
	$g = isset( $body['Infogempa']['gempa'] ) ? $body['Infogempa']['gempa'] : false;
	if ( $g ) { set_transient( 'bmkg_autogempa_v2', $g, 5 * MINUTE_IN_SECONDS ); }
	return $g;
}
$bmkg_gempa = bmkg_betoambari_get_gempa();
?>

<main id="site-main" class="bmkg-main">

	<?php /* ====== 1. PERINGATAN DINI (gaya BMKG Pusat: kartu oranye lembut) ====== */ ?>
	<section class="bmkg-section bmkg-section--first" aria-label="Peringatan dini cuaca Sulawesi Tenggara">
		<div class="bmkg-container">
			<?php if ( empty( $bmkg_alerts ) ) : ?>
				<div class="bmkg-warn bmkg-warn--nil">
					<span class="bmkg-warn__icon" aria-hidden="true">
						<svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M22 11.08V12a10 10 0 11-5.93-9.14"></path><polyline points="22 4 12 14.01 9 11.01"></polyline></svg>
					</span>
					<div class="bmkg-warn__body">
						<h2 class="bmkg-warn__title">Peringatan Dini Cuaca — Sulawesi Tenggara</h2>
						<p class="bmkg-warn__text">Nihil. Tidak ada peringatan dini cuaca yang berlaku untuk wilayah Sulawesi Tenggara saat ini.
						<a href="https://nowcasting.bmkg.go.id/nowcast/" target="_blank" rel="noopener" class="bmkg-warn__link">Informasi selengkapnya &rarr;</a></p>
					</div>
				</div>
			<?php else : ?>
				<div class="bmkg-warn">
					<button type="button" class="bmkg-warn__arrow bmkg-warn__arrow--prev" data-alert-prev aria-label="Sebelumnya" <?php echo count( $bmkg_alerts ) <= 1 ? 'hidden' : ''; ?>>
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
					</button>
					<span class="bmkg-warn__icon" aria-hidden="true">
						<svg width="26" height="26" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M10.29 3.86L1.82 18a2 2 0 001.71 3h16.94a2 2 0 001.71-3L13.71 3.86a2 2 0 00-3.42 0z"></path><line x1="12" y1="9" x2="12" y2="13"></line><line x1="12" y1="17" x2="12.01" y2="17"></line></svg>
					</span>
					<div class="bmkg-warn__body" data-alert-items>
						<?php foreach ( $bmkg_alerts as $i => $al ) : ?>
							<div class="bmkg-warn__item <?php echo 0 === $i ? 'is-active' : ''; ?>">
								<h2 class="bmkg-warn__title">Peringatan Dini Cuaca Sulawesi Tenggara</h2>
								<p class="bmkg-warn__text">
									<?php echo esc_html( bmkg_ms_to_wita( $al['berlaku'] ) ); ?> s.d. <?php echo esc_html( bmkg_ms_to_wita( $al['berakhir'] ) ); ?>:
									Berpotensi <strong><?php echo esc_html( strtolower( implode( ', ', array_map( 'bmkg_dampak_label', $al['dampak'] ) ) ) ); ?></strong>
									di sebagian wilayah
									<?php
									$kabs = array_unique( array_values( $al['wilayah'] ) );
									echo esc_html( implode( ', ', array_slice( $kabs, 0, 4 ) ) );
									?>.
									<?php
									$kecs = array_keys( $al['wilayah'] );
									if ( $kecs ) {
										echo ' <span class="bmkg-warn__kec">Kecamatan: ' . esc_html( implode( ', ', array_slice( $kecs, 0, 10 ) ) );
										if ( count( $kecs ) > 10 ) { echo ' +' . ( count( $kecs ) - 10 ) . ' lainnya'; }
										echo '.</span>';
									}
									?>
								</p>
								<a class="bmkg-warn__link" href="https://nowcasting.bmkg.go.id/nowcast/" target="_blank" rel="noopener">Informasi selengkapnya
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
								</a>
							</div>
						<?php endforeach; ?>
					</div>
					<button type="button" class="bmkg-warn__arrow bmkg-warn__arrow--next" data-alert-next aria-label="Berikutnya" <?php echo count( $bmkg_alerts ) <= 1 ? 'hidden' : ''; ?>>
						<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
					</button>
				</div>
			<?php endif; ?>
		</div>
	</section>

	<?php /* ====== 2. PRAKIRAAN CUACA KELURAHAN (slider, di bawah peringatan) ====== */ ?>
	<section class="bmkg-section" aria-label="Prakiraan cuaca kelurahan">
		<div class="bmkg-container">
			<header class="bmkg-section__head">
				<div><h2 class="bmkg-section__title">Prakiraan Cuaca 43 Kelurahan</h2><p class="bmkg-section__support">Data Terbuka BMKG • 8 kecamatan Kota Baubau • interval prakiraan 3-jam</p></div>
				<div class="bmkg-tabs" role="tablist">
					<button class="bmkg-tab is-active" role="tab" aria-selected="true" data-forecast-day="0">Saat ini</button>
					<button class="bmkg-tab" role="tab" aria-selected="false" data-forecast-day="1">Hari ini</button>
					<button class="bmkg-tab" role="tab" aria-selected="false" data-forecast-day="2">Besok</button>
					<button class="bmkg-tab" role="tab" aria-selected="false" data-forecast-day="3">Lusa</button>
				</div>
			</header>

			<div class="bmkg-forecast-toolbar">
				<label for="bmkg-kec-filter">Tampilkan wilayah</label>
				<select id="bmkg-kec-filter" class="bmkg-kec-filter">
					<option value="">Semua 43 kelurahan</option>
					<option>Betoambari</option><option>Wolio</option><option>Sorawolio</option><option>Bungi</option>
					<option>Kokalukuna</option><option>Murhum</option><option>Lea-Lea</option><option>Batupoaro</option>
				</select>
				<span id="bmkg-weather-source" class="bmkg-source-note">Menghubungkan ke BMKG…</span>
			</div>
			<div class="bmkg-slider" data-slider>
				<button class="bmkg-slider__nav bmkg-slider__nav--prev" type="button" aria-label="Sebelumnya" data-slider-prev>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</button>
				<div class="bmkg-slider__track" data-slider-track data-forecast-track>
					<div class="bmkg-forecast-loading">Memuat prakiraan cuaca kelurahan…</div>
				</div>
				<button class="bmkg-slider__nav bmkg-slider__nav--next" type="button" aria-label="Berikutnya" data-slider-next>
					<svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg>
				</button>
			</div>
		</div>
	</section>

	<?php /* ====== 3. PETA CUACA (8 kecamatan; icon+jam+suhu terkini) ====== */ ?>
	<section class="bmkg-section" aria-label="Peta cuaca kecamatan">
		<div class="bmkg-container">
			<div class="bmkg-map-card">
				<div class="bmkg-map-card__head bmkg-map-card__head--tools">
					<label class="bmkg-mapsearch" for="bmkg-search-input">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
						<input id="bmkg-search-input" type="text" placeholder="Cari kecamatan di peta..." autocomplete="off">
						<div class="bmkg-wilayah-list" id="bmkg-wilayah-list"></div>
					</label>
					<span class="bmkg-mapnow" id="bmkg-hero-pill">
						<img id="bmkg-hero-icon" alt="" width="30" height="30" hidden>
						<strong id="bmkg-hero-temp">--°C</strong>
						<em id="bmkg-hero-cond">Betoambari</em>
					</span>
				</div>
				<div id="bmkg-map" class="bmkg-map" data-titik='<?php echo esc_attr( wp_json_encode( $bmkg_titik ) ); ?>'></div>
				<div class="bmkg-map-card__legend">
					<span><span class="dot dot--cerah"></span>Cerah</span>
					<span><span class="dot dot--berawan"></span>Berawan</span>
					<span><span class="dot dot--hujan"></span>Hujan</span>
					<span><span class="dot dot--petir"></span>Petir</span>
				</div>
			</div>
		</div>
	</section>

	<?php /* ====== 4. RADAR & SATELIT CUACA ====== */ ?>
	<section class="bmkg-section" aria-label="Radar dan satelit cuaca">
		<div class="bmkg-container">
			<h2 class="bmkg-section__title">Radar &amp; Satelit Cuaca</h2>
			<div class="bmkg-grid bmkg-grid--2">
				<figure class="bmkg-imgcard">
					<div class="bmkg-imgcard__head"><h3>Citra Radar Cuaca</h3><span>Kendari &mdash; Sultra</span></div>
					<div class="bmkg-imgcard__body">
						<img src="https://cuaca.bmkg.go.id/data/public/sidarma/IMAGE/CMAX_Kendari.png"
							alt="Citra Radar Cuaca Sulawesi Tenggara" loading="lazy"
							class="bmkg-enlargeable" referrerpolicy="no-referrer"
							onerror="this.closest('.bmkg-imgcard').classList.add('is-error')">
						<div class="bmkg-imgcard__fallback">Citra radar tidak tersedia saat ini.</div>
					</div>
				</figure>
				<figure class="bmkg-imgcard">
					<div class="bmkg-imgcard__head"><h3>Citra Satelit Cuaca</h3><span>Himawari-9 &mdash; Sultra</span></div>
					<div class="bmkg-imgcard__body">
						<img src="https://inderaja.bmkg.go.id/IMAGE/HIMA/H08_EH_Sultra.png"
							alt="Citra Satelit Himawari-9 Sulawesi Tenggara" loading="lazy"
							class="bmkg-enlargeable" referrerpolicy="no-referrer"
							onerror="this.closest('.bmkg-imgcard').classList.add('is-error')">
						<div class="bmkg-imgcard__fallback">Citra satelit tidak tersedia saat ini.</div>
					</div>
				</figure>
			</div>
		</div>
	</section>

	<?php /* ====== 5. GEMPA + KUALITAS UDARA ====== */ ?>
	<section class="bmkg-section" aria-label="Gempa bumi dan kualitas udara">
		<div class="bmkg-container">
			<div class="bmkg-grid bmkg-grid--gempa-aqi">

				<article class="bmkg-info-card bmkg-info-card--accent-red">
					<header class="bmkg-info-card__head">
						<h2>Gempa Bumi Terkini</h2>
						<a href="https://www.bmkg.go.id/gempabumi/gempabumi-terkini.bmkg" target="_blank" rel="noopener" class="bmkg-info-card__more" aria-label="Selengkapnya">
							<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
						</a>
					</header>
					<?php if ( $bmkg_gempa ) : ?>
						<div class="bmkg-info-card__body">
							<p class="bmkg-quake-time"><?php echo esc_html( $bmkg_gempa['Tanggal'] . ' • ' . $bmkg_gempa['Jam'] ); ?></p>
							<p class="bmkg-quake-wilayah"><?php echo esc_html( isset( $bmkg_gempa['Wilayah'] ) ? $bmkg_gempa['Wilayah'] : '' ); ?></p>
							<div class="bmkg-quake-stats">
								<div class="bmkg-quake-stat bmkg-quake-stat--mag"><strong><?php echo esc_html( $bmkg_gempa['Magnitude'] ); ?></strong><span>Magnitudo</span></div>
								<div class="bmkg-quake-stat"><strong><?php echo esc_html( $bmkg_gempa['Kedalaman'] ); ?></strong><span>Kedalaman</span></div>
								<div class="bmkg-quake-stat"><strong><?php echo esc_html( $bmkg_gempa['Lintang'] ); ?><br><?php echo esc_html( $bmkg_gempa['Bujur'] ); ?></strong><span>Koordinat</span></div>
							</div>
							<?php if ( ! empty( $bmkg_gempa['Dirasakan'] ) && '-' !== trim( $bmkg_gempa['Dirasakan'] ) ) : ?>
								<p class="bmkg-quake-note"><strong>Dirasakan:</strong> <?php echo esc_html( $bmkg_gempa['Dirasakan'] ); ?></p>
							<?php endif; ?>
							<?php if ( ! empty( $bmkg_gempa['Potensi'] ) ) : ?>
								<p class="bmkg-quake-note bmkg-quake-note--potensi"><?php echo esc_html( $bmkg_gempa['Potensi'] ); ?></p>
							<?php endif; ?>
						</div>
						<?php if ( ! empty( $bmkg_gempa['Shakemap'] ) ) : ?>
							<figure class="bmkg-info-card__shake">
								<img src="https://data.bmkg.go.id/DataMKG/TEWS/<?php echo esc_attr( $bmkg_gempa['Shakemap'] ); ?>"
									alt="Peta guncangan (shakemap)" class="bmkg-enlargeable" loading="lazy"
									referrerpolicy="no-referrer" onerror="this.closest('figure').remove()">
								<figcaption>Peta Guncangan (Shakemap)</figcaption>
							</figure>
						<?php endif; ?>
					<?php else : ?>
						<div class="bmkg-info-card__body"><p class="bmkg-info-card__empty">Data gempa belum tersedia.</p></div>
					<?php endif; ?>
				</article>

				<article class="bmkg-info-card bmkg-info-card--accent-blue bmkg-info-card--aqiwide">
					<header class="bmkg-info-card__head">
						<h2>Kualitas Udara</h2><span class="bmkg-live-badge">Sumber BMKG</span>
					</header>
					<div class="bmkg-info-card__body bmkg-air-official">
						<p class="bmkg-air-lead"><strong>PM2.5 tidak lagi disimulasikan.</strong> Nilai lokal hanya ditampilkan oleh layanan resmi apabila stasiun pemantauan tersedia.</p>
						<div class="bmkg-air-legend" aria-label="Kategori PM2.5 BMKG"><span data-level="good">Baik 0–15,5</span><span data-level="moderate">Sedang 15,6–55,4</span><span data-level="unhealthy">Tidak sehat 55,5–150,4</span><span data-level="very">Sangat tidak sehat 150,5–250,4</span><span data-level="danger">Berbahaya &gt;250,5 µg/m³</span></div>
						<div class="bmkg-service-actions"><a href="https://www.bmkg.go.id/kualitas-udara/pm25" target="_blank" rel="noopener">PM2.5 Realtime BMKG</a><a href="https://iklim.bmkg.go.id/id/kualitas-udara-indonesia/" target="_blank" rel="noopener">Peta Kualitas Udara &amp; Asap</a></div>
					</div>
				</article>

			</div>
		</div>
	</section>

	<?php /* ====== 6. HOTSPOT / KARHUTLA ====== */ ?>
	<section class="bmkg-section bmkg-section--service" aria-label="Hotspot dan kebakaran hutan lahan">
		<div class="bmkg-container">
			<header class="bmkg-section__head"><div><h2 class="bmkg-section__title">Hotspot, Asap &amp; Karhutla</h2><p class="bmkg-section__support">Akses pemantauan resmi BMKG berbasis satelit dan indeks cuaca kebakaran.</p></div><span class="bmkg-live-badge">LIVE SERVICE</span></header>
			<div class="bmkg-live-service-grid">
				<a class="bmkg-live-service-card" href="https://www.bmkg.go.id/cuaca/satelit/polar-hotspot" target="_blank" rel="noopener"><span class="bmkg-live-service-card__icon">●</span><h3>Hotspot VIIRS / MODIS</h3><p>Pantau titik panas dari satelit NOAA-20, S-NPP, Terra dan Aqua.</p><strong>Buka peta hotspot →</strong></a>
				<a class="bmkg-live-service-card" href="https://www.bmkg.go.id/cuaca/karhutla" target="_blank" rel="noopener"><span class="bmkg-live-service-card__icon">≋</span><h3>Sebaran Asap</h3><p>Lihat analisis sebaran asap untuk mendukung kewaspadaan wilayah.</p><strong>Buka peta asap →</strong></a>
				<a class="bmkg-live-service-card" href="https://www.bmkg.go.id/cuaca/karhutla" target="_blank" rel="noopener"><span class="bmkg-live-service-card__icon">△</span><h3>Potensi Karhutla / FWI</h3><p>Informasi indeks cuaca kebakaran dan layanan karhutla BMKG.</p><strong>Buka layanan karhutla →</strong></a>
			</div>
		</div>
	</section>

	<?php /* ====== VIDEO PRAKIRAAN MINGGUAN + INDEKS KEPUASAN ====== */ ?>
	<?php
	$bmkg_video_url = get_option( 'bmkg_youtube_prakiraan_url', '' );
	$bmkg_video_id  = '';
	if ( $bmkg_video_url ) {
		if ( preg_match( '/(?:youtu\.be\/|youtube\.com\/(?:watch\?v=|embed\/|v\/))([A-Za-z0-9_-]{11})/', $bmkg_video_url, $m ) ) {
			$bmkg_video_id = $m[1];
		}
	}
	?>
	<section class="bmkg-section" aria-label="Video prakiraan dan indeks kepuasan">
		<div class="bmkg-container">
			<div class="bmkg-grid bmkg-grid--2">
				<div class="bmkg-media-panel">
					<h2 class="bmkg-media-panel__title">Video Prakiraan Cuaca Mingguan</h2>
					<div class="bmkg-media-panel__body">
						<?php if ( $bmkg_video_id ) : ?>
							<div class="bmkg-video-embed">
								<iframe src="https://www.youtube.com/embed/<?php echo esc_attr( $bmkg_video_id ); ?>"
									title="Video Prakiraan Cuaca Mingguan Kota Baubau"
									loading="lazy" allowfullscreen
									allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"></iframe>
							</div>
						<?php else : ?>
							<div class="bmkg-imgcard__body" style="aspect-ratio:16/9;border-radius:10px;">
								<div style="display:flex;align-items:center;justify-content:center;text-align:center;padding:20px;color:var(--bmkg-text-muted);font-size:13px;">Video prakiraan cuaca belum diatur.<br>Atur melalui menu BMKG Video Settings di dashboard.</div>
							</div>
						<?php endif; ?>
					</div>
				</div>
				<div class="bmkg-media-panel">
					<h2 class="bmkg-media-panel__title">Indeks Kepuasan Masyarakat</h2>
					<div class="bmkg-media-panel__body">
						<figure class="bmkg-imgcard" style="border:0;box-shadow:none;">
							<div class="bmkg-imgcard__body" style="aspect-ratio:auto;background:transparent;">
							<?php if ( file_exists( get_template_directory() . '/assets/images/ikm-poster.png' ) ) : ?>
								<img src="<?php echo esc_url( get_template_directory_uri() . '/assets/images/ikm-poster.png' ); ?>" alt="Indeks Kepuasan Masyarakat BMKG Betoambari" loading="lazy" class="bmkg-enlargeable" style="width:100%;height:auto;border-radius:10px;object-fit:contain;">
							<?php else : ?>
								<div class="bmkg-asset-placeholder"><strong>Poster IKM belum diunggah</strong><span>Tambahkan file <code>assets/images/ikm-poster.png</code> untuk menampilkan poster resmi.</span></div>
							<?php endif; ?>
							</div>
						</figure>
					</div>
				</div>
			</div>
		</div>
	</section>

	<?php /* ====== 6. BULETIN ====== */ ?>
	<?php
	$buletin_q = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 10, 'category_name' => 'buletin', 'post_status' => 'publish' ) );
	if ( $buletin_q->have_posts() ) : ?>
		<section class="bmkg-section" aria-label="Buletin">
			<div class="bmkg-container">
				<header class="bmkg-section__head">
					<h2 class="bmkg-section__title">Buletin</h2>
					<a href="<?php echo esc_url( home_url( '/buletin' ) ); ?>" class="bmkg-section__more">Lihat Semua
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
				</header>
				<div class="bmkg-slider" data-slider data-auto-slide="4000">
					<button class="bmkg-slider__nav bmkg-slider__nav--prev" type="button" aria-label="Sebelumnya" data-slider-prev><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>
					<div class="bmkg-slider__track" data-slider-track>
						<?php while ( $buletin_q->have_posts() ) : $buletin_q->the_post(); ?>
							<article class="bmkg-bulletin-card bmkg-bulletin-card--slide">
								<a href="<?php the_permalink(); ?>" class="bmkg-bulletin-card__poster">
									<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ) ); else : ?>
										<span class="bmkg-media-empty" aria-hidden="true"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"></path></svg></span>
									<?php endif; ?>
									<span class="bmkg-bulletin-card__month"><?php echo esc_html( get_the_date( 'M Y' ) ); ?></span>
								</a>
								<div class="bmkg-bulletin-card__body"><h3 class="bmkg-bulletin-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3></div>
							</article>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
					<button class="bmkg-slider__nav bmkg-slider__nav--next" type="button" aria-label="Berikutnya" data-slider-next><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<?php /* ====== 7. PRODUK ====== */ ?>
	<section class="bmkg-section" aria-label="Produk">
		<div class="bmkg-container">
			<h2 class="bmkg-section__title">Produk &amp; Layanan</h2>
			<div class="bmkg-produk-grid">
				<a href="<?php echo esc_url( home_url( '/flight-documentation/' ) ); ?>" class="bmkg-produk-item">
					<span class="bmkg-produk-item__icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.8 19.2L16 11l3.5-3.5C21 6 21.5 4 21 3c-1-.5-3 0-4.5 1.5L13 8 4.8 6.2c-.5-.1-.9.1-1.1.5l-.3.5c-.2.5-.1 1 .3 1.3L9 12l-2 3H4l-1 1 3 2 2 3 1-1v-3l3-2 3.5 5.3c.3.4.8.5 1.3.3l.5-.2c.4-.3.6-.7.5-1.2z"></path></svg></span>
					<h3>Flight Documentation WAWB</h3><p>METAR/SPECI, TAF, SIGMET &amp; briefing penerbangan</p>
				</a>
				<a href="https://www.bmkg.go.id/cuaca/prakiraan-cuaca.bmkg?kab=Baubau" target="_blank" rel="noopener" class="bmkg-produk-item">
					<span class="bmkg-produk-item__icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M17.5 19a4.5 4.5 0 100-9h-1.8A7 7 0 104 15.5"></path></svg></span>
					<h3>Prakiraan Cuaca</h3><p>Prakiraan cuaca Kota Baubau</p>
				</a>
				<a href="https://maritim.bmkg.go.id/" target="_blank" rel="noopener" class="bmkg-produk-item">
					<span class="bmkg-produk-item__icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M2 16c1.5 1.5 3.5 1.5 5 0s3.5-1.5 5 0 3.5 1.5 5 0 3.5-1.5 5 0"></path><path d="M2 20c1.5 1.5 3.5 1.5 5 0s3.5-1.5 5 0 3.5 1.5 5 0 3.5-1.5 5 0"></path><path d="M4 12l8-8 8 8"></path></svg></span>
					<h3>Cuaca Maritim</h3><p>Gelombang &amp; cuaca perairan</p>
				</a>
				<a href="https://iklim.bmkg.go.id/id/" target="_blank" rel="noopener" class="bmkg-produk-item">
					<span class="bmkg-produk-item__icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"><path d="M3 3v18h18"></path><path d="M7 13l3-3 4 4 5-6"></path></svg></span>
					<h3>Produk Klimatologi</h3><p>Data &amp; analisis iklim</p>
				</a>

				<a href="<?php echo esc_url( home_url( '/layanan-data/' ) ); ?>" class="bmkg-produk-item"><span class="bmkg-produk-item__icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M4 5h16v14H4z"></path><path d="M8 9h8M8 13h8"></path></svg></span><h3>Layanan Data</h3><p>Permohonan data, PTSP, kontak &amp; Data Online</p></a>
				<a href="https://www.bmkg.go.id/cuaca/karhutla" target="_blank" rel="noopener" class="bmkg-produk-item"><span class="bmkg-produk-item__icon"><svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 22c4 0 7-3 7-7 0-5-4-7-3-12-4 2-7 6-7 10-1-1-2-2-2-4-2 2-3 4-3 6 0 4 4 7 8 7z"></path></svg></span><h3>Hotspot &amp; Karhutla</h3><p>Peta hotspot, asap dan indeks kebakaran</p></a>
			</div>
		</div>
	</section>

	<?php /* ====== 8. BERITA & ARTIKEL ====== */ ?>
	<?php
	$berita_q = new WP_Query( array( 'post_type' => 'post', 'posts_per_page' => 10, 'post_status' => 'publish',
		'tax_query' => array( array( 'taxonomy' => 'category', 'field' => 'slug', 'terms' => array( 'berita', 'artikel' ) ) ) ) );
	if ( $berita_q->have_posts() ) : ?>
		<section class="bmkg-section bmkg-section--news" aria-label="Berita dan artikel">
			<div class="bmkg-container">
				<header class="bmkg-section__head">
					<h2 class="bmkg-section__title">Berita &amp; Artikel</h2>
					<a href="<?php echo esc_url( home_url( '/berita' ) ); ?>" class="bmkg-section__more">Lihat Semua
						<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg></a>
				</header>
				<div class="bmkg-slider" data-slider data-auto-slide="4500">
					<button class="bmkg-slider__nav bmkg-slider__nav--prev" type="button" aria-label="Sebelumnya" data-slider-prev><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="15 18 9 12 15 6"></polyline></svg></button>
					<div class="bmkg-slider__track" data-slider-track>
						<?php while ( $berita_q->have_posts() ) : $berita_q->the_post(); $cats = get_the_category(); $cn = ! empty( $cats ) ? $cats[0]->name : 'Berita'; ?>
							<article class="bmkg-card bmkg-card--slide">
								<a href="<?php the_permalink(); ?>" class="bmkg-card__media">
									<?php if ( has_post_thumbnail() ) : the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ) ); else : ?>
										<span class="bmkg-media-empty" aria-hidden="true"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a2 2 0 01-2 2z"></path><path d="M18 14h-8"></path><path d="M15 18h-5"></path></svg></span>
									<?php endif; ?>
									<span class="bmkg-card__badge"><?php echo esc_html( $cn ); ?></span>
								</a>
								<div class="bmkg-card__body">
									<div class="bmkg-card__meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date( 'j M Y' ) ); ?></time></div>
									<h3 class="bmkg-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<p class="bmkg-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 14 ) ); ?></p>
								</div>
							</article>
						<?php endwhile; wp_reset_postdata(); ?>
					</div>
					<button class="bmkg-slider__nav bmkg-slider__nav--next" type="button" aria-label="Berikutnya" data-slider-next><svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="9 18 15 12 9 6"></polyline></svg></button>
				</div>
			</div>
		</section>
	<?php endif; ?>

	<div class="bmkg-weather-modal" id="bmkg-weather-modal" hidden aria-hidden="true">
		<div class="bmkg-weather-modal__backdrop" data-weather-close></div>
		<article class="bmkg-weather-modal__dialog" role="dialog" aria-modal="true" aria-labelledby="bmkg-weather-modal-title">
			<button type="button" class="bmkg-weather-modal__close" data-weather-close aria-label="Tutup">×</button>
			<div class="bmkg-weather-modal__head"><img id="bmkg-weather-modal-icon" src="" alt=""><div><span id="bmkg-weather-modal-kec"></span><h2 id="bmkg-weather-modal-title">Detail cuaca</h2><p id="bmkg-weather-modal-time"></p></div></div>
			<div class="bmkg-weather-modal__stats"><div><span>Suhu</span><strong id="bmkg-weather-modal-temp">—</strong></div><div><span>Kelembapan</span><strong id="bmkg-weather-modal-rh">—</strong></div><div><span>Angin</span><strong id="bmkg-weather-modal-wind">—</strong></div><div><span>Arah Angin</span><strong id="bmkg-weather-modal-wd">—</strong></div><div><span>Jarak Pandang</span><strong id="bmkg-weather-modal-vis">—</strong></div><div><span>Tutupan Awan</span><strong id="bmkg-weather-modal-cloud">—</strong></div></div>
			<p class="bmkg-weather-modal__source">Sumber: Data Terbuka BMKG. Tidak ada data cuaca simulasi pada halaman ini.</p>
		</article>
	</div>

</main>

<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>

<script>
(function(){
	'use strict';
	var REST_URL = '<?php echo esc_url( rest_url( 'bmkg/v1/cuaca' ) ); ?>';
	var CUACA = {};      // adm4 -> {kel,kec,entries}
	var KEL_META = <?php echo wp_json_encode( function_exists( 'bmkg_betoambari_adm4_map' ) ? bmkg_betoambari_adm4_map() : array() ); ?>;
	var KEC_LIST = ['Betoambari','Wolio','Sorawolio','Bungi','Kokalukuna','Murhum','Lea-Lea','Batupoaro'];
	var currentMode = 0;

	/* Meteocons (fill) — icon halus, tanpa frame */
	var ICON = 'https://cdn.jsdelivr.net/gh/basmilius/weather-icons@2.0.0/production/fill/all/';
	function codeMeta(code){
		var m = {
			0:['clear-day','Cerah','cerah'],1:['partly-cloudy-day','Cerah Berawan','berawan'],
			2:['partly-cloudy-day','Cerah Berawan','berawan'],3:['cloudy','Berawan','berawan'],
			4:['overcast','Berawan Tebal','berawan'],5:['haze','Udara Kabur','berawan'],
			10:['haze','Asap','berawan'],45:['fog','Kabut','berawan'],
			60:['drizzle','Hujan Ringan','hujan'],61:['rain','Hujan Sedang','hujan'],
			63:['extreme-rain','Hujan Lebat','hujan'],80:['partly-cloudy-day-rain','Hujan Lokal','hujan'],
			95:['thunderstorms-rain','Hujan Petir','petir'],97:['thunderstorms-extreme-rain','Hujan Petir Lebat','petir']
		};
		var d = m[code] || m[3];
		return { url: ICON + d[0] + '.svg', label: d[1], cls: d[2] };
	}
	/* Pakai image dari API bila ada; kalau kosong -> Meteocons (tidak pernah broken) */
	function iconFor(e){
		var meta = codeMeta(e ? e.code : 3);
		return { url: (e && e.img) ? e.img : meta.url, label: (e && e.desc) || meta.label, cls: meta.cls };
	}
	function parseDt(s){ return s ? new Date(s.replace(' ','T')) : null; }
	function pickEntry(entries, mode){
		if (!entries || !entries.length) return null;
		var now = new Date();
		function closest(list, target){ var best=null,bd=Infinity; list.forEach(function(e){ var d=Math.abs(parseDt(e.dt)-target); if(d<bd){bd=d;best=e;} }); return best; }
		if (mode===0) return closest(entries, now);
		var t;
		if (mode===1) t=new Date(now.getFullYear(),now.getMonth(),now.getDate(),13,0,0);
		else if (mode===2) t=new Date(now.getFullYear(),now.getMonth(),now.getDate()+1,13,0,0);
		else t=new Date(now.getFullYear(),now.getMonth(),now.getDate()+2,13,0,0);
		var same = entries.filter(function(e){ var d=parseDt(e.dt); return d&&d.getDate()===t.getDate()&&d.getMonth()===t.getMonth(); });
		return closest(same.length?same:entries, t);
	}
	function fmtTime(e){ var d=parseDt(e&&e.dt); return d?((d.getHours()<10?'0':'')+d.getHours()+'.00 WITA'):'--.-- WITA'; }

	/* ---- PETA ---- */
	var mapEl = document.getElementById('bmkg-map');
	var map, markers = {};
	var titik = [];
	if (mapEl){
		try { titik = JSON.parse(mapEl.dataset.titik || '[]'); } catch(e){}
		map = L.map('bmkg-map', { center:[-5.47,122.62], zoom:11, scrollWheelZoom:false });
		L.tileLayer('https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png', { attribution:'&copy; OpenStreetMap &copy; CARTO', maxZoom:18 }).addTo(map);
		function makeIcon(info, temp){
			return L.divIcon({ className:'bmkg-marker bmkg-marker--'+info.cls,
				html:'<img src="'+info.url+'" alt=""><span class="bmkg-marker__temp">'+(temp!=null?temp+'°':'')+'</span>',
				iconSize:[54,54], iconAnchor:[27,27] });
		}
		titik.forEach(function(t){
			markers[t.nama] = L.marker([t.lat,t.lng], { icon: makeIcon(codeMeta(3), null) })
				.bindTooltip('<strong>'+t.nama+'</strong>', {direction:'top', offset:[0,-16]}).addTo(map);
		});

		/* Dropdown wilayah custom (poin 5) */
		var input = document.getElementById('bmkg-search-input');
		var list = document.getElementById('bmkg-wilayah-list');
		if (input && list){
			titik.forEach(function(t){
				var b = document.createElement('button'); b.type='button'; b.textContent = t.nama;
				b.addEventListener('click', function(){ input.value=t.nama; list.classList.remove('is-open'); map.flyTo([t.lat,t.lng],12,{duration:.7}); var m=markers[t.nama]; if(m)m.openTooltip(); });
				list.appendChild(b);
			});
			input.addEventListener('focus', function(){ list.classList.add('is-open'); });
			input.addEventListener('input', function(){
				var v=this.value.toLowerCase();
				Array.prototype.forEach.call(list.children,function(b){ b.style.display = b.textContent.toLowerCase().indexOf(v)>-1 ? '' : 'none'; });
				list.classList.add('is-open');
			});
			document.addEventListener('click', function(e){ if(!e.target.closest('.bmkg-mapsearch')) list.classList.remove('is-open'); });
		}
	}

	/* ---- PRAKIRAAN 43 KELURAHAN + DETAIL ---- */
	var track = document.querySelector('[data-forecast-track]');
	var kecFilter = document.getElementById('bmkg-kec-filter');
	var sourceNote = document.getElementById('bmkg-weather-source');
	function esc(v){ var d=document.createElement('div'); d.textContent=(v==null?'':String(v)); return d.innerHTML; }
	function conditionClass(info){ return info && info.cls ? ' bmkg-forecast-card--'+info.cls : ''; }
	function buildForecast(mode){
		if (!track) return;
		var selected = kecFilter ? kecFilter.value : '';
		track.innerHTML = '';
		Object.keys(KEL_META).forEach(function(adm4){
			var meta=KEL_META[adm4]; if(selected && meta.kec!==selected) return;
			var row=CUACA[adm4] || {kel:meta.kel,kec:meta.kec,entries:[]};
			var e=pickEntry(row.entries,mode), info=iconFor(e);
			var card=document.createElement('article');
			card.className='bmkg-forecast-card'+conditionClass(info)+(e?'':' is-unavailable'); card.tabIndex=0; card.setAttribute('role','button'); card.dataset.adm4=adm4; card.setAttribute('aria-label','Detail cuaca '+meta.kel+', Kecamatan '+meta.kec);
			card.innerHTML='<h3 class="bmkg-forecast-card__name">'+esc(meta.kec)+'</h3><p class="bmkg-forecast-card__sub">'+esc(meta.kel)+'</p><p class="bmkg-forecast-card__time">'+(e?fmtTime(e):'Data belum tersedia')+'</p><div class="bmkg-forecast-card__icon"><img src="'+esc(info.url)+'" alt="'+esc(info.label)+'" width="52" height="52"></div><p class="bmkg-forecast-card__temp">'+(e&&e.t!=null?esc(e.t)+'<em>°C</em>':'<span>—</span>')+'</p><p class="bmkg-forecast-card__cond">'+(e?esc(info.label):'Menunggu BMKG')+'</p><span class="bmkg-forecast-card__detail">Lihat detail →</span>';
			card.addEventListener('click',function(){openWeatherModal(adm4);}); card.addEventListener('keydown',function(ev){if(ev.key==='Enter'||ev.key===' '){ev.preventDefault();openWeatherModal(adm4);}}); track.appendChild(card);
		});
		if(!track.children.length) track.innerHTML='<div class="bmkg-forecast-loading">Tidak ada kelurahan pada filter ini.</div>';
	}
	function updateMap(mode){
		if(!map)return; titik.forEach(function(t){var row=CUACA[t.adm4],e=row?pickEntry(row.entries,mode):null,info=iconFor(e),m=markers[t.nama];if(m){m.setIcon(L.divIcon({className:'bmkg-marker bmkg-marker--'+info.cls,html:'<img src="'+info.url+'" alt=""><span class="bmkg-marker__temp">'+(e&&e.t!=null?e.t+'°':'')+'</span>',iconSize:[54,54],iconAnchor:[27,27]}));var tt=m.getTooltip();if(tt)tt.setContent('<strong>'+t.nama+'</strong><br>'+(e?info.label:'Data BMKG belum tersedia')+(e&&e.t!=null?' '+e.t+'°C':''));}});
		var kt=titik.find(function(t){return t.nama==='Betoambari';}),krow=kt?CUACA[kt.adm4]:null,ke=krow?pickEntry(krow.entries,0):null,ht=document.getElementById('bmkg-hero-temp'),hc=document.getElementById('bmkg-hero-cond'),hi=document.getElementById('bmkg-hero-icon'); if(ke){var ki=iconFor(ke);if(ht)ht.textContent=(ke.t!=null?ke.t:'--')+'°C';if(hc)hc.textContent=ki.label+' — Betoambari';if(hi){hi.onerror=function(){this.hidden=true;};hi.src=ki.url;hi.hidden=false;}}else{if(ht)ht.textContent='--°C';if(hc)hc.textContent='Data BMKG belum tersedia';if(hi)hi.hidden=true;}
	}
	function refresh(mode){buildForecast(mode);updateMap(mode);}
	function load(){
		if(sourceNote)sourceNote.textContent='Menghubungkan ke Data Terbuka BMKG…';
		Promise.all(KEC_LIST.map(function(k){return fetch(REST_URL+'?kec='+encodeURIComponent(k),{credentials:'same-origin'}).then(function(r){if(!r.ok)throw new Error('HTTP '+r.status);return r.json();}).catch(function(){return {data:{}};});})).then(function(rows){var merged={};rows.forEach(function(j){if(j&&j.data)Object.keys(j.data).forEach(function(key){merged[key]=j.data[key];});});CUACA=merged;var n=Object.keys(CUACA).length;if(sourceNote)sourceNote.textContent=n?'Data BMKG aktif: '+n+' dari 43 kelurahan termuat':'API BMKG belum dapat diakses — seluruh 43 kelurahan tetap ditampilkan tanpa simulasi';refresh(currentMode);bindSliders();});
	}
	refresh(currentMode); load(); setInterval(load,30*60*1000); if(kecFilter)kecFilter.addEventListener('change',function(){buildForecast(currentMode);});
	var weatherModal=document.getElementById('bmkg-weather-modal');
	function setTxt(id,val){var el=document.getElementById(id);if(el)el.textContent=val;}
	function openWeatherModal(adm4){if(!weatherModal)return;var meta=KEL_META[adm4]||{},row=CUACA[adm4],e=row?pickEntry(row.entries,currentMode):null,info=iconFor(e);setTxt('bmkg-weather-modal-kec','Kecamatan '+(meta.kec||'—'));setTxt('bmkg-weather-modal-title',meta.kel||'Detail cuaca');setTxt('bmkg-weather-modal-time',e?fmtTime(e):'Data BMKG belum tersedia');setTxt('bmkg-weather-modal-temp',e&&e.t!=null?e.t+' °C':'—');setTxt('bmkg-weather-modal-rh',e&&e.hu!=null?e.hu+' %':'—');setTxt('bmkg-weather-modal-wind',e&&e.ws!=null?e.ws+' km/jam':'—');setTxt('bmkg-weather-modal-wd',e&&e.wd?e.wd+(e.wd_deg!=null?' ('+e.wd_deg+'°)':''):'—');setTxt('bmkg-weather-modal-vis',e&&e.vs_text?e.vs_text:'—');setTxt('bmkg-weather-modal-cloud',e&&e.tcc!=null?e.tcc+' %':'—');var img=document.getElementById('bmkg-weather-modal-icon');if(img){img.src=info.url;img.alt=info.label;}weatherModal.hidden=false;weatherModal.setAttribute('aria-hidden','false');document.body.classList.add('bmkg-modal-open');}
	function closeWeatherModal(){if(!weatherModal)return;weatherModal.hidden=true;weatherModal.setAttribute('aria-hidden','true');document.body.classList.remove('bmkg-modal-open');}
	document.querySelectorAll('[data-weather-close]').forEach(function(b){b.addEventListener('click',closeWeatherModal);});document.addEventListener('keydown',function(e){if(e.key==='Escape')closeWeatherModal();});

	document.querySelectorAll('[data-forecast-day]').forEach(function(tab){
		tab.addEventListener('click', function(){
			document.querySelectorAll('[data-forecast-day]').forEach(function(t){ t.classList.remove('is-active'); t.setAttribute('aria-selected','false'); });
			tab.classList.add('is-active'); tab.setAttribute('aria-selected','true');
			currentMode = parseInt(tab.dataset.forecastDay,10)||0;
			refresh(currentMode);
		});
	});


	/* ---- Slider generik (tombol + drag + auto) ---- */
	var slidersBound = false;
	function bindSliders(){
		if (slidersBound) return; slidersBound = true;
		document.querySelectorAll('[data-slider]').forEach(function(slider){
			var track=slider.querySelector('[data-slider-track]'), prev=slider.querySelector('[data-slider-prev]'), next=slider.querySelector('[data-slider-next]');
			if(!track) return;
			function step(){ var f=track.querySelector(':scope > *'); if(!f)return 260; var g=parseFloat(getComputedStyle(track).columnGap||getComputedStyle(track).gap||14)||14; return f.getBoundingClientRect().width+g; }
			function atEnd(){ return track.scrollLeft+track.clientWidth >= track.scrollWidth-8; }
			function goNext(){ atEnd()?track.scrollTo({left:0,behavior:'smooth'}):track.scrollBy({left:step()*2,behavior:'smooth'}); }
			function goPrev(){ track.scrollBy({left:-step()*2,behavior:'smooth'}); }
			if(prev)prev.addEventListener('click',function(){goPrev();restart();});
			if(next)next.addEventListener('click',function(){goNext();restart();});
			var autoMs=parseInt(slider.dataset.autoSlide,10)||0, timer=null;
			function start(){ if(autoMs>0&&track.scrollWidth>track.clientWidth+8){stop();timer=setInterval(goNext,autoMs);} }
			function stop(){ if(timer){clearInterval(timer);timer=null;} }
			function restart(){ stop();start(); }
			slider.addEventListener('mouseenter',stop); slider.addEventListener('mouseleave',start); start();
			var drag=false,sx=0,sl=0;
			track.addEventListener('mousedown',function(e){drag=true;sx=e.pageX;sl=track.scrollLeft;track.classList.add('is-grabbing');stop();});
			window.addEventListener('mousemove',function(e){if(drag){e.preventDefault();track.scrollLeft=sl-(e.pageX-sx);}});
			window.addEventListener('mouseup',function(){if(drag){drag=false;track.classList.remove('is-grabbing');start();}});
		});
	}
	bindSliders();

	/* ---- Reveal ringan saat scroll ---- */
	var revealEls=document.querySelectorAll('.bmkg-section');
	if('IntersectionObserver' in window){var io=new IntersectionObserver(function(entries){entries.forEach(function(en){if(en.isIntersecting){en.target.classList.add('is-visible');io.unobserve(en.target);}});},{threshold:.08});revealEls.forEach(function(el){el.classList.add('bmkg-reveal');io.observe(el);});}else{revealEls.forEach(function(el){el.classList.add('is-visible');});}

	/* ---- Peringatan: navigasi + auto ---- */
	var items=document.querySelectorAll('[data-alert-items] .bmkg-warn__item');
	if(items.length>1){
		var ai=0;
		function showA(i){ ai=(i+items.length)%items.length; items.forEach(function(it,n){it.classList.toggle('is-active',n===ai);}); }
		var p=document.querySelector('[data-alert-prev]'),n=document.querySelector('[data-alert-next]');
		if(p)p.addEventListener('click',function(){showA(ai-1);});
		if(n)n.addEventListener('click',function(){showA(ai+1);});
		setInterval(function(){showA(ai+1);},8000);
	}
})();
</script>

<?php get_footer(); ?>
