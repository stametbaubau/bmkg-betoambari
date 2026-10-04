<?php
/**
 * Footer Template — BMKG Betoambari
 *
 * Mengikuti gaya footer BMKG Pusat & Kalteng:
 * - Background dark navy (#0f2447)
 * - 4 kolom: Brand / Kontak / Tautan / Sosial Media
 * - Teks putih semi-transparan
 * - Garis pembatas + copyright bar di bawah
 *
 * @package BMKG_Betoambari
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
?>

<footer class="bmkg-footer" role="contentinfo">
	<div class="bmkg-container">

		<div class="bmkg-footer__grid">

			<?php /* ---- Kolom 1: Brand + Deskripsi ---- */ ?>
			<div class="bmkg-footer__col">
				<div class="bmkg-footer__brand-row">
					<img src="https://www.bmkg.go.id/asset/img/logo/logo-bmkg.png" alt="Logo BMKG" width="56" height="56" loading="lazy">
					<div>
						<strong>BMKG</strong>
					</div>
				</div>

				<p class="bmkg-footer__station-name">Stasiun Meteorologi Betoambari</p>
				<p class="bmkg-footer__desc">
					BMKG adalah lembaga yang melaksanakan tugas pemerintahan di bidang meteorologi, klimatologi, dan geofisika.
					Stasiun Meteorologi Betoambari merupakan unit pelaksana tugas BMKG di Kota Baubau, Sulawesi Tenggara.
				</p>
			</div>

			<?php /* ---- Kolom 2: Kontak Kami ---- */ ?>
			<div class="bmkg-footer__col">
				<h4>Kontak Kami</h4>
				<ul class="bmkg-footer__contact">
					<li>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M21 10c0 7-9 13-9 13s-9-6-9-13a9 9 0 0118 0z"></path>
							<circle cx="12" cy="10" r="3"></circle>
						</svg>
						<span>Bandara Betoambari, Jl. Mayjen Sutoyo Km.12, Tebing, Pamak, Kota Baubau, Sulawesi Tenggara
							<a href="https://maps.app.goo.gl/baubau" target="_blank" rel="noopener">Lihat di Google Maps ↗</a>
						</span>
					</li>
					<li>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 013.07 4.18 2 2 0 015.11 2h3a2 2 0 012 1.72c.127.96.362 1.9.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0122 16.92z"></path>
						</svg>
						<span>Pusat Layanan BMKG 196</span>
					</li>
					<li>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M22 16.92v3a2 2 0 01-2.18 2 19.79 19.79 0 01-8.63-3.07 19.5 19.5 0 01-6-6A19.79 19.79 0 013.07 4.18 2 2 0 015.11 2h3a2 2 0 012 1.72c.127.96.362 1.9.7 2.81a2 2 0 01-.45 2.11L8.09 9.91a16 16 0 006 6l1.27-1.27a2 2 0 012.11-.45c.907.338 1.85.573 2.81.7A2 2 0 0122 16.92z"></path>
						</svg>
						<span><a href="tel:+628127018643">+62 xxx-xxxx-xxxx</a></span>
					</li>
					<li>
						<svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
							<path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"></path>
							<polyline points="22,6 12,13 2,6"></polyline>
						</svg>
						<span>
							<a href="mailto:stamet.baubau@bmkg.go.id">stamet.baubau@bmkg.go.id</a>
						</span>
					</li>
				</ul>
			</div>

			<?php /* ---- Kolom 3: Tautan Terkait ---- */ ?>
			<div class="bmkg-footer__col">
				<h4>Tautan Terkait</h4>
				<ul class="bmkg-footer__links">
					<li><a href="https://www.bmkg.go.id" target="_blank" rel="noopener">BMKG Pusat ↗</a></li>
					<li><a href="https://stmkg.ac.id" target="_blank" rel="noopener">STMKG ↗</a></li>
					<li><a href="https://dataonline.bmkg.go.id" target="_blank" rel="noopener">Data Online BMKG ↗</a></li>
					<li><a href="https://signature.bmkg.go.id" target="_blank" rel="noopener">Signature BMKG ↗</a></li>
					<li><a href="https://jdih.bmkg.go.id" target="_blank" rel="noopener">Dokumentasi Hukum (JDIH) ↗</a></li>
					<li><a href="https://inatews.bmkg.go.id" target="_blank" rel="noopener">Indonesia Tsunami Early Warning ↗</a></li>
					<li><a href="https://ppid.bmkg.go.id" target="_blank" rel="noopener">PPID BMKG ↗</a></li>
				</ul>
			</div>

			<?php /* ---- Kolom 4: Media Sosial + Aplikasi ---- */ ?>
			<div class="bmkg-footer__col">
				<h4>Aplikasi Info BMKG</h4>
				<div class="bmkg-footer__apps">
					<a href="https://play.google.com/store/apps/details?id=com.bmkg.cuacaapp" target="_blank" rel="noopener" class="bmkg-footer__app-badge">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M3.609 1.814L13.792 12 3.61 22.186a.996.996 0 01-.61-.92V2.734a1 1 0 01.609-.92zm10.89 10.893l2.302 2.302-10.937 6.333 8.635-8.635zm3.199-3.199l2.302 2.302-2.302 2.302-2.604-2.302 2.604-2.302zM5.864 3.455L16.8 9.788l-2.302 2.302-8.634-8.635z"></path></svg>
						Google Play
					</a>
					<a href="https://apps.apple.com/id/app/bmkg/id1114372615" target="_blank" rel="noopener" class="bmkg-footer__app-badge">
						<svg width="20" height="20" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.71 19.5c-.83 1.24-1.71 2.45-3.05 2.47-1.34.03-1.77-.79-3.29-.79-1.53 0-2 .77-3.27.82-1.31.05-2.3-1.32-3.14-2.53C4.25 17 2.94 12.45 4.7 9.39c.87-1.52 2.43-2.48 4.12-2.51 1.28-.02 2.5.87 3.29.87.78 0 2.26-1.07 3.8-.91.65.03 2.47.26 3.64 1.98-.09.06-2.17 1.28-2.15 3.81.03 3.02 2.65 4.03 2.68 4.04-.03.07-.42 1.44-1.38 2.83M13 3.5c.73-.83 1.94-1.46 2.94-1.5.13 1.17-.34 2.35-1.04 3.19-.69.85-1.83 1.51-2.95 1.42-.15-1.15.41-2.35 1.05-3.11z"></path></svg>
						App Store
					</a>
				</div>

				<h4>Media Sosial</h4>
				<div class="bmkg-footer__social">
					<a href="https://www.youtube.com/@BMKGBetoambari" target="_blank" rel="noopener" aria-label="YouTube BMKG Betoambari">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"></path></svg>
					</a>
					<a href="https://web.facebook.com/bmkgbetoambari" target="_blank" rel="noopener" aria-label="Facebook BMKG Betoambari">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"></path></svg>
					</a>
					<a href="https://twitter.com/bmkgbetoambari" target="_blank" rel="noopener" aria-label="Twitter/X BMKG Betoambari">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18.244 2.25h3.308l-7.227 8.26 8.502 11.24H16.17l-5.214-6.817L4.99 21.75H1.68l7.73-8.835L1.254 2.25H8.08l4.713 6.231zm-1.161 17.52h1.833L7.084 4.126H5.117z"></path></svg>
					</a>
					<a href="https://instagram.com/bmkgbetoambari" target="_blank" rel="noopener" aria-label="Instagram BMKG Betoambari">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zM12 0C8.741 0 8.333.014 7.053.072 2.695.272.273 2.69.073 7.052.014 8.333 0 8.741 0 12c0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98C8.333 23.986 8.741 24 12 24c3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98C15.668.014 15.259 0 12 0zm0 5.838a6.162 6.162 0 100 12.324 6.162 6.162 0 000-12.324zM12 16a4 4 0 110-8 4 4 0 010 8zm6.406-11.845a1.44 1.44 0 100 2.881 1.44 1.44 0 000-2.881z"></path></svg>
					</a>
					<a href="https://wa.me/62xxxxxxxxxx" target="_blank" rel="noopener" aria-label="WhatsApp BMKG Betoambari">
						<svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"></path></svg>
					</a>
				</div>
			</div>

		</div>

		<?php /* ---- Bottom bar: Copyright + kebijakan ---- */ ?>
		<div class="bmkg-footer__bottom">
			<p>&copy; <?php echo esc_html( date( 'Y' ) ); ?> Stasiun Meteorologi Betoambari &mdash; Badan Meteorologi, Klimatologi, dan Geofisika</p>
			<nav class="bmkg-footer__bottom-links" aria-label="Tautan kebijakan">
				<a href="<?php echo esc_url( home_url( '/peta-situs' ) ); ?>">Peta Situs</a>
				<a href="<?php echo esc_url( home_url( '/kebijakan-privasi' ) ); ?>">Kebijakan Privasi</a>
			</nav>
		</div>

	</div>
</footer>

<?php /* ---- Lightbox untuk gambar .bmkg-enlargeable ---- */ ?>
<div class="bmkg-lightbox" id="bmkg-lightbox" role="dialog" aria-modal="true" aria-label="Tampilan gambar diperbesar">
	<button type="button" class="bmkg-lightbox__close" aria-label="Tutup">&times;</button>
	<img src="" alt="" id="bmkg-lightbox-img">
</div>

<script>
(function(){
	'use strict';
	var box   = document.getElementById('bmkg-lightbox');
	var img   = document.getElementById('bmkg-lightbox-img');
	var close = box ? box.querySelector('.bmkg-lightbox__close') : null;
	if (!box || !img) return;

	document.addEventListener('click', function(e){
		var target = e.target.closest('.bmkg-enlargeable');
		if (target && target.tagName === 'IMG'){
			img.src = target.src;
			img.alt = target.alt || '';
			box.classList.add('is-open');
			document.body.style.overflow = 'hidden';
		}
	});

	function hide(){
		box.classList.remove('is-open');
		document.body.style.overflow = '';
		img.src = '';
	}

	if (close) close.addEventListener('click', hide);
	box.addEventListener('click', function(e){ if (e.target === box) hide(); });
	document.addEventListener('keydown', function(e){ if (e.key === 'Escape') hide(); });
})();
</script>

<?php wp_footer(); ?>
</body>
</html>
