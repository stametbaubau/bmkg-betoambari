<?php
/**
 * Template Name: Halaman Berita
 *
 * Mengikuti standar desain BMKG Pusat & Kalteng:
 * white theme, card polos, typografi Inter, tanpa gradient / inline style.
 *
 * @package BMKG_Betoambari
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

$paged = get_query_var( 'paged' ) ? get_query_var( 'paged' ) : 1;

$news_query = new WP_Query( array(
	'post_type'      => 'post',
	'posts_per_page' => 12,
	'paged'          => $paged,
	'post_status'    => 'publish',
	'tax_query'      => array(
		array(
			'taxonomy' => 'category',
			'field'    => 'slug',
			'terms'    => array( 'berita', 'artikel' ),
		),
	),
) );

/* Fallback: bila belum ada post berkategori berita/artikel, tampilkan post terbaru
   (kecuali yang berkategori buletin) agar halaman tidak kosong. */
if ( ! $news_query->have_posts() ) {
	$buletin_cat = get_category_by_slug( 'buletin' );
	$news_query  = new WP_Query( array(
		'post_type'      => 'post',
		'posts_per_page' => 12,
		'paged'          => $paged,
		'post_status'    => 'publish',
		'category__not_in' => $buletin_cat ? array( $buletin_cat->term_id ) : array(),
	) );
}
?>

<main class="bmkg-main bmkg-page">

	<div class="bmkg-container">

		<header class="bmkg-page__head">
			<h1 class="bmkg-page__title"><?php the_title(); ?></h1>
			<p class="bmkg-page__subtitle">
				<?php echo esc_html( ( function_exists( 'get_field' ) && get_field( 'page_subtitle' ) ) ? get_field( 'page_subtitle' ) : 'Berita terbaru dan informasi terkini dari BMKG Betoambari' ); ?>
			</p>
		</header>

		<div class="bmkg-filter" role="tablist" aria-label="Filter kategori">
			<button type="button" class="bmkg-filter__btn is-active" role="tab" aria-selected="true" data-filter="all">Semua</button>
			<button type="button" class="bmkg-filter__btn" role="tab" aria-selected="false" data-filter="berita">Berita</button>
			<button type="button" class="bmkg-filter__btn" role="tab" aria-selected="false" data-filter="artikel">Artikel</button>
		</div>

		<?php if ( $news_query->have_posts() ) : ?>

			<div class="bmkg-card-grid" id="bmkg-news-grid">
				<?php
				while ( $news_query->have_posts() ) :
					$news_query->the_post();
					$cats     = get_the_category();
					$cat_slug = ! empty( $cats ) ? $cats[0]->slug : 'berita';
					$cat_name = ! empty( $cats ) ? $cats[0]->name : 'Berita';
					?>
					<article class="bmkg-card" data-type="<?php echo esc_attr( $cat_slug ); ?>">
						<a href="<?php the_permalink(); ?>" class="bmkg-card__media" aria-hidden="true" tabindex="-1">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ) ); ?>
							<?php else : ?>
								<span class="bmkg-media-empty" aria-hidden="true"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a2 2 0 01-2 2zm0 0a2 2 0 01-2-2v-9c0-1.1.9-2 2-2h2"></path><path d="M18 14h-8"></path><path d="M15 18h-5"></path><path d="M10 6h8v4h-8V6z"></path></svg></span>
							<?php endif; ?>
							<span class="bmkg-card__badge"><?php echo esc_html( $cat_name ); ?></span>
						</a>

						<div class="bmkg-card__body">
							<div class="bmkg-card__meta">
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
										<rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect>
										<line x1="16" y1="2" x2="16" y2="6"></line>
										<line x1="8" y1="2" x2="8" y2="6"></line>
										<line x1="3" y1="10" x2="21" y2="10"></line>
									</svg>
									<?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
								</time>
							</div>

							<h2 class="bmkg-card__title">
								<a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
							</h2>

							<p class="bmkg-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>

							<a href="<?php the_permalink(); ?>" class="bmkg-card__more">
								Baca selengkapnya
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
									<line x1="5" y1="12" x2="19" y2="12"></line>
									<polyline points="12 5 19 12 12 19"></polyline>
								</svg>
							</a>
						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<p class="bmkg-empty is-hidden" data-empty-message>Tidak ada berita atau artikel pada kategori ini.</p>

			<nav class="bmkg-pagination" aria-label="Navigasi halaman">
				<?php
				echo paginate_links( array(
					'total'     => $news_query->max_num_pages,
					'current'   => $paged,
					'prev_text' => '&larr; Sebelumnya',
					'next_text' => 'Berikutnya &rarr;',
				) );
				?>
			</nav>

		<?php else : ?>

			<p class="bmkg-empty">Belum ada berita atau artikel yang dipublikasikan.</p>

		<?php endif; ?>

		<?php wp_reset_postdata(); ?>

	</div>
</main>

<script>
(function(){
	'use strict';
	var btns  = document.querySelectorAll('.bmkg-filter__btn');
	var cards = document.querySelectorAll('#bmkg-news-grid .bmkg-card');
	var emptyMsg = document.querySelector('[data-empty-message]');

	btns.forEach(function(btn){
		btn.addEventListener('click', function(){
			var filter = btn.dataset.filter;

			btns.forEach(function(b){
				b.classList.remove('is-active');
				b.setAttribute('aria-selected', 'false');
			});
			btn.classList.add('is-active');
			btn.setAttribute('aria-selected', 'true');

			var visible = 0;
			cards.forEach(function(card){
				var show = (filter === 'all' || card.dataset.type === filter);
				card.style.display = show ? '' : 'none';
				if (show) visible++;
			});

			if (emptyMsg) emptyMsg.classList.toggle('is-hidden', visible > 0);
		});
	});
})();
</script>

<?php get_footer(); ?>
