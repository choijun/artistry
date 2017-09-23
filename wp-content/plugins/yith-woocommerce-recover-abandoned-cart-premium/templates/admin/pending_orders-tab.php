<div class="wrap">
    <h2><?php _e('Pending Orders', 'yith-woocommerce-recover-abandoned-cart') ?></h2>

	<div id="poststuff">
		<div id="post-body" class="metabox-holder">
			<div id="post-body-content">
				<div class="meta-box-sortables ui-sortable">
					<form method="post">
						<input type="hidden" name="page" value="yith_woocommerce_recover_abandoned_cart" />
						<?php $this->cpt_obj_pending_orders->search_box('search', 'search_id'); ?>
					</form>
					<form method="post">
						<?php
						$this->cpt_obj_pending_orders->prepare_items();
						$this->cpt_obj_pending_orders->display(); ?>
					</form>
				</div>
			</div>
		</div>
		<br class="clear">
	</div>
</div>