<form class="form-horizontal row contacts-form">

	<div class="col-xs-12 col-sm-5 col-md-6">

		<div class="form-group">
		
			<label for="user_name" class="control-label col-sm-4 col-md-3"><?php _e('Name', _theme_name()); ?></label>
			<div class="col-sm-8 col-md-9">
				<input type="text" name="user_name" class="form-control">
			</div>
	
		</div>

		<div class="form-group">

			<label for="user_email" class="control-label col-sm-4 col-md-3">Email</label>
			<div class="col-sm-8 col-md-9">
				<input type="text" name="user_email" class="form-control">
			</div>

		</div>

		<div class="form-group">

			<label for="user_telephone" class="control-label col-sm-4 col-md-3"><?php _e('Telephone', _theme_name()); ?></label>
			<div class="col-sm-8 col-md-9">
				<input type="text" name="user_telephone" class="form-control">
			</div>

		</div>

		<div class="form-group">

			<!--<label for="mailinglist" class="control-label col-sm-4 col-md-3"><?php _e('Telephone', _theme_name()); ?></label>-->
			<div class="col-sm-8 col-md-9 col-sm-offset-4 col-md-offset-3 checkbox">
				<label for="mailinglist" class="small">
					<input type="checkbox" name="mailinglist"> <?php _e('Add me to the MailingList', _theme_name()); ?>
				</label>
			</div>

		</div>					

	</div><!--/col-->

	<div class="col-xs-12 col-sm-7 col-md-6">

		<div class="form-group">

			<label for="user_message" class="control-label col-sm-4 col-md-3"><?php _e('Message', _theme_name()); ?></label>
			<div class="col-sm-8 col-md-9">
				<textarea name="user_message" class="form-control"></textarea>
			</div>

		</div>

	</div><!--/col-->



	<div class="col-xs-12 col-md-6 col-md-offset-6">
		
		<div class="form-group">

			<div class="col-sm-8 col-sm-offset-4 col-md-9 col-md-offset-3">

				<input type="submit" value="<?php _e('Submit', _theme_name()); ?>" class="btn btn-primary btn-block">
			
			</div>

		</div>

	</div><!--/col-->

</form>




