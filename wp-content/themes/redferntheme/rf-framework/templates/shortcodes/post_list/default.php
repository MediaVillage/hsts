<?php if ( $the_query->have_posts() ): ?>
    <div class="RFPostList">
        <?php if ( $title ): ?><h1><?php echo $title; ?></h1><?php endif; ?>
        <?php while( $the_query->have_posts() ): $the_query->the_post(); ?>
            <div class="RFPostList__item">
                <div class="RFPostList__inner">

                    <?php if ( has_post_thumbnail() ): ?>
                        <div class="RFPostList__image">
                            <?php the_post_thumbnail( $image_size, array( 'class' => 'img-responsive') ); ?>
                        </div>
                    <?php endif; ?>

                    <div class="RFPostList__title">
                        <h3><?php the_title(); ?></h3>
                    </div>

                    <div class="RFPostList__meta">
                        <h4><?php the_time( 'd.m.y' ); ?></h4>
                    </div>

                    <div class="RFPostList__excerpt">
                        <?php the_excerpt(); ?>
                    </div>

                    <div class="RFPostList__read-more">
                        <a class="btn btn-primary" href="<?php the_permalink(); ?>">Read More</a>
                    </div>
                </div>
            </div>
        <?php endwhile; ?>
    </div>
<?php endif; ?>