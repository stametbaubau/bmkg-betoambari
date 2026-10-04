<?php
/**
 * Template Name: Halaman Buletin
 *
 * Mengikuti standar desain BMKG Pusat & Kalteng:
 * white theme, card poster polos, filter tahun, tanpa gradient / inline style.
 *
 * @package BMKG_Betoambari
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

$bulletin_query = new WP_Query( array(
	'post_type'      => 'post',
	'posts_per_page' => 12,
	'paged'          => $paged,
	'post_status'    => 'publish',
	'category_name'  => 'buletin',
) );

/* Fallback: bila kategori 'buletin' kosong, coba meta lama post_type_select */
if ( ! $bulletin_query->have_posts() ) {
	$bulletin_query = new WP_Query( array(
		'post_type'      => 'post',
		'posts_per_page' => 12,
		'paged'          => $paged,
		'post_status'    => 'publish',
		'meta_key'       => 'post_type_select',
		'meta_value'     => 'buletin',
	) );
}

/* Kumpulkan daftar tahun unik untuk filter dinamis */
$bmkg_years = array();
if ( $bulletin_query->have_posts() ) {
	foreach ( $bulletin_query->posts as $p ) {
		$y = get_the_date( 'Y', $p );
		$bmkg_years[ $y ] = true;
	}
}
krsort( $bmkg_years );
?>

<main class="bmkg-main bmkg-page">

	<div class="bmkg-container">

		<header class="bmkg-page__head">
			<h1 class="bmkg-page__title">Buletin BMKG Betoambari</h1>
			<p class="bmkg-page__subtitle">
				Publikasi berkala berisi informasi cuaca, iklim, dan geofisika di wilayah Kota Baubau dan sekitarnya.
			</p>
		</header>

		<?php if ( ! empty( $bmkg_years ) ) : ?>
			<div class="bmkg-filter" role="tablist" aria-label="Filter tahun">
				<button type="button" class="bmkg-filter__btn is-active" role="tab" aria-selected="true" data-year="all">Semua Tahun</button>
				<?php foreach ( array_keys( $bmkg_years ) as $year ) : ?>
					<button type="button" class="bmkg-filter__btn" role="tab" aria-selected="false" data-year="<?php echo esc_attr( $year ); ?>">
						<?php echo esc_html( $year ); ?>
					</button>
				<?php endforeach; ?>
			</div>
		<?php endif; ?>

		<?php if ( $bulletin_query->have_posts() ) : ?>

			<div class="bmkg-bulletin-grid" id="bmkg-bulletin-grid">
				<?php
				while ( $bulletin_query->have_posts() ) :
					$bulletin_query->the_post();
					$year  = get_the_date( 'Y' );
					$month = get_the_date( 'F' );
					?>
					<article class="bmkg-bulletin-card" data-year="<?php echo esc_attr( $year ); ?>">
						<a href="<?php the_permalink(); ?>" class="bmkg-bulletin-card__poster">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'large', array( 'alt' => get_the_title() ) ); ?>
							<?php else : ?>
								<span class="bmkg-media-empty" aria-hidden="true"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 19.5A2.5 2.5 0 016.5 17H20"></path><path d="M6.5 2H20v20H6.5A2.5 2.5 0 014 19.5v-15A2.5 2.5 0 016.5 2z"></path></svg></span>
							<?php endif; ?>
							<span class="bmkg-bulletin-card__month"><?php echo esc_html( $month ); ?></span>
							<span class="bmkg-bulletin-card__tag">Buletin</span>
						</a>

						<div class="bmkg-bulletin-card__body">
							<h2 class="bmkg-bulletin-card__title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>
							<p class="bmkg-bulletin-card__date">
								<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
									<line x1="16" y1="2" x2="16" y2="6"></line>
									<line x1="8" y1="2" x2="8" y2="6"></line>
									<line x1="3" y1="10" x2="21" y2="10"></line>
								</svg>
								<?php echo esc_html( get_the_date( 'F Y' ) ); ?>
							</p>
						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<p class="bmkg-empty is-hidden" data-empty-message>Tidak ada buletin pada tahun ini.</p>

			<nav class="bmkg-pagination" aria-label="Navigasi halaman">
				<?php
				echo paginate_links( array(
					'total'     => $bulletin_query->max_num_pages,
					'current'   => $paged,
					'prev_text' => '&larr; Sebelumnya',
					'next_text' => 'Berikutnya &rarr;',
				) );
				?>
			</nav>

		<?php else : ?>

			<p class="bmkg-empty">Belum ada buletin yang dipublikasikan.</p>

		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

	</div>
</main>

<script>
(function(){
	'use strict';
	var btns  = document.querySelectorAll('.bmkg-filter__btn');
	var cards = document.querySelectorAll('#bmkg-bulletin-grid .bmkg-bulletin-card');
	var emptyMsg = document.querySelector('[data-empty-message]');

	btns.forEach(function(btn){
		btn.addEventListener('click', function(){
			var year = btn.dataset.year;

			btns.forEach(function(b){
				b.classList.remove('is-active');
				b.setAttribute('aria-selected', 'false');
			});
			btn.classList.add('is-active');
			btn.setAttribute('aria-selected', 'true');

			var visible = 0;
			cards.forEach(function(card){
				var show = (year === 'all' || card.dataset.year === year);
				card.style.display = show ? '' : 'none';
				if (show) visible++;
			});

			if (emptyMsg) emptyMsg.classList.toggle('is-hidden', visible > 0);
		});
	});
})();
</script>

<?php get_footer(); ?>
