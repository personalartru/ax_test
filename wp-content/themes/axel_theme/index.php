<?php get_header(); ?>

           
<?php block1_slider_css() ?>
<section class="hero"> 
    <div class="container container--large hero__container"> 
        <div class="embla hero__content" data-loop="false" data-class="true" data-fade="true" data-custom="true"> 
            <div class="embla__viewport"> 
                <div class="embla__container"> 
                    <?php block1_slider() ?>                                  
                    <?php if(get_field('video_ikonka', get_option('page_on_front'))!=''){ ?>                                  
                    <div class="embla__slide"> 
                        <div class="hero__item"> 
                            <video src="<?php echo @get_field('block_1_video_slide', get_option('page_on_front'))['video_file'] ?>"  muted autoplay loop class="hero__item-video"></video>
							<video src="<?php echo @get_field('block_1_video_slide', get_option('page_on_front'))['video_file2'] ?>"  muted autoplay loop playsinline defaultMuted class="hero__item-video hero__item-video--mob"></video>	
                        </div>                                     
                    </div>    
                    <?php } ?>                             
                </div>                             
            </div>                         
            <div class="embla__controls"> 
                <div class="embla__dots"></div>                             
            </div>                         
        </div>                     
    </div>                 
    <div class="hero__btn"> 
        <a href="#form" title="Доступ к Лекции Левина О.С." class="hero__btn-link"><?php echo get_field('shapka_knopka', get_option('page_on_front')) ?></a> 
    </div>                 
</section>             
<section class="research" id="about"> 
    <div class="container container--large research__container"> 
        <div class="research__content"> 
            <div class="research__desc"> 
                <div class="research__desc-title">
                    <?php echo get_field('block_2_title', get_option('page_on_front')) ?>
                </div>                             
                <div class="research__desc-list">
                    <?php block2_icons() ?>
                </div>                             
            </div>                         
            <div class="research__img"> 
                <img src="<?php echo get_field('block_2_image', get_option('page_on_front')) ?>" alt="Исследование" title="Исследование"> 
            </div>                         
        </div>                     
    </div>                 
</section>             
<section class="thesis" id="key"> 
    <div class="thesis__container"> 
        <div class="thesis__content"> 
            <div class="thesis__title"> 
                <h2 class="thesis__title-text"><?php echo get_field('block_3_title', get_option('page_on_front')) ?></h2> 
                <?php if(get_field('block_3_image', get_option('page_on_front'))!=''){ ?>
                <img src="<?php echo get_field('block_3_image', get_option('page_on_front')) ?>" title="Афобазол" alt="Афобазол" class="thesis__title-img"> 
                <?php } ?>
            </div>                         
            <div class="thesis__elements"> 
                <div class="thesis__elements-col">
                    <?php block3_left() ?>
                </div>                             
                <div class="thesis__elements-col">
                    <?php block3_right() ?>
                </div>                             
            </div>                         
        </div>                     
        <div class="afabazol"> 
            <div class="afabazol__container container container--large"> 
                <div class="afabazol__content"> 
                    <div class="afabazol__img"> 
                        <img src="<?php echo get_field('block_3_image_2', get_option('page_on_front')) ?>" alt="Афобазол® Ретард" title="Афобазол® Ретард"> 
                        <img src="<?php echo get_field('block_3_image_2_mob', get_option('page_on_front')) ?>" alt> 
                    </div>                                 
                    <div class="afabazol__info"> 
                        <div class="afabazol__info-title">
                            <?php echo get_field('block_3_title_2', get_option('page_on_front')) ?>
                        </div>                                     
                        <div class="afabazol__info-subtitle">
                            <?php echo get_field('block_3_subtitle_2', get_option('page_on_front')) ?>
                        </div>                                     
                        <div class="afabazol__info-elements">
                            <?php block3_icons() ?>
                        </div>                                     
                        <div class="afabazol__text">
                            <?php echo get_field('tekst_vnizu', get_option('page_on_front')) ?>
                        </div>                                     
                    </div>                                 
                </div>                             
            </div>                         
        </div>                     
</section>     
<? /**/ ?>        
<section class="mind" id="mind"> 
    <div class="mind__container"> 
        <h2 class="mind__title"><?php echo get_field('block_4_title', get_option('page_on_front')) ?></h2> 
        <div class="embla"> 
            <div class="embla__viewport"> 
                <div class="embla__container">
                    <?php block4_cards() ?>
                </div>                             
            </div>                         
            <div class="embla__controls"> 
                <div class="embla__buttons"> 
                    <button class="embla__button embla__button--prev" type="button"></button>                                 
                    <button class="embla__button embla__button--next" type="button"></button>                                 
                </div>                             
            </div>                         
        </div>                     
    </div>                 
</section>     
<? /**/ ?>             
<section class="exclusive section-1"> 
    <div class="container container--large exclusive__container"> 
        <div class="exclusive__content"> 
            <div class="exclusive__desc"> 
                <div class="exclusive__tag">
                    <?php echo get_field('block_5_tag', get_option('page_on_front')) ?>
                </div>                             
                <div class="exclusive__title">
                    <?php echo get_field('block_5_title', get_option('page_on_front')) ?>
                </div>                             
                <div class="exclusive__text">
                    <?php echo get_field('block_5_text', get_option('page_on_front')) ?>
                </div>                             
                <a href="<?php echo get_field('block_5_button_link', get_option('page_on_front')) ?>" title="Читать" class="btn-1 btn-1--violet exclusive__btn"><?php echo get_field('block_5_button_text', get_option('page_on_front')) ?></a> 
            </div>                         
            <div class="exclusive__img"> 
                <img src="<?php echo get_field('block_5_image', get_option('page_on_front')) ?>" title="Маргарита Дробязко" alt="Маргарита Дробязко"> 
            </div>                         
        </div>                     
    </div>                 
</section>             
<section class="materials section-1" id="materials"> 
    <div class="container container--large materials__container"> 
        <div class="materials__content"> 
            <h2 class="materials__title"><?php echo get_field('block_6_title', get_option('page_on_front')) ?></h2> 
            <?php echo '<input type="hidden" name="sheet" value="1" />' ?>
            <div class="materials__content-list">
                <?php show_home_posts() ?>
            </div>                         
            <?php echo show_home_posts_btn() ?>                                                    
        </div>                     
    </div>                 
</section>             
<section class="portal section-1"> 
    <div class="container container--large portal__container"> 
        <div class="portal__content">
            <?php block7_cards() ?>
        </div>                     
    </div>                 
</section>             
<section class="lecture" id="form"> 
    <div class="container container--large lecture__container"> 
        <div class="lecture__content"> 
            <div class="lecture__content-desc"> 
                <div class="lecture__content-desc-title">
                    <?php echo get_field('block_8_title', get_option('page_on_front')) ?>
                </div>                             
                <!--<div class="lecture__content-desc-text">
                    <?php echo get_field('block_8_text', get_option('page_on_front')) ?>
                </div> -->                              
                <div class="lecture__content-desc-img"> 
                    <img src="<?php echo get_field('block_8_image', get_option('page_on_front')) ?>" alt=""> 
					<img src="<?php echo get_field('block_8_image_1', get_option('page_on_front')) ?>" alt="">
					<img src="<?php echo get_field('block_8_image_2', get_option('page_on_front')) ?>" alt="">
                </div>                             
            </div>                         
            <div class="lecture__content-form">
                <?php echo get_field('block_8_iframe', get_option('page_on_front')) ?>
            </div>                         
        </div>                     
    </div>                 
</section>             
                
<style>
	.lecture__content-desc-img{
		width: max-content;
		display: flex;
		border-radius: 0;
		gap: 10rem;
	}

	.lecture__content-desc-img img{
		width: 180rem;
		height: 180rem;
		object-fit: cover;
		border-radius: 50%;
	}
	
	.lecture__content-desc-title{
		font-size: 59rem;
	}
	
	@media (max-width: 768px) {

}

	@media (max-width: 768px) {
		.lecture__content-desc-img img{
			width: 112rem;
			height: 112rem;
			object-fit: cover;
			border-radius: 50%;
		}
		
		.lecture__content-desc-title {
			max-width: 296rem;
			font-size: 28rem;
    	}
	}
</style>

<?php get_footer(); ?>