<?php

function remove_plugin_updates($value) {
  unset($value->response['advanced-custom-fields-pro/acf.php']);
  return $value;
}
add_filter('site_transient_update_plugins', 'remove_plugin_updates');

add_action( 'generate_rewrite_rules', 'roots_add_rewrites' );
function roots_add_rewrites($content) {
  $theme_name = next( explode( '/themes/', get_stylesheet_directory() ) );
  global $wp_rewrite;
  $roots_new_non_wp_rules = array(
    'css/(.*)' => 'wp-content/themes/' . $theme_name . '/css/$1',
    'fonts/(.*)' => 'wp-content/themes/' . $theme_name . '/fonts/$1',
    'files/(.*)' => 'wp-content/themes/' . $theme_name . '/files/$1',
    'images/(.*)' => 'wp-content/themes/' . $theme_name . '/images/$1',
    'js/(.*)' => 'wp-content/themes/' . $theme_name . '/js/$1',
  );
  $wp_rewrite->non_wp_rules += $roots_new_non_wp_rules;
}

add_filter( 'upload_mimes', 'svg_upload_allow' );

function svg_upload_allow( $mimes ) {
	
	$mimes['svg']  = 'image/svg+xml';

	return $mimes;
}	

add_filter( 'wp_check_filetype_and_ext', 'fix_svg_mime_type', 10, 5 );

function fix_svg_mime_type( $data, $file, $filename, $mimes, $real_mime = '' ){

	// WP 5.1 +
	if( version_compare( $GLOBALS['wp_version'], '5.1.0', '>=' ) )
		$dosvg = in_array( $real_mime, [ 'image/svg', 'image/svg+xml' ] );
	else
		$dosvg = ( '.svg' === strtolower( substr($filename, -4) ) );

	if( $dosvg ){

		if( current_user_can('manage_options') ){

			$data['ext']  = 'svg';
			$data['type'] = 'image/svg+xml';
		}
		else {
			$data['ext'] = $type_and_ext['type'] = false;
		}

	}

	return $data;
}

add_filter( 'wp_prepare_attachment_for_js', 'show_svg_in_media_library' );

function show_svg_in_media_library( $response ) {

	if ( $response['mime'] === 'image/svg+xml' ) {

		// С выводом названия файла
		$response['image'] = [
			'src' => $response['url'],
		];
	}

	return $response;
}	

add_action( 'parse_query', function ( $query ) {
    if( is_date() || is_category() || is_tag() || is_author() ) {
        wp_redirect( home_url() );
        exit;
    }
});

/*

add_action( 'after_setup_theme', 'woocommerce_support' );
function woocommerce_support() {
	
   add_theme_support( 'woocommerce' );
}

/**/

function nav_menus($menu_id) {
	
	$menu = wp_get_nav_menu_items($menu_id, ['output_key'  => 'menu_order']);
	
	if($menu)
	foreach ($menu as $m) {
		
		if ( $m->menu_item_parent == 0) {
			
			$flag = 0;
			
			foreach ($menu as $sm) {
				
				if($sm->menu_item_parent == $m->ID){
					
					$flag = 1;
					break;
				}
			}
			
			if($flag == 0){
				
				?>
				<li class="menu__item">
					<a class="menu__link" href="<?php echo $m->url ?>" data-goto2="#category-1" style="--color-link: #d3cfff">
						<figure class="menu__icon-box">
							<img class="menu__icon" src="<?php echo get_field('ikonka_menyu', $m) ?>" alt="image">
						</figure>
						<?php echo $m->title ?>
					</a>
				</li>				
				
				<?php					
			} else {
				
			}
		}
		?><?php
	}

}

function show_home_news($id){
	
	$out = '';
	
	$args = array(
		'post_type' => 'post',
		'status' => 'publish',
		'cat' => $id,
		'posts_per_page' => 3,
		'offset' => 0,
		'order' => 'ASC',
		'orderby' => 'date'
	);

	$post_query = new WP_Query($args);
	$post_query = $post_query->get_posts();
	
	
	foreach ($post_query as $pq) {
		
		$out .= '   
			<article class="article-card">
				<a class="article-card__link" href="'.get_the_permalink($pq).'"> <span class="seo">Читать статью</span> </a>
				<h3 class="article-card__title">'.$pq->post_title.'</h3>
				<figure class="article-card__img-box">
					<img class="article-card__img" src="'.get_the_post_thumbnail_url( $pq, 'full' ).'" alt="image">
				</figure>
				<span class="article-card__icon" aria-hidden="true"></span>
			</article>';
	}
	
	$out .= '';
	
	echo $out;
}

function show_btn($id){
	
	$out = '';
	
	$args = array(
		'post_type' => 'post',
		'status' => 'publish',
		'cat' => $id,
		'posts_per_page' => 4,
		'offset' => 0,
		'order' => 'ASC',
		'orderby' => 'date'
	);

	$post_query = new WP_Query($args);
	$post_query = $post_query->get_posts();
	
	if(count($post_query)>3) {
		
		$out .= '
			<button class="category__btn btn btn_accent btn_large btn_ajax" data-id="'.$id.'" type="button">
				Посмотреть еще статьи
			</button>';
	}
	
	$out .= '';
	
	echo $out;
}

function footer_code() {
    
	?>
	<!-- HTML структура модального окна -->
	<div class="rutube-modal-overlay" id="rutubeModalOverlay">
		<div class="rutube-modal-content">
			<button class="rutube-close-button" id="rutubeCloseButton">×</button>
			<div class="rutube-video-container" id="rutubeVideoContainer">
				<!-- Сюда будет вставлен iframe -->
			</div>
		</div>
	</div>

	<!-- Скрипт для работы модального окна -->
	<script>
	// Класс для управления модальным окном Rutube
class RutubeModal {
	constructor() {
		this.modalOverlay = document.getElementById('rutubeModalOverlay');
		this.videoContainer = document.getElementById('rutubeVideoContainer');
		this.closeButton = document.getElementById('rutubeCloseButton');

		this.init();
	}

	init() {
		// Находим ссылки и добавляем обработчики
		this.addEventListenersToLinks();

		// Закрытие по крестику
		this.closeButton.addEventListener('click', () => {
			this.closeModal();
		});

		// Закрытие по клику на оверлей
		this.modalOverlay.addEventListener('click', (e) => {
			if (e.target === this.modalOverlay) {
				this.closeModal();
			}
		});

		// Закрытие по ESC
		document.addEventListener('keydown', (e) => {
			if (e.key === 'Escape') {
				this.closeModal();
			}
		});
	}

	addEventListenersToLinks() {
		// Находим все ссылки на Rutube и Kinescope
		const links = document.querySelectorAll(
			'a[href*="rutube.ru/video/"], ' +
			'a[href*="rutube.ru/play/embed/"], ' +
			'a[href*="kinescope.io/embed/"]'
		);

		links.forEach(link => {
			// Добавляем класс для стилизации
			link.classList.add('rutube-modal-link');

			// Добавляем обработчик клика
			link.addEventListener('click', (e) => {
				e.preventDefault();

				if (link.getAttribute('data-vertical')=='1') {

					document.getElementById('rutubeVideoContainer').classList.add('vmodal');
				}

				// Прямо передаем ссылку в iframe
				const videoUrl = link.href;

				if (videoUrl) {
					this.openModal(videoUrl);
				} else {
					// Если вдруг что-то не так — открываем обычным способом
					window.open(link.href, '_blank');
				}
			});
		});
	}

	openModal(videoUrl) {
		// Создаем iframe
		const iframe = document.createElement('iframe');
		iframe.src = videoUrl;
		iframe.allow = 'accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture';
		iframe.allowFullscreen = true;

		// Очищаем контейнер и вставляем iframe
		this.videoContainer.innerHTML = '';
		this.videoContainer.appendChild(iframe);

		// Показываем модальное окно
		this.modalOverlay.style.display = 'flex';
		setTimeout(() => {
			this.modalOverlay.classList.add('active');
		}, 10);

		// Отключаем прокрутку страницы
		document.body.style.overflow = 'hidden';
	}

	closeModal() {
		this.modalOverlay.classList.remove('active');

		setTimeout(() => {
			this.modalOverlay.style.display = 'none';
			this.videoContainer.innerHTML = ''; // Удаляем видео
			this.videoContainer.classList.remove('vmodal');

			// Включаем прокрутку обратно
			document.body.style.overflow = '';
		}, 300);
	}
}

// Инициализация
document.addEventListener('DOMContentLoaded', () => {
	new RutubeModal();
});

	</script>

<div class="msd-overlay" id="msdModal">
  <div class="msd-dialog">
    <p><strong>Информация, содержащаяся на сайте, предназначена для специалистов здравоохранения.</strong></p>
    <p>В соответствии с действующим законодательством доступ к такой информации может быть предоставлен только медицинским и фармацевтическим работникам.</p>
    <p>Переходя на сайт, Вы подтверждаете что являетесь медицинским или фармацевтическим работником и берёте на себя ответственность за последствия, вызванные возможным нарушением указанного ограничения.</p>
    <div class="msd-buttons">
      <button class="msd-btn msd-btn-primary" id="msdAccept">Перейти на сайт</button>
      <button class="msd-btn msd-btn-secondary" id="msdDecline">Отказаться</button>
    </div>
  </div>
</div>

<script>
(function() {
  const STORAGE_KEY = 'msd_consent_expiry';
  const DAYS = 7;
  
  const overlay = document.getElementById('msdModal');
  const acceptBtn = document.getElementById('msdAccept');
  const declineBtn = document.getElementById('msdDecline');
  
  // Элемент для блюра: main или body
  const blurTarget = document.querySelector('main') || document.body;
  
  function hasValidConsent() {
    const expiry = localStorage.getItem(STORAGE_KEY);
    return expiry && Date.now() < parseInt(expiry);
  }
  
  function setConsent() {
    const expires = Date.now() + DAYS * 24 * 60 * 60 * 1000;
    localStorage.setItem(STORAGE_KEY, expires);
  }
  
  function addBlur() {
    blurTarget.classList.add('msd-blur');
  }
  
  function removeBlur() {
    blurTarget.classList.remove('msd-blur');
  }
  
  function showModal() {
    overlay.classList.add('msd-visible');
  }
  
  function hideModal() {
    overlay.classList.remove('msd-visible');
  }
  
  function acceptHandler() {
    setConsent();
    removeBlur();
    hideModal();
  }
  
  function declineHandler() {
    hideModal();
    // blur остаётся
	setTimeout(() => {
		showModal();
	}, 100); 		
  }
  
  if (!hasValidConsent()) {
    addBlur();
    showModal();
    acceptBtn.onclick = acceptHandler;
    declineBtn.onclick = declineHandler;
  } else {
    removeBlur();
  }
})();
</script>
	<?php
}
add_action( 'wp_footer', 'footer_code' );

function get_tumb(){
	
	return 'background-image: url('.get_the_post_thumbnail_url(get_the_ID(), "full").')';
}

function set_full(){
	
	if(get_the_post_thumbnail_url(get_the_ID(), "full")=='')
		return '<style>.article__banner-info-title, .article__banner-info-desc{max-width: 856px!important;}.article__container{display:none;}</style>';
}

function block1_slider(){
	
	$n = 1;
    $primer = get_field('block_1_slides', get_option('page_on_front'));
	if($primer){
		
		?>
		
		<?php
		
		foreach ($primer as $fi) {
		  
		  ?>
			<div class="embla__slide <?php echo ($n==1?'active':'') ?>"> 
				<div class="hero__item <?php echo ($fi['style']=='dark'?'hero__item--dark':'') ?>"> 
					<?php if($fi['tag']!=''){ ?>
					<div class="hero__item-tag"><?php echo $fi['tag'] ?></div>  
					<?php } ?>
					<div class="hero__item-title">
						<?php echo $fi['title'] ?>
					</div>                                         
					<div class="hero__item-text"><?php echo $fi['text'] ?></div>        
					<?php if($fi['button_text']){ ?>					
					<a href="<?php echo $fi['button_link'] ?>" title="Читать" class="btn-2 hero__item-btn"><?php echo $fi['button_text'] ?></a> 
					<?php } ?>
				</div>                                     
			</div>
		  <?php
		  $n++;
		}
	}
}

function block1_slider_css(){
	
    $vid = get_field('video_ikonka', get_option('page_on_front'));
    $primer = get_field('block_1_slides', get_option('page_on_front'));
	if($primer){	
	
		$out = '';
		foreach ($primer as $key => $fi) {
			
			$out .= 'body .hero .embla__dot:nth-child('.($key+1).'){background-image: url('.$fi['ikonka'].');}';
		}
		$out .= 'body .hero .embla__dot:nth-child('.(count($primer)+1).'){background-image: url('.$vid.');}';
		
		$out2 = '';
		$out3 = '';
		foreach ($primer as $key => $fi) {
			
			$out2 .= 'body .hero .embla__slide:nth-child('.($key+1).') .hero__item {background-image: url('.$fi['slajder_fon'].');}';
			$out3 .= 'body .hero .embla__slide:nth-child('.($key+1).') .hero__item {background-image: url('.$fi['slajder_fon_mobilnyj'].');}';
		}
		
		if($out2!='' || $out3!='') $out .= $out2.'@media (max-width: 768px) {'.$out3.'}';
		
		if($out!='') echo '<style>'.$out.'</style>';

    }	
}

function block2_icons(){

    $primer = get_field('block_2_items', get_option('page_on_front'));
	if($primer){
		
		?>
		
		<?php
		
		foreach ($primer as $fi) {
			
			$str = str_replace(array("\r", "\n"), '', strip_tags($fi['text']));
		  
		  ?>
			<div class="research__desc-item"> 
				<div class="research__desc-item-icon"> 
					<img src="<?php echo $fi['ikonka'] ?>" alt="<?php echo $str ?>" title="<?php echo $str ?>"> 
				</div>                                     
				<div class="research__desc-item-title"><?php echo $fi['text'] ?>
				</div>                                     
			</div>   			
		  <?php
		}
	}
}

function block3_left(){

    $primer = get_field('block_3_left_cards', get_option('page_on_front'));
	if($primer){
		
		?>
		
		<?php
		
		foreach ($primer as $fi) {
		  
		  ?>
			<div class="thesis__card"> 
				<div class="thesis__card-title"> 
					<span><?php echo $fi['title'] ?></span> 
					<img src="<?php echo $fi['icon'] ?>" alt=""> 
				</div>                                     
				<div class="thesis__card-text"> 
					<?php echo $fi['text'] ?> 
				</div>                                     
			</div> 
		  <?php
		}
	}
}

function block3_right(){

	$n = 1;
    $primer = get_field('block_3_right_cards', get_option('page_on_front'));
	if($primer){
		
		?>
		
		<?php
		
		foreach ($primer as $key => $fi) {
		  
		  if($key==0)  {
		  ?>
			<div class="thesis__card"> 
				<div class="thesis__card-title"> 
					<span><?php echo $fi['title'] ?></span> 
					<img src="<?php echo $fi['icon'] ?>" alt=""> 
				</div>                                     
				<div class="thesis__card-text"> 
					<?php echo $fi['text'] ?>
				</div>                                     
			</div>  			
			<?php
		  } else if($key==1) {
			?>
                               
			<div class="thesis__card"> 
				<div class="thesis__card-title"> 
					<span><?php echo $fi['title'] ?></span> 
					<img src="<?php echo $fi['icon'] ?>" alt=""> 
				</div>                                     
				<div class="thesis__card-text"> 
					<?php echo $fi['text'] ?>
				</div>                                     
			</div>                                     
                                    			
		  <?php
		  } else {
			  
			  ?>
				<div class="thesis__card"> 
					<div class="thesis__card-title"> 
						<span><?php echo $fi['title'] ?></span> 
					<img src="<?php echo $fi['icon'] ?>" alt=""> 
					</div>                                         
					<div class="thesis__card-text"> 
						<?php echo $fi['text'] ?>
					</div>                                         
				</div>  			  
			  <?php
		  }
		  $n++;
		}
		  ?>                                  
			<div class="thesis__card thesis__card--accent"> 
				<div class="thesis__card-title"> 
					<span><?php echo get_field('block_3_accent_card', get_option('page_on_front'))['title'] ?></span> 
					<img src="<?php echo get_field('block_3_accent_card', get_option('page_on_front'))['icon'] ?>" alt=""> 
				</div>                                         
				<div class="thesis__card-text"><?php echo get_field('block_3_accent_card', get_option('page_on_front'))['text'] ?></div>                                         
				<a href="<?php echo get_field('block_3_accent_card', get_option('page_on_front'))['button_link'] ?>" class="btn-2 btn-2--purple thesis__card-btn" target="_blank">
					<?php echo get_field('block_3_accent_card', get_option('page_on_front'))['button_text'] ?>
				</a> 
			</div>
		  <?php		
	}
}

function block3_icons(){

    $primer = get_field('block_3_items_2', get_option('page_on_front'));
	if($primer){
		
		?>
		
		<?php
		
		foreach ($primer as $fi) {
			
			$str = str_replace(array("\r", "\n"), '', strip_tags($fi['text']));
		  
		  ?>
			<div class="afabazol__info-item"> 
				<div class="afabazol__info-item-icon"> 
					<img src="<?php echo $fi['ikonka'] ?>" alt=""> 
				</div>                                             
				<div class="afabazol__info-item-title">
					<?php echo $fi['text'] ?>
				</div>                                             
			</div>  
		  <?php
		}
	}
}

function block4_cards(){

    $primer = get_field('block_4_cards', get_option('page_on_front'));
	if($primer){
		
		?>
		
		<?php
		
		foreach ($primer as $fi) {
			
			/*$out = '<div class="mind__card-tag">'.get_field('teg', $fi).'</div> 
					<div class="mind__card-img"> 
						<div class="mind__card-bg" style="background-color: '.get_field('czvet_fona', $fi).'; background-image: url('.get_the_post_thumbnail_url($fi, "full").');"></div>                                             
					</div>
					<div class="mind__card-info"> 
						<div class="mind__card-info-wrapper"> 
							<div class="mind__card-info-title">'.get_the_title($fi).'</div>				
							<div class="mind__card-info-desc">'.get_the_excerpt($fi).'></div>                                                 
							<div class="btn-2 btn-2--purple mind__card-info-btn">Подробнее</div>
						</div>                                             
					</div> ';
			/**/		
			/*$out = 	'<a href="'.get_field('ssylka', $fi).'" title="'.get_the_title($fi).'" class="mind__card vp-a" style="background-color: '.get_field('czvet_fona', $fi).';>
						<div class="mind__card-tag">'.get_field('teg', $fi).'</div>
						<div class="mind__card-img"> <img src="'.get_the_post_thumbnail_url($fi, "full").'" alt=""> </div>
						<div class="mind__card-info">
							<div class="mind__card-info-wrapper">
								<div class="mind__card-info-title">'.get_the_title($fi).'</div>
								<div class="mind__card-info-desc">'.get_the_excerpt($fi).'</div>
								<div class="btn-2 btn-2--purple mind__card-info-btn">Подробнее</div>
							</div>
						</div>
					</a>';	

			/**/		
			$out =	'<a href="'.get_field('ssylka', $fi).'" title="'.get_the_title($fi).'" data-vertical="'.get_field('vertikalnoe_video', $fi).'" class="mind__card vp-a" style="background-color: '.get_field('czvet_fona', $fi).';">
						<div class="mind__card-tag">'.get_field('teg', $fi).'</div>
						<div class="mind__card-img"> <img src="'.get_the_post_thumbnail_url($fi, "full").'" alt> </div>
						<div class="mind__card-info">
							<div class="mind__card-info-wrapper">
								<div class="mind__card-info-title">'.get_the_title($fi).'</div>
								<div class="mind__card-info-desc">'.get_the_excerpt($fi).'</div>
								<div class="btn-2 btn-2--purple mind__card-info-btn">Подробнее</div>
							</div>
						</div>
					</a>';					

		  ?>
			<div class="embla__slide"> 
				<?php //echo str_replace('Подробнее', $out, str_replace('vp-a', 'vp-a mind__card', do_shortcode('[video_popup url="'.get_field('ssylka', $fi).'" text="Подробнее" wrap="1"]'))) ?>
				<?php echo $out ?>
			</div> 			
		  <?php
		}
	}
}

function show_home_posts1(){

    $args = array(
        'post_type' => 'post',
        'cat' => array(30),
        'status' => 'publish',
        //'posts_per_page' => get_option( 'posts_per_page' ),
        'posts_per_page' => -1,
        'order' => 'DESC',
        'orderby' => 'date'
    );

    $post_query = new WP_Query($args);
    $post_query = $post_query->get_posts();


    foreach ($post_query as $fi) {

        ?>
        <?php
        $out =	'<a href="'.get_field('ssylka', $fi).'" title="'.get_the_title($fi).'" data-vertical="'.get_field('vertikalnoe_video', $fi).'" class="mind__card vp-a" style="background-color: '.get_field('czvet_fona', $fi).';">
            <div class="mind__card-tag">'.get_field('teg', $fi).'</div>
            <div class="mind__card-img"> <img src="'.get_the_post_thumbnail_url($fi, "full").'" alt> </div>
            <div class="mind__card-info">
                <div class="mind__card-info-wrapper">
                    <div class="mind__card-info-title">'.get_the_title($fi).'</div>
                    <div class="mind__card-info-desc">'.get_the_excerpt($fi).'</div>
                    <div class="btn-2 btn-2--purple mind__card-info-btn">Подробнее</div>
                </div>
            </div>
        </a>';

        ?>
        <div class="embla__slide">
            <?php //echo str_replace('Подробнее', $out, str_replace('vp-a', 'vp-a mind__card', do_shortcode('[video_popup url="'.get_field('ssylka', $fi).'" text="Подробнее" wrap="1"]'))) ?>
            <?php echo $out ?>
        </div>

        <?php
    }
}

function block6_cards(){

    $primer = get_field('block_6_cards', get_option('page_on_front'));
	if($primer){
		
		?>
		
		<?php
		
		foreach ($primer as $fi) {

		  ?>
			<a href="<?php echo get_field('ssylka', $fi) ?>" title="" class="materials__card">
				<div class="materials__card-img" style="background-color: <?php echo get_field('czvet_fona', $fi) ?>"> 
					<div class="materials__card-tag"><?php echo get_field('teg', $fi) ?></div>                                     
					<img src="<?php echo get_the_post_thumbnail_url($fi, "full") ?>" alt="" title=""> 
				</div> 
				<div class="materials__card-title"><?php echo get_the_title($fi) ?>
					<span><?php echo apply_filters('the_content', get_post($fi)->post_content) ?></span>
				</div>
			</a> 
		  <?php
		}
	}
}

function block7_cards(){
	
    $primer = get_field('block_7_card_1', get_option('page_on_front'));
    $primer2 = get_field('block_7_card_2', get_option('page_on_front'));
	if($primer){
		
		?>
		<a href="<?php echo $primer['link'] ?>" class="portal__card" target="_blank">
			<div class="portal__card-title"><?php echo $primer['title'] ?>
			</div> 
			<div class="portal__card-desc"><?php echo $primer['text'] ?></div> 
			<div class="portal__card-img"> 
				<img src="<?php echo $primer['image'] ?>" alt="" title="">
			</div>
		</a> 
		<a href="<?php echo $primer2['link'] ?>" class="portal__card portal__card--light" target="_blank">
			<div class="portal__card-title"><?php echo $primer2['title'] ?></div> 
			<div class="portal__card-desc"><?php echo $primer2['text'] ?></div>
		</a> 	
		<style>
		@media (max-width: 768px) {
		body .portal__card-img {
			background-image: url(<?php echo $primer['izobrazhenie_telefony'] ?>)!important;
		}
		}		
		</style>
	    <?php
	}	
}

function show_home_posts_btn(){
	
	$args = array(
		'post_type' => 'post',
		'cat' => array(29),
		'status' => 'publish',
		'posts_per_page' => get_option( 'posts_per_page' )+1,
		'order' => 'ASC',
		'orderby' => 'date'
	);

	$post_query = new WP_Query($args);
	$post_query = $post_query->get_posts();
		
	if(count($post_query)>get_option( 'posts_per_page' )) return '<div class="materials__more"> <div class="btn-1 btn-1--purple ajax_posts">Загрузить еще</div></div>';
}

function show_home_posts(){
	
	$args = array(
		'post_type' => 'post',
		'cat' => array(29),
		'status' => 'publish',
		'posts_per_page' => get_option( 'posts_per_page' ),
		'order' => 'ASC',
		'orderby' => 'date'
	);

	$post_query = new WP_Query($args);
	$post_query = $post_query->get_posts();
	
	
	foreach ($post_query as $pq) {
		
		?>
		<a href="<?php echo get_field('ssylka', $pq->ID) ?>" title="" class="materials__card" target="_blank">
			<div class="materials__card-img" style="background-color: <?php echo get_field('czvet_fona', $pq->ID) ?>"> 
				<div class="materials__card-tag"><?php echo get_field('teg', $pq->ID) ?></div>                                     
				<img src="<?php echo get_the_post_thumbnail_url($pq->ID, "full") ?>" alt="" title=""> 
			</div> 
			<div class="materials__card-title"><?php echo get_the_title($pq->ID) ?>
				<span><?php echo apply_filters('the_content', get_post($pq->ID)->post_content) ?></span>
			</div>
		</a> 
		<?php
	}
}

if ( !function_exists( 'my_theme_js' ) ):
    function my_theme_js() {
		
		wp_enqueue_script( 'custom-script', get_stylesheet_directory_uri() . '/js/custom.js', array('jquery'), '', true);
		wp_localize_script( 'custom-script', 'myajax',
			array(
					'url' => admin_url('admin-ajax.php')
				)
			);
    }
endif;
add_action( 'wp_enqueue_scripts', 'my_theme_js', 10 );

add_action( 'wp_ajax_getposts', 'getposts' );
add_action( 'wp_ajax_nopriv_getposts', 'getposts' );

function getposts(){
	
	$sheet = $_POST['sheet'];
	
	$offset = $sheet * get_option( 'posts_per_page' );

	if(isset($_POST['sheet'])){
		
		$args = array(
			'post_type' => 'post',
			'status' => 'publish',
			'offset' => $offset,
			'order' => 'ASC',
			'orderby' => 'date',
			'cat' => 29,
			'posts_per_page' => get_option( 'posts_per_page' )			
		);
		
		$post_page = new WP_Query($args);
		
		if($_POST['pagebtn']>0){
			
			$args['offset'] = 0;
			$args['posts_per_page'] = get_option( 'posts_per_page' )*$_POST['pagebtn'];
		}
		
		//echo $debug = '<pre>'.print_r($args, true).'</pre>';

		$post_query = new WP_Query($args);

		foreach ($post_query->get_posts() as $pq) {
			
			?>
			<a href="<?php echo get_field('ssylka', $pq->ID) ?>" title="" class="materials__card">
				<div class="materials__card-img" style="background-color: <?php echo get_field('czvet_fona', $pq->ID) ?>"> 
					<div class="materials__card-tag"><?php echo get_field('teg', $pq->ID) ?></div>                                     
					<img src="<?php echo get_the_post_thumbnail_url($pq->ID, "full") ?>" alt="" title=""> 
				</div> 
				<div class="materials__card-title"><?php echo get_the_title($pq->ID) ?>
					<span><?php echo apply_filters('the_content', get_post($pq->ID)->post_content) ?></span>
				</div>
			</a> 
			<?php
		}
	}
	die;
} 

function show_header(){

?>
<header class="header"> 
    <div class="header__content"> 
        <div class="header__menu"> 
            <a href="<?php echo '/' ?>" title="Исследование AXEL" class="header__logo" style="background-image:url('<?php echo get_field('shapka_logo', get_option('page_on_front')) ?>');"></a> 
            <nav class="header__nav" id="menu"> 
                <ul class="header__nav-list"> 
                    <?php
                        PG_Smart_Walker_Nav_Menu::$options['template'] = '<li class="header__nav-list-item hover-link {CLASSES}" id="{ID}"> 
                                                            <a data-close-menu {ATTRS}>{TITLE}</a> 
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
<?php
}

function show_footer(){

?>
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
<?php

}

add_action('template_redirect', 'disable_single_posts');
function disable_single_posts() {
    if (is_single() && 'post' == get_post_type()) {
        global $wp_query;
        $wp_query->set_404();
        status_header(404);
    }
}