<?php
/**
 * Template Name: Daftar Pegawai
 *
 * Menampilkan pegawai dari Custom Post Type "pegawai" dalam bentuk slider
 * yang auto-slide dan bisa digeser manual (drag / tombol / dot).
 * Data diisi lewat WP Admin → Pegawai (Nama, Foto, Jabatan, NIP, Urutan).
 *
 * @package BMKG_Betoambari
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$pegawai_query = function_exists( 'bmkg_get_pegawai_list' )
	? bmkg_get_pegawai_list()
	: new WP_Query( array( 'post_type' => 'pegawai', 'posts_per_page' => -1, 'post_status' => 'publish' ) );
?>

<main class="bmkg-main bmkg-page">
	<div class="bmkg-container">

		<header class="bmkg-page__head">
			<h1 class="bmkg-page__title">Daftar Pegawai</h1>
			<p class="bmkg-page__subtitle">Stasiun Meteorologi Betoambari</p>
		</header>

		<?php if ( $pegawai_query->have_posts() ) : ?>

			<div class="bmkg-staff" data-staff-slider>
				<button type="button" class="bmkg-staff__nav bmkg-staff__nav--prev" aria-label="Sebelumnya" data-staff-prev>
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="15 18 9 12 15 6"></polyline></svg>
				</button>

				<div class="bmkg-staff__viewport" data-staff-viewport>
					<div class="bmkg-staff__track" data-staff-track>
						<?php
						while ( $pegawai_query->have_posts() ) :
							$pegawai_query->the_post();
							$jabatan = get_post_meta( get_the_ID(), '_bmkg_pegawai_jabatan', true );
							$nip     = get_post_meta( get_the_ID(), '_bmkg_pegawai_nip', true );
							?>
							<article class="bmkg-staff-card">
								<div class="bmkg-staff-card__photo">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ) ); ?>
									<?php else : ?>
										<span class="bmkg-staff-card__placeholder" aria-hidden="true">
											<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
												<path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path>
												<circle cx="12" cy="7" r="4"></circle>
											</svg>
										</span>
									<?php endif; ?>
								</div>
								<div class="bmkg-staff-card__body">
									<h2 class="bmkg-staff-card__name"><?php the_title(); ?></h2>
									<?php if ( $jabatan ) : ?>
										<p class="bmkg-staff-card__role"><?php echo esc_html( $jabatan ); ?></p>
									<?php endif; ?>
									<?php if ( $nip ) : ?>
										<p class="bmkg-staff-card__nip">NIP. <?php echo esc_html( $nip ); ?></p>
									<?php endif; ?>
								</div>
							</article>
							<?php
						endwhile;
						?>
					</div>
				</div>

				<button type="button" class="bmkg-staff__nav bmkg-staff__nav--next" aria-label="Berikutnya" data-staff-next>
					<svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><polyline points="9 18 15 12 9 6"></polyline></svg>
				</button>

				<div class="bmkg-staff__dots" data-staff-dots aria-hidden="true"></div>
			</div>

		<?php else : ?>

			<div class="bmkg-prose-card" style="text-align:center;">
				<p class="bmkg-empty" style="padding:24px 0;">Data pegawai belum tersedia. Silakan tambahkan melalui menu <strong>Pegawai</strong> di dashboard.</p>
			</div>

		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

	</div>
</main>

<script>
(function(){
	'use strict';
	var root = document.querySelector('[data-staff-slider]');
	if (!root) return;

	var viewport = root.querySelector('[data-staff-viewport]');
	var track    = root.querySelector('[data-staff-track]');
	var prevBtn  = root.querySelector('[data-staff-prev]');
	var nextBtn  = root.querySelector('[data-staff-next]');
	var dotsWrap = root.querySelector('[data-staff-dots]');
	var cards    = Array.prototype.slice.call(track.children);
	if (!cards.length) return;

	var index       = 0;
	var autoTimer   = null;
	var AUTO_MS     = 3500;
	var isDragging  = false;
	var startX      = 0;
	var scrollStart = 0;

	/* Berapa kartu yang muat per "halaman" */
	function perView(){
		var w = window.innerWidth;
		if (w < 560) return 1;
		if (w < 900) return 2;
		if (w < 1200) return 3;
		return 4;
	}

	function maxIndex(){
		return Math.max(0, cards.length - perView());
	}

	function cardStep(){
		// lebar 1 kartu + gap
		var style = getComputedStyle(track);
		var gap = parseFloat(style.columnGap || style.gap || 0) || 0;
		return cards[0].getBoundingClientRect().width + gap;
	}

	function goTo(i, smooth){
		index = Math.min(Math.max(i, 0), maxIndex());
		viewport.scrollTo({
			left: index * cardStep(),
			behavior: smooth === false ? 'auto' : 'smooth'
		});
		updateDots();
	}

	function next(){
		if (index >= maxIndex()) { goTo(0); }
		else { goTo(index + 1); }
	}
	function prev(){
		if (index <= 0) { goTo(maxIndex()); }
		else { goTo(index - 1); }
	}

	/* ---- Dots ---- */
	function buildDots(){
		if (!dotsWrap) return;
		dotsWrap.innerHTML = '';
		var pages = maxIndex() + 1;
		for (var i = 0; i < pages; i++){
			var b = document.createElement('button');
			b.type = 'button';
			b.className = 'bmkg-staff__dot';
			b.setAttribute('aria-label', 'Ke halaman ' + (i+1));
			(function(idx){
				b.addEventListener('click', function(){ goTo(idx); restartAuto(); });
			})(i);
			dotsWrap.appendChild(b);
		}
		updateDots();
	}
	function updateDots(){
		if (!dotsWrap) return;
		Array.prototype.forEach.call(dotsWrap.children, function(dot, i){
			dot.classList.toggle('is-active', i === index);
		});
	}

	/* ---- Auto slide ---- */
	function startAuto(){
		if (cards.length <= perView()) return; // tidak perlu auto kalau semua muat
		stopAuto();
		autoTimer = setInterval(next, AUTO_MS);
	}
	function stopAuto(){ if (autoTimer){ clearInterval(autoTimer); autoTimer = null; } }
	function restartAuto(){ stopAuto(); startAuto(); }

	/* ---- Tombol ---- */
	if (nextBtn) nextBtn.addEventListener('click', function(){ next(); restartAuto(); });
	if (prevBtn) prevBtn.addEventListener('click', function(){ prev(); restartAuto(); });

	/* ---- Pause saat hover ---- */
	root.addEventListener('mouseenter', stopAuto);
	root.addEventListener('mouseleave', startAuto);

	/* ---- Drag / swipe manual ---- */
	function pointerDown(x){
		isDragging = true;
		startX = x;
		scrollStart = viewport.scrollLeft;
		viewport.classList.add('is-grabbing');
		stopAuto();
	}
	function pointerMove(x){
		if (!isDragging) return;
		viewport.scrollLeft = scrollStart - (x - startX);
	}
	function pointerUp(){
		if (!isDragging) return;
		isDragging = false;
		viewport.classList.remove('is-grabbing');
		// snap ke kartu terdekat
		index = Math.round(viewport.scrollLeft / cardStep());
		goTo(index);
		restartAuto();
	}

	viewport.addEventListener('mousedown', function(e){ pointerDown(e.pageX); });
	window.addEventListener('mousemove', function(e){ if (isDragging){ e.preventDefault(); pointerMove(e.pageX); } });
	window.addEventListener('mouseup', pointerUp);

	viewport.addEventListener('touchstart', function(e){ pointerDown(e.touches[0].pageX); }, {passive:true});
	viewport.addEventListener('touchmove', function(e){ pointerMove(e.touches[0].pageX); }, {passive:true});
	viewport.addEventListener('touchend', pointerUp);

	/* ---- Keyboard ---- */
	root.setAttribute('tabindex','0');
	root.addEventListener('keydown', function(e){
		if (e.key === 'ArrowRight'){ next(); restartAuto(); }
		if (e.key === 'ArrowLeft'){ prev(); restartAuto(); }
	});

	/* ---- Resize ---- */
	var rt;
	window.addEventListener('resize', function(){
		clearTimeout(rt);
		rt = setTimeout(function(){ buildDots(); goTo(index, false); }, 150);
	});

	/* ---- Init ---- */
	buildDots();
	startAuto();
})();
</script>

<?php get_footer(); ?>
