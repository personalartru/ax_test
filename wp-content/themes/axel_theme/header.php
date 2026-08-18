<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo( 'charset' ); ?>">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
        <?php wp_head(); ?>
		
		<!-- Yandex.Metrika counter -->
		<script type="text/javascript">
			(function(m,e,t,r,i,k,a){
				m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
				m[i].l=1*new Date();
				for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
				k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)
			})(window, document,'script','https://mc.yandex.ru/metrika/tag.js?id=104196818', 'ym');

			ym(104196818, 'init', {ssr:true, webvisor:true, clickmap:true, ecommerce:"dataLayer", accurateTrackBounce:true, trackLinks:true});
		</script>
		<noscript><div><img src="https://mc.yandex.ru/watch/104196818" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
		<!-- /Yandex.Metrika counter -->

    </head>
    <body class="<?php echo implode(' ', get_body_class()); ?>">
        <?php if( function_exists( 'wp_body_open' ) ) wp_body_open(); ?> 
        <main>
<header class="header"> 
    <div class="header__content"> 
        <div class="header__menu"> 
            <a href="<?php echo '/' ?>" title="Исследование AXEL" class="header__logo" style="background-image:url('<?php echo get_field('shapka_logo', get_option('page_on_front')) ?>');"></a> 
            <nav class="header__nav" id="menu"> 
                <ul class="header__nav-list"> 
                    <?php
                        PG_Smart_Walker_Nav_Menu::$options['template'] = '<li class="header__nav-list-item hover-link {CLASSES}" id="{ID}"> 
                                                            <a {ATTRS}>{TITLE}</a> 
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
            <div class="header__burger" data-toggle-menu> 
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/burger.2afc2c92.svg" alt="Открыть меню" title="Открыть меню"> 
                <img src="<?php echo esc_url( get_template_directory_uri() ); ?>/images/close.6bde4ef8.svg" alt="Закрыть меню" title="Закрыть меню"> 
            </div>                         
        </div>                     
        <a href="#form" class="btn-1 header__btn"><?php echo get_field('shapka_knopka', get_option('page_on_front')).'<div class="header__btn-info"> Зарегистрируйтесь и получите эксклюзивный материал о тревожных расстройствах с Левиным Олегом Семеновичем </div>' ?></a> 
    </div>                 
</header>  		