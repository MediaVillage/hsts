<?php if ( count($terms) ): ?>
    <div class="RFTermList">
        <?php if ( isset($title) ): ?>
            <h3 class="RFTermList__title"><?php echo $title; ?></h3>
        <?php endif; ?>
        <?php foreach($terms as $term): ?>
            <div class="RFTermList__term">
                <a href="<?php echo get_term_link($term); ?>"><?php echo $term->name; ?></a>
            </div>
        <?php endforeach; ?>
    </div>
<?php endif; ?>