<?php
/**
 * Tekil blog yazısı — 2 kolon (içerik + sticky sidebar), Kısa Özet, İçerik Tablosu,
 * yazar kutusu (EEAT), Son Yazılar / Kategoriler. utkuerdemozer.com dili.
 *
 * @package dr-alper-uslu
 */

get_header();

while ( have_posts() ) :
	the_post();
	$cats     = get_the_category();
	$cat_html = '';
	foreach ( $cats as $c ) {
		$cat_html .= '<a href="' . esc_url( get_category_link( $c ) ) . '" class="hover:text-brand-700">' . esc_html( $c->name ) . '</a>';
	}
	$author_id   = get_the_author_meta( 'ID' );
	$author_bio  = get_the_author_meta( 'description' );
	if ( ! $author_bio ) {
		$author_bio = __( 'Op. Dr. Alper Burak Uslu, plastik, rekonstrüktif ve estetik cerrahi uzmanıdır. Yüz, vücut ve göğüs estetiği ile ameliyatsız uygulamalarda doğal ve kişiye özel sonuçları önceler. ISAPS, ASPS, EBOPRAS ve TPRECD üyesidir.', 'dr-alper-uslu' );
	}
	?>
	<section class="mesh-teal"><div class="container py-10 md:py-14">
		<nav class="flex flex-wrap items-center gap-2 text-sm text-ink-500" aria-label="breadcrumb">
			<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="hover:text-brand-700"><?php esc_html_e( 'Ana Sayfa', 'dr-alper-uslu' ); ?></a><span aria-hidden="true">/</span>
			<a href="<?php echo esc_url( get_permalink( get_option( 'page_for_posts' ) ) ?: home_url( '/blog/' ) ); ?>" class="hover:text-brand-700"><?php esc_html_e( 'Blog', 'dr-alper-uslu' ); ?></a><span aria-hidden="true">/</span>
			<span class="text-ink-700 line-clamp-1"><?php the_title(); ?></span>
		</nav>
		<?php if ( $cat_html ) : ?><div class="mt-5 text-[0.7rem] uppercase tracking-wider text-brand-600 font-semibold"><?php echo $cat_html; // phpcs:ignore ?></div><?php endif; ?>
		<h1 class="font-display font-black text-ink-900 text-[clamp(1.9rem,4vw,3rem)] leading-[1.08] tracking-[-0.02em] mt-3 max-w-3xl"><?php the_title(); ?></h1>
		<div class="flex items-center gap-3 text-sm text-ink-500 mt-4">
			<span><?php echo esc_html( get_the_date() ); ?></span><span aria-hidden="true">·</span>
			<span><?php echo esc_html( dau_reading_time( get_the_content() ) ); ?></span><span aria-hidden="true">·</span>
			<span><?php the_author(); ?></span>
		</div>
	</div></section>

	<section class="section bg-white"><div class="container grid lg:grid-cols-3 gap-10 lg:gap-14 items-start">
		<!-- İçerik -->
		<div class="lg:col-span-2 min-w-0">
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="rounded-xl2 overflow-hidden mb-8 ring-1 ring-line"><?php the_post_thumbnail( 'dau-hero', array( 'class' => 'w-full object-cover' ) ); ?></div>
			<?php endif; ?>

			<?php if ( has_excerpt() ) : ?>
			<div class="rounded-2xl bg-brand-50/60 ring-1 ring-brand-100 p-5 mb-8">
				<div class="text-[0.7rem] uppercase tracking-wider text-brand-700 font-semibold mb-2"><?php esc_html_e( 'Kısa Özet', 'dr-alper-uslu' ); ?></div>
				<p class="text-ink-700 leading-relaxed m-0"><?php echo esc_html( get_the_excerpt() ); ?></p>
			</div>
			<?php endif; ?>

			<!-- İçerik Tablosu (H2'lerden JS ile) -->
			<div data-toc x-data="{ open:true }" class="rounded-2xl ring-1 ring-line p-5 mb-8 hidden">
				<button @click="open=!open" class="w-full flex items-center justify-between font-display font-semibold text-ink-900">
					<span><?php esc_html_e( 'İçindekiler', 'dr-alper-uslu' ); ?></span>
					<span class="transition-transform text-brand-600" :class="!open && 'rotate-180'"><?php echo dau_icon( 'chevron' ); // phpcs:ignore ?></span>
				</button>
				<nav data-toc-list x-show="open" x-transition class="mt-3 space-y-1.5 text-sm"></nav>
			</div>

			<article data-article class="prose-clinic max-w-none"><?php the_content(); wp_link_pages(); ?></article>
			<?php
			// FAQPage schema — post meta'dan (content'e gömülü <script> kses'e takıldığı için tema çıktısıyla).
			$dau_faq = get_post_meta( get_the_ID(), '_dau_faq', true );
			if ( $dau_faq ) {
				$faq = json_decode( $dau_faq, true );
				if ( is_array( $faq ) && $faq ) {
					$items = array();
					foreach ( $faq as $q => $a ) {
						$items[] = array( '@type' => 'Question', 'name' => $q, 'acceptedAnswer' => array( '@type' => 'Answer', 'text' => $a ) );
					}
					echo '<script type="application/ld+json">' . wp_json_encode( array( '@context' => 'https://schema.org', '@type' => 'FAQPage', 'mainEntity' => $items ), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES ) . '</script>';
				}
			}
			?>

			<!-- Paylaş -->
			<div class="flex items-center gap-3 mt-10 pt-6 border-t border-line">
				<span class="text-sm text-ink-500 font-medium"><?php esc_html_e( 'Paylaş', 'dr-alper-uslu' ); ?></span>
				<?php $purl = rawurlencode( get_permalink() ); $ptitle = rawurlencode( get_the_title() ); ?>
				<a href="https://api.whatsapp.com/send?text=<?php echo $ptitle . '%20' . $purl; ?>" target="_blank" rel="noopener" aria-label="WhatsApp" class="w-9 h-9 grid place-content-center rounded-full ring-1 ring-line text-ink-700 hover:bg-brand-50 hover:text-brand-700 transition"><?php echo dau_icon( 'wa' ); // phpcs:ignore ?></a>
				<a href="https://www.facebook.com/sharer/sharer.php?u=<?php echo $purl; ?>" target="_blank" rel="noopener" aria-label="Facebook" class="w-9 h-9 grid place-content-center rounded-full ring-1 ring-line text-ink-700 hover:bg-brand-50 hover:text-brand-700 transition"><?php echo dau_icon( 'fb' ); // phpcs:ignore ?></a>
				<a href="https://twitter.com/intent/tweet?url=<?php echo $purl; ?>&text=<?php echo $ptitle; ?>" target="_blank" rel="noopener" aria-label="X" class="w-9 h-9 grid place-content-center rounded-full ring-1 ring-line text-ink-700 hover:bg-brand-50 hover:text-brand-700 transition text-xs font-bold">X</a>
			</div>

			<!-- Yazar kutusu (EEAT) -->
			<div class="mt-8 rounded-2xl bg-cream-50 ring-1 ring-line p-6 flex flex-col sm:flex-row gap-5">
				<div class="shrink-0">
					<?php if ( has_custom_logo() && false ) : endif; ?>
					<div class="w-20 h-20 rounded-full bg-brand-600 text-white grid place-content-center font-display text-2xl font-bold ring-2 ring-white shadow">A</div>
				</div>
				<div class="min-w-0">
					<div class="text-[0.7rem] uppercase tracking-wider text-brand-600 font-semibold"><?php esc_html_e( 'Yazar', 'dr-alper-uslu' ); ?></div>
					<h2 class="font-display text-h3 font-bold text-ink-900 mt-0.5"><?php the_author(); ?></h2>
					<p class="text-sm text-ink-500"><?php esc_html_e( 'Plastik, Rekonstrüktif ve Estetik Cerrahi · M.D, FEBOPRAS', 'dr-alper-uslu' ); ?></p>
					<p class="text-ink-700 mt-3 text-[0.95rem] leading-relaxed"><?php echo esc_html( $author_bio ); ?></p>
					<a href="<?php echo esc_url( home_url( '/hakkimda/' ) ); ?>" class="inline-flex items-center gap-1 text-brand-700 font-semibold text-sm mt-3 hover:text-brand-800"><?php esc_html_e( 'Hakkımda', 'dr-alper-uslu' ); ?><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></a>
				</div>
			</div>

			<!-- Önceki / Sonraki -->
			<?php
			$prev = get_previous_post();
			$next = get_next_post();
			if ( $prev || $next ) :
				?>
			<div class="grid sm:grid-cols-2 gap-4 mt-8">
				<?php if ( $prev ) : ?><a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="card card-hover p-4"><div class="text-xs text-ink-500"><?php esc_html_e( 'Önceki yazı', 'dr-alper-uslu' ); ?></div><div class="font-medium text-ink-900 mt-1 line-clamp-2"><?php echo esc_html( get_the_title( $prev ) ); ?></div></a><?php else : ?><span></span><?php endif; ?>
				<?php if ( $next ) : ?><a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="card card-hover p-4 sm:text-right"><div class="text-xs text-ink-500"><?php esc_html_e( 'Sonraki yazı', 'dr-alper-uslu' ); ?></div><div class="font-medium text-ink-900 mt-1 line-clamp-2"><?php echo esc_html( get_the_title( $next ) ); ?></div></a><?php endif; ?>
			</div>
			<?php endif; ?>
		</div>

		<!-- Sidebar -->
		<aside class="lg:col-span-1">
			<div class="sticky top-28 space-y-6">
				<div class="card p-6 bg-ink-900 text-white">
					<h2 class="font-display text-h3 font-semibold mb-1"><?php esc_html_e( 'Randevu Talebi', 'dr-alper-uslu' ); ?></h2>
					<p class="text-white/70 text-sm mb-4"><?php esc_html_e( 'Sorularınızı yanıtlayalım, size uygun planı birlikte belirleyelim.', 'dr-alper-uslu' ); ?></p>
					<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 rounded-full bg-[#0E7A3A] text-white py-3 font-semibold text-sm mb-2"><?php echo dau_icon( 'wa' ); // phpcs:ignore ?>WhatsApp</a>
					<a href="<?php echo esc_url( dau_tel() ); ?>" class="flex items-center justify-center gap-2 rounded-full bg-white/10 ring-1 ring-white/20 text-white py-3 font-semibold text-sm"><?php echo dau_icon( 'phone' ); // phpcs:ignore ?><?php echo esc_html( dau_opt( 'telefon' ) ); ?></a>
				</div>

				<?php
				$recent = get_posts( array( 'post_type' => 'post', 'posts_per_page' => 5, 'post__not_in' => array( get_the_ID() ), 'post_status' => 'publish' ) );
				if ( $recent ) :
					?>
				<div class="card p-6">
					<h2 class="font-display text-h3 font-semibold text-ink-900 mb-4"><?php esc_html_e( 'Son Yazılar', 'dr-alper-uslu' ); ?></h2>
					<ul class="space-y-3">
						<?php foreach ( $recent as $rp ) : ?>
						<li><a href="<?php echo esc_url( get_permalink( $rp ) ); ?>" class="flex gap-3 group/li">
							<span class="text-brand-600 mt-1 shrink-0"><?php echo dau_icon( 'arrow' ); // phpcs:ignore ?></span>
							<span class="text-sm text-ink-700 group-hover/li:text-brand-700 leading-snug line-clamp-2"><?php echo esc_html( get_the_title( $rp ) ); ?></span>
						</a></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>

				<?php
				$blog_cats = get_categories( array( 'hide_empty' => true ) );
				if ( count( $blog_cats ) > 0 ) :
					?>
				<div class="card p-6">
					<h2 class="font-display text-h3 font-semibold text-ink-900 mb-4"><?php esc_html_e( 'Kategoriler', 'dr-alper-uslu' ); ?></h2>
					<ul class="space-y-2">
						<?php foreach ( $blog_cats as $bc ) : ?>
						<li><a href="<?php echo esc_url( get_category_link( $bc ) ); ?>" class="flex items-center justify-between text-sm text-ink-700 hover:text-brand-700">
							<span><?php echo esc_html( $bc->name ); ?></span>
							<span class="text-xs text-ink-500 bg-cream-50 rounded-full px-2 py-0.5"><?php echo (int) $bc->count; ?></span>
						</a></li>
						<?php endforeach; ?>
					</ul>
				</div>
				<?php endif; ?>
			</div>
		</aside>
	</div></section>

	<script>
	// İçindekiler: makaledeki H2'lerden otomatik oluştur
	(function(){
		var art = document.querySelector('[data-article]');
		var box = document.querySelector('[data-toc]');
		var list = document.querySelector('[data-toc-list]');
		if(!art || !box || !list) return;
		var hs = art.querySelectorAll('h2');
		if(hs.length < 3) return;
		hs.forEach(function(h,i){
			var id = 'b'+i; h.id = id;
			var a = document.createElement('a');
			a.href = '#'+id; a.textContent = h.textContent;
			a.className = 'block text-ink-600 hover:text-brand-700 border-l-2 border-line hover:border-brand-500 pl-3 py-0.5';
			list.appendChild(a);
		});
		box.classList.remove('hidden');
	})();
	</script>
	<?php
endwhile;

get_footer();
