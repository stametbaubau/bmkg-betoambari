<?php
/**
 * Single Post Template (Berita & Artikel)
 *
 * White theme BMKG. Tanpa gradient / inline style / Font Awesome.
 *
 * @package BMKG_Betoambari
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="bmkg-main bmkg-page bmkg-single">
	<div class="bmkg-container bmkg-single__container">

		<?php
		while ( have_posts() ) :
			the_post();

			/* Tentukan kategori/tipe: dukung slug kategori DAN meta lama post_type_select */
			$cats      = get_the_category();
			$cat_slug  = ! empty( $cats ) ? $cats[0]->slug : '';
			$meta_type = get_post_meta( get_the_ID(), 'post_type_select', true );
			$is_buletin = ( 'buletin' === $cat_slug || 'buletin' === $meta_type );
			$parent_url = $is_buletin ? home_url( '/buletin' ) : home_url( '/berita' );
			$parent_txt = $is_buletin ? 'Buletin' : 'Berita';
			?>

			<nav class="bmkg-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Beranda</a>
				<span aria-hidden="true">&rsaquo;</span>
				<a href="<?php echo esc_url( $parent_url ); ?>"><?php echo esc_html( $parent_txt ); ?></a>
				<span aria-hidden="true">&rsaquo;</span>
				<span class="bmkg-breadcrumb__current"><?php echo esc_html( wp_trim_words( get_the_title(), 8, '…' ) ); ?></span>
			</nav>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'bmkg-article' ); ?>>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="bmkg-article__hero">
						<?php the_post_thumbnail( 'full', array( 'alt' => get_the_title() ) ); ?>
					</figure>
				<?php endif; ?>

				<div class="bmkg-article__inner">

					<header class="bmkg-article__head">
						<div class="bmkg-article__meta">
							<span>
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
								<?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
							</span>
							<span>
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M20 21v-2a4 4 0 00-4-4H8a4 4 0 00-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
								<?php the_author(); ?>
							</span>
							<span>
								<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M22 19a2 2 0 01-2 2H4a2 2 0 01-2-2V5a2 2 0 012-2h5l2 3h9a2 2 0 012 2z"></path></svg>
								<?php the_category( ', ' ); ?>
							</span>
							<?php if ( function_exists( 'bmkg_set_post_views' ) && function_exists( 'bmkg_get_post_views' ) ) : ?>
								<?php bmkg_set_post_views( get_the_ID() ); ?>
								<span>
									<svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
									<?php echo esc_html( bmkg_get_post_views( get_the_ID() ) ); ?> kali dilihat
								</span>
							<?php endif; ?>
						</div>

						<h1 class="bmkg-article__title"><?php the_title(); ?></h1>
					</header>

					<div class="bmkg-article__content bmkg-prose">
						<?php the_content(); ?>
						<?php
						wp_link_pages( array(
							'before' => '<div class="bmkg-page-links">Halaman: ',
							'after'  => '</div>',
						) );
						?>
					</div>

					<?php /* ---- Bagikan ---- */ ?>
					<div class="bmkg-share">
						<h2 class="bmkg-share__title">Bagikan Artikel Ini</h2>
						<div class="bmkg-share__links">
							<a class="bmkg-share__btn bmkg-share__btn--fb" target="_blank" rel="noopener" aria-label="Bagikan ke Facebook"
								href="https://www.facebook.com/sharer/sharer.php?u=<?php echo rawurlencode( get_permalink() ); ?>">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>
							</a>
							<a class="bmkg-share__btn bmkg-share__btn--x" target="_blank" rel="noopener" aria-label="Bagikan ke X"
								href="https://twitter.com/intent/tweet?url=<?php echo rawurlencode( get_permalink() ); ?>&text=<?php echo rawurlencode( get_the_title() ); ?>">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
							</a>
							<a class="bmkg-share__btn bmkg-share__btn--wa" target="_blank" rel="noopener" aria-label="Bagikan ke WhatsApp"
								href="https://wa.me/?text=<?php echo rawurlencode( get_the_title() . ' ' . get_permalink() ); ?>">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
							</a>
							<a class="bmkg-share__btn bmkg-share__btn--mail" aria-label="Bagikan via Email"
								href="mailto:?subject=<?php echo rawurlencode( get_the_title() ); ?>&body=<?php echo rawurlencode( get_permalink() ); ?>">
								<svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path><polyline points="22,6 12,13 2,6"></polyline></svg>
							</a>
						</div>
					</div>

					<?php /* ---- Navigasi antar-artikel ---- */ ?>
					<nav class="bmkg-post-nav" aria-label="Navigasi artikel">
						<div class="bmkg-post-nav__prev">
							<?php previous_post_link( '%link', '&larr; Sebelumnya' ); ?>
						</div>
						<div class="bmkg-post-nav__next">
							<?php next_post_link( '%link', 'Berikutnya &rarr;' ); ?>
						</div>
					</nav>

				</div>
			</article>

			<?php /* ---- Artikel terkait ---- */ ?>
			<?php
			$related = new WP_Query( array(
				'post_type'      => 'post',
				'posts_per_page' => 3,
				'post__not_in'   => array( get_the_ID() ),
				'orderby'        => 'rand',
				'no_found_rows'  => true,
			) );
			if ( $related->have_posts() ) :
				?>
				<section class="bmkg-related" aria-label="Artikel terkait">
					<h2 class="bmkg-section__title bmkg-section__title--center">Artikel Terkait</h2>
					<div class="bmkg-card-grid">
						<?php
						while ( $related->have_posts() ) :
							$related->the_post();
							?>
							<article class="bmkg-card">
								<a href="<?php the_permalink(); ?>" class="bmkg-card__media">
									<?php if ( has_post_thumbnail() ) : ?>
										<?php the_post_thumbnail( 'medium_large', array( 'alt' => get_the_title() ) ); ?>
									<?php else : ?>
										<span class="bmkg-media-empty" aria-hidden="true"><svg width="40" height="40" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4 22h16a2 2 0 002-2V4a2 2 0 00-2-2H8a2 2 0 00-2 2v16a2 2 0 01-2 2zm0 0a2 2 0 01-2-2v-9c0-1.1.9-2 2-2h2"></path><path d="M18 14h-8"></path><path d="M15 18h-5"></path><path d="M10 6h8v4h-8V6z"></path></svg></span>
									<?php endif; ?>
								</a>
								<div class="bmkg-card__body">
									<div class="bmkg-card__meta">
										<time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>">
											<svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true"><rect x="3" y="4" width="18" height="18" rx="2" ry="2"></rect><line x1="16" y1="2" x2="16" y2="6"></line><line x1="8" y1="2" x2="8" y2="6"></line><line x1="3" y1="10" x2="21" y2="10"></line></svg>
											<?php echo esc_html( get_the_date( 'j F Y' ) ); ?>
										</time>
									</div>
									<h3 class="bmkg-card__title"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
									<p class="bmkg-card__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 16 ) ); ?></p>
								</div>
							</article>
							<?php
						endwhile;
						wp_reset_postdata();
						?>
					</div>
				</section>
			<?php endif; ?>

		<?php endwhile; ?>

	</div>
</main>

<?php get_footer(); ?>
