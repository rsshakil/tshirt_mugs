<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="viewport" content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, user-scalable=yes">
<meta http-equiv="X-UA-Compatible" content="IE=Edge">
<meta http-equiv="Pragma" content="no-cache">
<meta http-equiv="Cache-Control" content="no-cache">
<title>
<?php if(is_home()): ?>
<?php bloginfo('name'); ?>
<?php else: ?>
<?php wp_title(' | ', true, 'right'); ?><?php bloginfo('name'); ?>
<?php endif; ?>
</title>
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/style.css" type="text/css" media="screen" />
<link rel="stylesheet" href="<?php bloginfo('template_directory'); ?>/css/headerWithSlider.css" type="text/css" media="screen" />
<link rel="stylesheet" type="text/css" href="<?php bloginfo('template_directory'); ?>/css/font-awesome.min.css" media="screen" />
<link href="<?php bloginfo('template_directory'); ?>/css/least.min.css" rel="stylesheet">
<link href="<?php bloginfo('template_directory'); ?>/css/jquery.easy-pie-chart.css" rel="stylesheet">
<link href='http://fonts.googleapis.com/css?family=Muli:300,400' rel='stylesheet' type='text/css'>
<link href="http://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.css" rel="stylesheet">
<style> html, body{ margin: 0; padding: 0; height: 100%; } </style>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery-1.10.2.min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jQuery.appear.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/superfish.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.flexslider-min.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/jquery.easing.1.3.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/easypiechart.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/canvas.js"></script>
<script type="text/javascript" src="<?php bloginfo('template_directory'); ?>/js/niceScroll.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/jquery.lazyload.js"></script>
<script src="<?php bloginfo('template_directory'); ?>/js/least.min.js"></script>

<script type="text/javascript">
  document.documentElement.className = 'js';
</script>

<?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
