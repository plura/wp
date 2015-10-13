
<!-- Button trigger modal -->
<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal-quotes">
  <span class="icon"></span>
  <span class="text"><?php _e('Quote', _theme_name()); ?></span>
</button>

<!-- Modal -->
<div class="modal fade" id="modal-quotes" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  
  <div class="modal-dialog">
    
    <div class="modal-content">
      
      
      <div class="modal-header">
        
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        
        <h4 class="modal-title" id="myModalLabel"><?php _e('Quote Request Form', _theme_name()); ?></h4>
      
      </div><!--/modal-header-->
     


	  <form class="form-horizontal form-quotes">

      <div class="modal-body">


			<div class="form-group">

				<label for="user_name" class="control-label col-sm-3"><?php _e('Name', _theme_name()); ?></label>
				<div class="col-sm-9">
					<input type="text" name="user_name" class="form-control">
				</div>

			</div>

			<div class="form-group">

				<label for="user_email" class="control-label col-sm-3">Email</label>
				<div class="col-sm-9">
					<input type="text" name="user_email" class="form-control">
				</div>

			</div>

			<div class="form-group">

				<label for="user_telephone" class="control-label col-sm-3"><?php _e('Telephone', _theme_name()); ?></label>
				<div class="col-sm-9">
					<input type="text" name="user_telephone" class="form-control">
				</div>

			</div>

			<div class="form-group">

				<label for="user_message" class="control-label col-sm-3"><?php _e('Message', _theme_name()); ?></label>
				<div class="col-sm-9">
					<textarea name="user_message" class="form-control"></textarea>
				</div>

			</div>

			<div class="form-group">

				<div class="col-sm-8 col-md-9 col-sm-offset-4 col-md-offset-3 checkbox">
				
					<label for="mailinglist" class="small">
						<input type="checkbox" name="mailinglist"> <?php _e('Add me to the MailingList', _theme_name()); ?>
					</label>
				
				</div>

			</div>				

      </div><!--/modal-body-->

      <div class="modal-footer">
        
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php _e('Close', _theme_name()); ?></button>
        
        <input type="submit" value="<?php _e('Submit', _theme_name()); ?>" class="btn btn-primary">
        <!--<button type="button" class="btn btn-primary" type="submit"><?php _e('Send', _theme_name()); ?></button>-->
      
      </div><!--/modal-footer-->
    
    </form>

    </div><!--/modal-content-->
  
  </div><!--/modal-dialog-->

</div>