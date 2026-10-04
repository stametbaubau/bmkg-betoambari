<?php
/**
 * Archive Template (Berita & Artikel)
 *
 * White theme BMKG. Filter kategori + sort. Tanpa gradient / inline style.
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

		<header class="bmkg-page__head">
			<h1 class="bmkg-page__title">
				<?php
				if ( is_category() ) {
					single_cat_title();
				} elseif ( is_tag() ) {
					single_tag_title();
				} elseif ( is_author() ) {
					the_author();
				} elseif ( is_date() ) {
					echo esc_html( get_the_archive_title() );
				} else {
					echo 'Berita &amp; Informasi';
				}
				?>
			</h1>
			<p class="bmkg-page__subtitle">Berita terbaru dan informasi terkini dari BMKG Betoambari</p>
		</header>

		<div class="bmkg-archive-controls">
			<div class="bmkg-filter" role="tablist" aria-label="Filter kategori">
				<button type="button" class="bmkg-filter__btn is-active" role="tab" aria-selected="true" data-filter="all">Semua</button>
				<button type="button" class="bmkg-filter__btn" role="tab" aria-selected="false" data-filter="berita">Berita</button>
				<button type="button" class="bmkg-filter__btn" role="tab" aria-selected="false" data-filter="artikel">Artikel</button>
			</div>

			<label class="bmkg-sort">
				<span>Urutkan:</span>
				<select id="bmkg-sort-select">
					<option value="date-desc">Terbaru</option>
					<option value="date-asc">Terlama</option>
					<option value="title-asc">Judul (A&ndash;Z)</option>
					<option value="title-desc">Judul (Z&ndash;A)</option>
				</select>
			</label>
		</div>

		<?php if ( have_posts() ) : ?>

			<div class="bmkg-card-grid" id="bmkg-news-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$cats      = get_the_category();
					$cat_slug  = ! empty( $cats ) ? $cats[0]->slug : '';
					$meta_type = get_post_meta( get_the_ID(), 'post_type_select', true );
					$type      = ( 'artikel' === $cat_slug || 'artikel' === $meta_type ) ? 'artikel' : 'berita';
					$type_name = ucfirst( $type );
					?>
					<article class="bmkg-card"
						data-type="<?php echo esc_attr( $type ); ?>"
						data-date="<?php echo esc_attr( get_the_date( 'Y-m-d' ) ); ?>"
						data-title="<?php echo esc_attr( get_the_title() ); ?>">
						<a href="<?php the_permalink(); ?>" class="bmkg-card__media">
							<?php if ( has_post_thumbnail() ) : ?>
								<?php the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ) ); ?>
							<?php else : ?>
								<span class="bmkg-media-empty" aria-hidden="true"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a2 2 0 01-2 2zm0 0a2 2 0 01-2-2v-9c0-1.1.9-2 2-2h2"></path><path d="M18 14h-8"></path><path d="M15 18h-5"></path><path d="M10 6h8v4h-8V6z"></path></svg></span>
							<?php endif; ?>
							<span class="bmkg-card__badge"><?php echo esc_html( $type_name ); ?></span>
						</a>
						<div class="bmkg-card__body">
							<div class="bmkg-card__meta">
								<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
									<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
									<?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
								</time>
							</div>
							<h2 class="bmkg-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
							<p class="bmkg-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 22 ) ); ?></p>
							<a href="<?php the_permalink(); ?>" class="bmkg-card__more">
								Baca selengkapnya
								<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><line x1="5" y1="12" x2="19" y2="12"></line><polyline points="12 5 19 12 12 19"></polyline></svg>
							</a>
						</div>
					</article>
					<?php
				endwhile;
				?>
			</div>

			<p class="bmkg-empty is-hidden" data-empty-message>Tidak ada konten pada kategori ini.</p>

			<?php if ( $GLOBALS['wp_query']->max_num_pages > 1 ) : ?>
				<nav class="bmkg-pagination" aria-label="Navigasi halaman">
					<?php
					echo paginate_links( array(
						'mid_size'  => 2,
						'prev_text' => '&larr; Sebelumnya',
						'next_text' => 'Berikutnya &rarr;',
					) );
					?>
				</nav>
			<?php endif; ?>

		<?php else : ?>

			<div class="bmkg-empty-state">
				<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a2 2 0 01-2 2zm0 0a2 2 0 01-2-2v-9c0-1.1.9-2 2-2h2"></path><path d="M18 14h-8"></path><path d="M15 18h-5"></path><path d="M10 6h8v4h-8V6z"></path></svg>
				<h2>Belum Ada Konten</h2>
				<p>Belum ada berita atau artikel yang dipublikasikan.</p>
			</div>

		<?php endif; ?>

	</div>
</main>

<script>
(function(){
	'use strict';
	var btns   = document.querySelectorAll('.bmkg-filter__btn');
	var grid   = document.getElementById('bmkg-news-grid');
	var select = document.getElementById('bmkg-sort-select');
	var empty  = document.querySelector('[data-empty-message]');
	if (!grid) return;
	var cards = Array.prototype.slice.call(grid.querySelectorAll('.bmkg-card'));

	/* ---- Filter ---- */
	btns.forEach(function(btn){
		btn.addEventListener('click', function(){
			var filter = btn.dataset.filter;
			btns.forEach(function(b){ b.classList.remove('is-active'); b.setAttribute('aria-selected','false'); });
			btn.classList.add('is-active');
			btn.setAttribute('aria-selected','true');

			var visible = 0;
			cards.forEach(function(card){
				var show = (filter === 'all' || card.dataset.type === filter);
				card.style.display = show ? '' : 'none';
				if (show) visible++;
			});
			if (empty) empty.classList.toggle('is-hidden', visible > 0);
		});
	});

	/* ---- Sort ---- */
	if (select){
		select.addEventListener('change', function(){
			var v = select.value;
			var sorted = cards.slice().sort(function(a,b){
				if (v === 'date-desc') return new Date(b.dataset.date) - new Date(a.dataset.date);
				if (v === 'date-asc')  return new Date(a.dataset.date) - new Date(b.dataset.date);
				if (v === 'title-asc') return a.dataset.title.localeCompare(b.dataset.title);
				if (v === 'title-desc')return b.dataset.title.localeCompare(a.dataset.title);
				return 0;
			});
			sorted.forEach(function(card){ grid.appendChild(card); });
		});
	}
})();
</script>

<?php get_footer(); ?>
