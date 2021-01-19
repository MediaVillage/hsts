<div class="RFCarousel__container">
    <?php if ( $title ): ?><h2><?php echo $title; ?></h2><?php endif; ?>
    <div class="RFCarousel owl-carousel <?php echo $extra_class; ?>" <?php echo $data_atts; ?>>
        <?php if ( $the_query->have_posts() ): ?>
            <?php while( $the_query->have_posts() ): $the_query->the_post(); ?>
                <div class="RFCarousel__item">
                    <div class="RFCarousel__item__inner">

                        <?php if ( has_post_thumbnail() ): ?>
                        <div class="RFCarousel__item__thumbnail">
                            <a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( $image_size ); ?></a>
                        </div>
                        <?php endif; ?>
   

                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <p>There are currently no items.</p>
        <?php endif; ?>
    </div>
</div>