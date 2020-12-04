<div class="RFIbox <?php echo $extra_class; ?>">
    <div class="RFIbox__media">
        <?php echo $media; ?>
        <?php echo $overlay; ?>
    </div>

    <?php if ( $title ): ?>
        <div class="RFIBox__title">
            <?php echo $title; ?>
        </div>
    <?php endif; ?>

    <?php if ( $content ): ?>
        <div class="RFIbox__content">
            <?php echo $content; ?>
        </div>
    <?php endif; ?>
</div>