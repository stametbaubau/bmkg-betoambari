<?php
/**
 * Fallback Template (index.php)
 *
 * Dipakai bila tidak ada template yang lebih spesifik. Menampilkan daftar
 * post terbaru dalam kartu putih BMKG.
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
			<h1 class="bmkg-page__title"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></h1>
			<p class="bmkg-page__subtitle">Berita, artikel, dan informasi terbaru dari BMKG Betoambari</p>
		</header>

		<?php if ( have_posts() ) : ?>

			<div class="bmkg-card-grid">
				<?php
				while ( have_posts() ) :
					the_post();
					$cats = get_the_category();
					$cat_name = ! empty( $cats ) ? $cats[0]->name : 'Berita';
					?>
					<article class="bmkg-card">
						<a href="<?php the_permalink(); ?>" class="bmkg-card__media">
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
				<svg width="56" height="56" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><circle cx="11" cy="11" r="8"></circle><line x1="21" y1="21" x2="16.65" y2="16.65"></line></svg>
				<h2>Tidak Ada Konten</h2>
				<p>Belum ada konten yang dapat ditampilkan.</p>
			</div>

		<?php endif; ?>

	</div>
</main>

<?php get_footer(); ?>
