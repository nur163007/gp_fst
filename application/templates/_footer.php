	<!--script src="http://maps.google.com/maps/api/js?sensor=false"></script-->
    <div class="modal_busy"></div>
	<!-- Footer -->
	<footer class="site-footer">
		<span class="site-footer-legal">© <?php echo date('Y');?> Grameenphone Limited</span>
		<div class="site-footer-right small">
			<i class="wb wb-edit"></i> Powered by <a target="_blank" href="http://aqa.technology">Aqa technology</a>
		</div>
	</footer>

    <!-- Core  -->
    <script src="assets/vendor/jquery/jquery.js"></script>
    <script src="assets/vendor/bootstrap/bootstrap.js"></script>
    <script src="assets/vendor/animsition/jquery.animsition.js"></script>
    <script src="assets/vendor/asscroll/jquery-asScroll.js"></script>
    <script src="assets/vendor/mousewheel/jquery.mousewheel.js"></script>
    <script src="assets/vendor/asscrollable/jquery.asScrollable.all.js"></script>
    <script src="assets/vendor/ashoverscroll/jquery-asHoverScroll.js"></script>

    <!-- Plugins -->
    
    <script src="assets/vendor/switchery/switchery.min.js"></script>
    <script src="assets/vendor/intro-js/intro.js"></script>
    <script src="assets/vendor/screenfull/screenfull.js"></script>
    <script src="assets/vendor/slidepanel/jquery-slidePanel.js"></script>
	<script src="assets/vendor/alertify-js/alertify.js"></script>

	<?php
	if (is_array($page_specific_js)) {
		foreach( $page_specific_js as $script ) {
			 // Render a script tag
			 echo $script;
		}
	}
	?>

    
    <!--script src="assets/vendor/gmaps/gmaps.js"></script-->
    

    <!-- Scripts -->
    <script src="assets/js/aqa.script.lib.js?v=3"></script>
    <script src="assets/js/site.config.js?v=1.0.5"></script>
    <script src="assets/js/core.js"></script>
    <script src="assets/js/site.js"></script>    
    <script src="assets/js/sections/menu.js"></script>
    <script src="assets/js/sections/menubar.js"></script>
    <script src="assets/js/sections/sidebar.js"></script>

    <script src="assets/js/configs/config-colors.js"></script>
    <script src="assets/js/configs/config-tour.js"></script>

    <script src="assets/js/components/asscrollable.js"></script>
    <script src="assets/js/components/animsition.js"></script>
    <script src="assets/js/components/slidepanel.js"></script>
    <script src="assets/js/components/switchery.js"></script>
    <!--script src="assets/js/components/gmaps.js"></script-->
    <script src="assets/js/components/matchheight.js"></script>
    <!--script src="assets/js/components/alertify-js.js"></script-->
    
    <script src="application/handler/session.handler.js?v=1"></script>
    <?php if(1==2){ ?>
    <script src="application/handler/notification.handler.js"></script>
    <?php }?>
    <script src="application/handler/attachment.handler.js?v=2.0.4"></script>
    <script src="assets/vendor/datatables/jquery.dataTables.min.js"></script>
    <script src="assets/vendor/datatables-bootstrap/dataTables.bootstrap.js"></script>

    <script>
    $body = $("body");
    $(document).on({
        ajaxStart: function () {
            $body.addClass("working_ajax");
        },
        ajaxStop: function () {
            $body.removeClass("working_ajax");
        }
    });
    /*$(document).ready(function() {
    });*/
    
	(function(document, window, $) {
		'use strict';

		var Site = window.Site;

		$(document).ready(function($) {
			Site.run();
		});
   })(document, window, jQuery);

    $(document).ready(function() {
        /*$(".curnum").blur(function(e){
            $(this).val(commaSeperatedFormat($(this).val()));
        });*/
        /*$('.curnum').on('blur', function() {
            $(this).val(commaSeperatedFormat($(this).val()));
        });*/
        $(document).on('blur','.curnum',function(){
            $(this).val(commaSeperatedFormat($(this).val()));
        });
    });
    </script>

	<?php
	
	if (is_array($page_specific_jsfunc)) {
		foreach( $page_specific_jsfunc as $script ) {
			 // Render a script tag
			 echo $script;
		}
	}
	
	if (is_array($page_specific_js1)) {
		foreach( $page_specific_js1 as $script ) {
			 // Render a script tag
			 echo $script;
		}
	}
	?>

</body>
</html>