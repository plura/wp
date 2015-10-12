<?php if (is_front_page()) { ?>


<div class="navbar-wrapper">
  
  <div class="container">
      
    <div class="navbar navbar-default <!--navbar-static-top-->" role="navigation">


<?php } else { ?>


  <div class="navbar navbar-default navbar-fixed-top" role="navigation">

    <div class="container">


<?php } ?>


      <div class="row">

        <div class="col-xs-12 col-md-9">

          <div class="navbar-header">
            
            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
              <span class="sr-only">Toggle navigation</span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
              <span class="icon-bar"></span>
            </button>
            
            <a class="navbar-brand" href="<?php echo home_url(); ?>"><?php bloginfo('name'); ?></a>
          
          </div><!--/navbar-header-->
          

          <div class="collapse navbar-collapse">

            <!-- NAV -->
            <?php

              include_once(dirname(__FILE__) . '/../bootstrap/walkers/pwp_bootstrap_navwalker.php');

              wp_nav_menu( array(
                  'menu'              => 'Main',
                  'container'         => false,
                  'menu_class'        => 'nav navbar-nav',
                  'fallback_cb'       => 'pwp_bootstrap_navwalker::fallback',
                  'walker'            => new pwp_bootstrap_navwalker()
              ));               
          
            ?>

            <!-- LANG TOGGLE -->
            <!--<div class="lang-holder navbar-nav pull-right">

              <div class="btn-group" id="lang-toggle">
                <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
                  <span class="glyphicon glyphicon-globe"></span><span class="caret"></span>
                </button>
              <?php

                echo qtranxf_generateLanguageSelectCode('text');

              ?>
              </div>

            </div>--><!--/lang-holder-->

<div class="btn-group" role="group">
    <button type="button" class="btn btn-default navbar-btn dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      <span class="glyphicon glyphicon-globe"></span>&nbsp;&nbsp;<span class="caret"></span>
    </button>
              <?php

                echo qtranxf_generateLanguageSelectCode('text');

              ?>
  </div>            


          </div><!--/.nav-collapse -->


        </div><!--/col -->


          
        <div class="col-xs-12 col-md-3">
          
          <!-- SEARCH -->
          <?php get_search_form(); ?>
        
        </div><!--/col -->



      </div><!--/row -->





<?php if (is_front_page()) { ?>

    </div>
  
  </div>

</div>


<?php } else { ?>

  </div>

</div>


<?php } ?>

