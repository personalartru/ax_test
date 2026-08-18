
<footer class="footer"> 
    <div class="container"> 
        <div class="footer__content"> 
            <div class="footer__content-top"> 
                <div class="footer__logo"> 
                    <a href="#!" title="Отисифарм"> <img src="<?php echo get_field('podval_logo', get_option('page_on_front')) ?>" alt="Отисифарм" title="Отисифарм"> </a> 
                    <p><?php echo get_field('copyright', get_option('page_on_front')) ?></p> 
                </div>                             
                <nav class="footer__nav"> 
                    <div class="footer__nav-title">Навигация</div>                                 
                    <ul class="footer__nav-list"> 
                        <?php
                            PG_Smart_Walker_Nav_Menu::$options['template'] = '<li class="footer__nav-list-item {CLASSES}" id="{ID}"> 
                                                                    <a class="hover-link" {ATTRS}>{TITLE}</a> 
                                                                </li>';
                            wp_nav_menu( array(
                                'menu' => 'Главное меню',
                                'container' => '',
                                'depth' => '1',
                                'items_wrap' => '%3$s',
                                'walker' => new PG_Smart_Walker_Nav_Menu()
                        ) ); ?>                                                                                                                
                    </ul>                                 
                </nav>                             
                <nav class="footer__nav"> 
                    <div class="footer__nav-title">Контакты</div>                                 
                    <ul class="footer__nav-list"> 
                        <li class="footer__nav-list-item"> Телефон:&nbsp; 
                            <a href="<?php echo 'tel:'.str_replace(' ', '', get_field('podval_telefon', get_option('page_on_front'))) ?>" class="hover-link--accent" title="Телефон: +7 495  221 18 00"><?php echo get_field('podval_telefon', get_option('page_on_front')) ?></a> 
                        </li>                                     
                        <li class="footer__nav-list-item"> Факс:&nbsp; 
                            <a href="<?php echo 'tel:'.str_replace(' ', '', get_field('podval_faks', get_option('page_on_front'))) ?>" class="hover-link--accent" title="Телефон: +7 495 221 18 02"><?php echo get_field('podval_faks', get_option('page_on_front')) ?></a> 
                        </li>                                     
                        <li class="footer__nav-list-item"> 
                            <?php _e( 'E-mail:&nbsp;', 'axel_theme' ); ?> 
                            <a href="<?php echo 'mailto:'. get_field('podval_pochta', get_option('page_on_front')) ?>" class="hover-link--accent" title="E-mail: synapse@otcpharm.ru"><?php echo get_field('podval_pochta', get_option('page_on_front')) ?></a> 
                        </li>                                     
                    </ul>                                 
                </nav>                             
                <div class="footer__nav"> 
                    <div class="footer__nav-title">Адрес</div>                                 
                    <ul class="footer__nav-list"> 
                        <li class="footer__nav-list-item">
                            <b><?php echo get_field('podval_adres_1', get_option('page_on_front')) ?></b>
                        </li>                                     
                        <li class="footer__nav-list-item">
                            <?php echo get_field('podval_adres_2', get_option('page_on_front')) ?>
                        </li>                                     
                    </ul>                                 
                </div>                             
                <a href="<?php echo get_field('podval_ssylka_tg', get_option('page_on_front')) ?>" title="Синапс онлайн" class="btn-social footer__btn"> <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/telegram.312a7cb5.svg" alt="Синапс онлайн" title="Синапс онлайн"> <span>Синапс онлайн</span> </a> 
            </div>                         
            <img class="footer__content-middle" src="<?php echo get_field('podval_tekst_1', get_option('page_on_front')) ?>" alt="Информация для специалистов здравохранения" title="Информация для специалистов здравохранения"> 
            <div class="footer__content-bottom"> 
                <a href="<?php echo get_field('podval_ssylka', get_option('page_on_front')) ?>" title="Политика конфиденциальности" class="footer__content-bottom-policy hover-link--accent"><?php echo get_field('podval_politika', get_option('page_on_front')) ?></a> 
                <p class="footer__content-bottom-text"><?php echo get_field('podval_tekst_2', get_option('page_on_front')) ?></p> 
            </div>                         
        </div>                     
    </div>                 
</footer>    

<div class="cookie" id="cookieBanner"> 
	<div class="cookie__content"> 
		<div class="cookie__text"> 
			<?php echo get_field('kuki', get_option('page_on_front')) ?>
		</div> 
		<button class="btn-2 cookie__btn" id="acceptCookies">Принять</button> 
	</div> 
</div>

		<div class="hero__btn--sticky">
			<a href="#form" title="Доступ к Лекции Левина О.С." class="hero__btn-link"><?php echo get_field('shapka_knopka', get_option('page_on_front')) ?></a>
		</div>
        </main>         
        <?php wp_footer(); ?>
    </body>
</html>