      <!--<hr>-->

        </div> <!--/main-col [bootstrap]-->

<?php if ( !_has_fn('has_sidebar') || _fn('has_sidebar') ) { ?>

        <?php include('includes/sidebar.php'); ?>

<?php } ?>

      </div> <!--/row [4 bootsrap purposes > main | sidebar ] -->

    </div> <!-- /container -->

    <!--<footer>-->

    <?php include_once('includes/footer.php'); ?>

    <!--</footer>-->


<?php

    if ( !is_front_page() ) {

      include( 'includes/modal.php' );

    }

?>    

    <?php wp_footer(); ?>

  </body>

</html>