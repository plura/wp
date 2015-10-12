<!--<form role="search" method="get" id="searchform" class="searchform" action="<?php echo esc_url( home_url( '/' ) ); ?>">
	<div>
		<label class="screen-reader-text" for="s"><?php _x( 'Search for:', 'label' ); ?></label>
		<input type="text" value="<?php echo get_search_query(); ?>" name="s" id="s" />
		<input type="submit" id="searchsubmit" value="<?php echo esc_attr_x( 'Search', 'submit button' ); ?>" />
	</div>
</form>-->



<form class="navbar-form" action="<?php echo home_url(); ?>">

	<div class="input-group">
		
		<input type="text" class="form-control" name="s">

		<div class="input-group-btn">
			
			<button class="btn btn-default">
				
				<span class="glyphicon glyphicon-search"></span>

			</button>

		</div>

	</div>

</form>