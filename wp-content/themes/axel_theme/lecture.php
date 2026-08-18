<?php
/*
Template Name: Lecture
*/
?>

<?php get_header(); ?>

<?php block1_slider_css() ?>

<style>
    .btn-1.header__btn{
        display: none;
    }
</style>
<section class="mind" id="mind" style="margin: 60rem 0">
    <div class="mind__container"> 
        <h2 class="mind__title">Эксклюзивные лекции</h2>
        <div class="embla"> 
            <div class="embla__viewport"> 
                <div class="embla__container">
                    <?php show_home_posts1() ?>
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


<section class="hero" style="margin: 0 0 60rem 0">
    <div class="container container--large hero__container">
        <div class="embla hero__content" data-loop="false" data-class="true" data-fade="true" data-custom="true">
            <div class="embla__viewport">
                <div class="embla__container">
                    <?php block1_slider() ?>
                    <div class="embla__slide">
                        <div class="hero__item">
                            <video src="<?php echo @get_field('block_1_video_slide', get_option('page_on_front'))['video_file'] ?>"  muted autoplay loop class="hero__item-video"></video>
                            <video src="<?php echo @get_field('block_1_video_slide', get_option('page_on_front'))['video_file2'] ?>"  muted autoplay loop playsinline defaultMuted class="hero__item-video hero__item-video--mob"></video>
                        </div>
                    </div>
                </div>
            </div>
            <div class="embla__controls">
                <div class="embla__dots"></div>
            </div>
        </div>
    </div>
<!--    <div class="hero__btn">-->
<!--        <a href="#form" title="Доступ к Лекции Левина О.С." class="hero__btn-link">Доступ к Лекции Левина О.С.</a>-->
<!--    </div>-->
</section>

<section class="portal section-1"> 
    <div class="container container--large portal__container"> 
        <div class="portal__content">
            <?php block7_cards() ?>
        </div>                     
    </div>                 
</section>             

<?php get_footer(); ?>