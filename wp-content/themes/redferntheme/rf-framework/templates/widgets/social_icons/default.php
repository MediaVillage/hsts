<?php if ( ! empty($this->social_media_types) ): ?>
	<div class="social-icons">
		<?php foreach( $this->social_media_types as $type => $icon ): ?>
			<?php $value = ! empty( $instance[$type] ) ? $instance[$type] : ''; ?>
			<?php if ( $value ): ?>
				<div class="social-platform social-<?php echo $type; ?>">
					<a href="<?php echo $value; ?>" target="_blank">
                        <?php if ( preg_match('/^(http|https)/', $icon) ): ?>
                            <img src="<?php echo $icon; ?>" alt="<?php echo $type; ?>">
                        <?php else: ?>
                            <i class="<?php echo $icon; ?>"></i>
                        <?php endif; ?>
                    </a>
				</div>
			<?php endif; ?>
		<?php endforeach; ?>
	</div>
<?php endif; ?>
