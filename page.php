<?php
/**
 * Generic Page Template (page.php)
 *
 * Dipakai semua Halaman WordPress yang TIDAK memilih template khusus.
 * Tanpa file ini, halaman jatuh ke index.php dan tampil sebagai kartu.
 *
 * @package BMKG_Betoambari
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>

<main class="bmkg-main bmkg-page">
	<div class="bmkg-container bmkg-single__container">

		<?php
		while ( have_posts() ) :
			the_post();
			?>

			<nav class="bmkg-breadcrumb" aria-label="Breadcrumb">
				<a href="<?php echo esc_url( home_url( '/' ) ); ?>">Beranda</a>
				<span aria-hidden="true">&rsaquo;</span>
				<span class="bmkg-breadcrumb__current"><?php the_title(); ?></span>
			</nav>

			<article id="post-<?php the_ID(); ?>" <?php post_class( 'bmkg-article' ); ?>>

				<?php if ( has_post_thumbnail() ) : ?>
					<figure class="bmkg-article__hero">
						<?php the_post_thumbnail( 'full', array( 'alt' => get_the_title() ) ); ?>
					</figure>
				<?php endif; ?>

				<div class="bmkg-article__inner">
					<header class="bmkg-article__head">
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
				</div>

			</article>

		<?php endwhile; ?>

	</div>
</main>

<?php get_footer(); ?>
