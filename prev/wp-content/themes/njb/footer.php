
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/custom.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/headerWithSlider.js"></script>
<script type="text/javascript"  src="<?php bloginfo('template_directory'); ?>/js/smoothScroll.js"></script>
<script type="text/javascript"  src="<?php bloginfo('template_directory'); ?>/js/script.js"></script>
<script>
	$(document).ready(function(){
		$('#gallery').least({
			random: 0,
		});
	});

	$(function() {
    $('.chart').easyPieChart({
		barColor: 'rgba(255,255,255,0.8)',
		trackColor: 'rgba(0,0,0,0.5)',
        scaleColor: false,
		lineWidth: 5,
		size: 80
    });
});
    </script>
</body>
<?php wp_footer(); ?>
</html>
