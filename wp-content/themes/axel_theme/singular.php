<?php get_header(); ?>


<?php //show_header() ?> 
<section class="article"> 
    <div class="article__banner" style="<?php echo get_tumb() ?>"> 
        <div class="article__banner-info"> 
            <nav class="breadcrumbs"> 
                <div class="breadcrumbs__list"> 
                    <li class="breadcrumbs__item">
                        <a href="#!" title="Главная" src="<?php echo '/' ?>">Главная</a>
                    </li>                                 

                    <li class="breadcrumbs__item">
                        <?php the_title(); ?>
                    </li>                                 
                </div>                             
            </nav>                         

            <div class="article__banner-info-title">
                <?php echo get_field('czvetnoj_zagolovok') ?>
            </div>                         

            <div class="article__banner-info-desc">
                <?php echo get_the_excerpt(); ?>
            </div>                         
        </div>                     

        <?php echo set_full(); ?>
        <div class="article__banner-img"> 
            <img src="<?php echo get_the_post_thumbnail_url(get_the_ID(),'full'); ?>" alt> 
        </div>                     
    </div>                 

    <div class="article__text"> 
        <div class="cms">
            <?php the_content(); ?>
        </div>                     
    </div>                 

    <div class="article__container"> 
        <div class="btn-2 btn-2--purple" data-btn-up>Наверх</div>                     
    </div>                 
</section>             

<section class="lecture" id="form"> 
    <div class="container container--large lecture__container"> 
        <div class="lecture__content"> 
            <div class="lecture__content-desc"> 
                <div class="lecture__content-desc-title">
                    <?php echo get_field('block_8_title', get_option('page_on_front')) ?>
                </div>                             

                <div class="lecture__content-desc-text">
                    <?php echo get_field('block_8_text', get_option('page_on_front')) ?>
                </div>                             

                <div class="lecture__content-desc-img"> 
                    <img src="<?php echo get_field('block_8_image', get_option('page_on_front')) ?>" alt="" title=""> 
                </div>                             
            </div>                         

            <div class="lecture__content-form">
                <?php echo get_field('block_8_iframe', get_option('page_on_front')) ?>
            </div>                         
        </div>                     
    </div>                 
</section>             

<?php //show_footer() ?>             

<?php get_footer(); ?>