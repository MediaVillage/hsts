<?php if ( $the_query->have_posts() ): ?>
    <div class="RFPostsGrid">

        <?php if ( $use_filter ): ?>
        <ul class="nav nav-tabs">
            <?php if ( $filter_all ): ?>
                <li><a href="#" data-filter="*">All</a></li>
            <?php endif; ?>
            <?php foreach($filters as $filter): ?>
                <li><a href="#" data-filter=".<?php echo $filter->slug; ?>"><?php echo $filter->name; ?></a></li>
            <?php endforeach; ?>
        </ul>
        <?php endif; ?>

        <div class="RFPostsGrid__items">
            <div class="RFPostsGrid__sizer <?php echo $this->get_column_classes(true); ?>"></div>
            <?php while( $the_query->have_posts() ): $the_query->the_post(); ?>
                <div class="RFPostsGrid__item <?php echo $this->get_item_classes(); ?>">
                    <div class="RFPostsGrid__inner">

                        <?php if ( has_post_thumbnail() ): ?>
                            <div class="RFPostsGrid__image">
                                <?php the_post_thumbnail( $image_size, array( 'class' => 'img-responsive') ); ?>
                            </div>
                        <?php endif; ?>

                        <div class="RFPostsGrid__title">
                            <h3><?php the_title(); ?></h3>
                        </div>

                        <div class="RFPostsGrid__meta">
                            <h4><?php the_time( 'd.m.y' ); ?></h4>
                        </div>

                        <div class="RFPostsGrid__excerpt">
                            <?php the_excerpt(); ?>
                        </div>

                        <div class="RFPostsGrid__read-more">
                            <a class="btn btn-primary" href="<?php the_permalink(); ?>">Read More</a>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        </div>
    </div>
<?php endif; ?>