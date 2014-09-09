<?php
function modChrome_html5onpage ($module, &$params, &$attribs)
{
	$moduleTag      = $params->get('module_tag', 'div');
	$headerTag      = htmlspecialchars($params->get('header_tag', 'h3'));
	$bootstrapSize  = (int) $params->get('bootstrap_size', 0);
	$moduleClass    = $bootstrapSize != 0 ? ' span' . $bootstrapSize : '';

	// Temporarily store header class in variable
	$headerClass	= $params->get('header_class');
	$headerClass	= !empty($headerClass) ? ' class="' . htmlspecialchars($headerClass) . '"' : '';

	if (!empty ($module->content)) : ?>
		<<?php echo $moduleTag; ?> <?php echo ($params->get('styleattr') != null)?'style="'.$params->get('styleattr').'"':''; ?>  <?php echo ($params->get('onepage_id') != null)?' id="'.$params->get('onepage_id').'"':''; ?> <?php echo ($params->get('onepage_class') != null)?' class="'.$params->get('onepage_class').'"':''; ?>>
		<?php
		if($params->get('onepage_container')) echo '<div class="container">';
		if($params->get('onepage_row')) echo '<div class="row">';
		if($params->get('onepage_fullimage')) echo '<div class="teaserfullimage">';
		?>
	<div class="moduletable <?php if(!$params->get('onepage_noclass')) echo htmlspecialchars($params->get('moduleclass_sfx')) . $moduleClass; ?>">
		<?php if ((bool) $module->showtitle) :?>
			<<?php echo $headerTag . $headerClass . '>' . $module->title; ?></<?php echo $headerTag; ?>>
		<?php endif; ?>
		<?php echo $module->content;
		if($params->get('onepage_fullimage')) echo "</div>";
		if($params->get('onepage_row')) echo "</div>";
		if($params->get('onepage_container')) echo "</div>";
		?>
		</div>
		</<?php echo $moduleTag; ?>>
	<?php endif;
}

function modChrome_html5kickstart ($module, &$params, &$attribs)
{
	$moduleTag      = $params->get('module_tag', 'div');
	$headerTag      = htmlspecialchars($params->get('header_tag', 'h3'));
	$bootstrapSize  = (int) $params->get('bootstrap_size', 0);
	$moduleClass    = $bootstrapSize != 0 ? ' span' . $bootstrapSize : '';

	// Temporarily store header class in variable
	$headerClass	= $params->get('header_class');
	$headerClass	= !empty($headerClass) ? ' class="' . htmlspecialchars($headerClass) . '"' : '';

	if (!empty ($module->content)) : ?>
		<<?php echo $moduleTag; ?> class="moduletable<?php echo htmlspecialchars($params->get('moduleclass_sfx')) . $moduleClass; ?>"
		<?php echo ($params->get('styleattr') != null)?'style="'.$params->get('styleattr').'"':''; ?>>
		<?php if ((bool) $module->showtitle) :?>
			<<?php echo $headerTag . $headerClass . '>' . $module->title; ?></<?php echo $headerTag; ?>>
		<?php endif; ?>
		<?php echo $module->content; ?>
		</<?php echo $moduleTag; ?>>
		<?php if($params->get('clearfix'))  echo '<div class="clearfix"></div>'; ?>
	<?php endif;
}