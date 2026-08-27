<?php
/**
 * Alt bilgi + mobil sabit çubuk.
 *
 * @package dr-alper-uslu
 */
?>
</main>

<footer class="bg-ink-900 text-white pt-16 pb-24 sm:pb-10">
	<div class="container grid gap-12 lg:grid-cols-4">
		<div class="lg:col-span-1">
			<span class="font-display text-lg font-bold text-white"><?php bloginfo( 'name' ); ?></span>
			<p class="text-white/60 text-sm mt-4 max-w-xs"><?php echo esc_html( dau_legal_note() ); ?></p>
			<div class="flex gap-2 mt-5">
				<?php
				$socials = array( 'facebook' => 'fb', 'instagram' => 'ig', 'youtube' => 'yt' );
				foreach ( array( 'facebook', 'instagram', 'youtube' ) as $s ) {
					$url = dau_opt( $s );
					if ( $url ) {
						printf( '<a href="%s" target="_blank" rel="noopener" aria-label="%s" class="w-10 h-10 grid place-content-center rounded-full bg-white/10 hover:bg-brand-500 transition">%s</a>',
							esc_url( $url ), esc_attr( $s ), dau_icon( 'star' ) ); // ikon basit; gerçek sosyal ikonlar için genişletilebilir
					}
				}
				?>
			</div>
		</div>
		<div>
			<h2 class="font-display text-lg mb-4 text-white"><?php esc_html_e( 'Uzmanlıklar', 'dr-alper-uslu' ); ?></h2>
			<ul class="space-y-2 text-sm">
				<?php
				$terms = get_terms( array( 'taxonomy' => 'uzmanlik-kategori', 'hide_empty' => false ) );
				if ( ! is_wp_error( $terms ) ) {
					foreach ( $terms as $term ) {
						printf( '<li><a href="%s" class="text-white/70 hover:text-white transition">%s</a></li>', esc_url( get_term_link( $term ) ), esc_html( $term->name ) );
					}
				}
				?>
				<li><a href="<?php echo esc_url( get_post_type_archive_link( 'uzmanlik' ) ); ?>" class="text-brand-300 hover:text-white font-medium"><?php esc_html_e( 'Tüm Uzmanlıklar', 'dr-alper-uslu' ); ?> →</a></li>
			</ul>
		</div>
		<div>
			<h2 class="font-display text-lg mb-4 text-white"><?php esc_html_e( 'Adres', 'dr-alper-uslu' ); ?></h2>
			<a href="<?php echo esc_url( dau_opt( 'maps' ) ); ?>" target="_blank" rel="noopener" class="flex gap-2 text-white/70 text-sm hover:text-white"><?php echo dau_icon( 'map' ); // phpcs:ignore ?><span><?php echo esc_html( dau_opt( 'adres' ) ); ?></span></a>
			<a href="<?php echo esc_url( dau_tel() ); ?>" class="flex gap-2 text-white/70 text-sm hover:text-white mt-3"><?php echo dau_icon( 'phone' ); // phpcs:ignore ?><?php echo esc_html( dau_opt( 'telefon' ) ); ?></a>
			<div class="flex gap-2 text-white/70 text-sm mt-3"><?php echo dau_icon( 'clock' ); // phpcs:ignore ?><span><?php echo esc_html( dau_opt( 'calisma_saati' ) ); ?></span></div>
		</div>
		<div>
			<h2 class="font-display text-lg mb-4 text-white"><?php esc_html_e( 'Hızlı İletişim', 'dr-alper-uslu' ); ?></h2>
			<?php
			// Contact Form 7 kısa kodu ACF Options'ta tanımlıysa göster; yoksa WhatsApp CTA.
			$cf7 = dau_opt( 'footer_form_shortcode', '' );
			if ( $cf7 && function_exists( 'do_shortcode' ) ) {
				echo do_shortcode( wp_kses_post( $cf7 ) );
			} else {
				printf( '<a href="%s" target="_blank" rel="noopener" class="btn-gold w-full">%s %s</a>',
					esc_url( dau_wa_link() ), dau_icon( 'wa' ), esc_html__( 'WhatsApp ile yazın', 'dr-alper-uslu' ) );
			}
			?>
		</div>
	</div>
	<div class="container mt-12 pt-6 border-t border-white/10 flex flex-col sm:flex-row items-center justify-between gap-3 text-white/50 text-xs">
		<span>&copy; <?php echo esc_html( date_i18n( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php esc_html_e( 'Tüm hakları saklıdır.', 'dr-alper-uslu' ); ?></span>
		<?php
		if ( has_nav_menu( 'footer' ) ) {
			wp_nav_menu( array( 'theme_location' => 'footer', 'container' => false, 'menu_class' => 'flex gap-4', 'depth' => 1, 'fallback_cb' => false ) );
		}
		?>
	</div>
</footer>

<!-- Mobil sabit çubuk -->
<div class="fixed bottom-0 inset-x-0 z-40 grid grid-cols-2 gap-px bg-line sm:hidden">
	<a href="<?php echo esc_url( dau_tel() ); ?>" class="flex items-center justify-center gap-2 bg-brand-600 text-white py-3.5 font-semibold text-sm"><?php echo dau_icon( 'phone' ); // phpcs:ignore ?><?php esc_html_e( 'Hemen Ara', 'dr-alper-uslu' ); ?></a>
	<a href="<?php echo esc_url( dau_wa_link() ); ?>" target="_blank" rel="noopener" class="flex items-center justify-center gap-2 bg-[#0E7A3A] text-white py-3.5 font-semibold text-sm"><?php echo dau_icon( 'wa' ); // phpcs:ignore ?>WhatsApp</a>
</div>

<?php wp_footer(); ?>
</body>
</html>
