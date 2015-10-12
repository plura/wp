      <!--<hr>-->

<?php if ( !_has_fn('has_sidebar') || _fn('has_sidebar') ) { ?>

        </div> <!--/main-col [bootstrap]-->

        <?php include('includes/sidebar.php'); ?>

		  </div> <!--/row [4 bootsrap purposes > main | sidebar ] -->

<?php } ?>

    </div> <!-- /container -->

    <!--<footer>-->

    <?php include_once('includes/footer.php'); ?>

    <!--</footer>-->


    <?php wp_footer(); ?>

  </body>

</html>