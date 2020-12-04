<div class="<?php echo $this->get_classes(); ?>" <?php echo $this->get_data_attributes(); ?> style="<?php echo $this->get_styles(); ?>">
    <?php foreach($this->markers as $marker): ?>
        <?php $this->render_marker($marker); ?>
    <?php endforeach; ?>
</div>