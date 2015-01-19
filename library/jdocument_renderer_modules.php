<?php
/**
 * @package     Joomla.Platform
 * @subpackage  Document
 *
 * @copyright   Copyright (C) 2005 - 2013 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE
 */

defined('JPATH_PLATFORM') or die;

/**
 * JDocument Modules renderer
 *
 * @package     Joomla.Platform
 * @subpackage  Document
 * @since       11.1
 */
class JDocumentRendererModules extends JDocumentRenderer
{
	/**
	 * Renders multiple modules script and returns the results as a string
	 *
	 * @param   string  $position  The position of the modules to render
	 * @param   array   $params    Associative array of values
	 * @param   string  $content   Module content
	 *
	 * @return  string  The output of the script
	 *
	 * @since   11.1
	 */
	public function render($position, $params = array(), $content = null)
	{
		$renderer = $this->_doc->loadRenderer('module');
		$buffer = '';

		$app = JFactory::getApplication();
		$frontediting = $app->get('frontediting', 1);
		$user = JFactory::getUser();

		$canEdit = $user->id && $frontediting && !($app->isAdmin() && $frontediting < 2) && $user->authorise('core.edit', 'com_modules');
		$menusEditing = ($frontediting == 2) && $user->authorise('core.edit', 'com_menus');

        $modules = JModuleHelper::getModules($position); //add for plugin
        $count = count($modules); //add for plugin
        $counter = 0; //add for plugin
        $style = (isset($params['style']))? $params['style'] : NULL; //add for plugin

        $plugin = JPluginHelper::getPlugin('system', 'nnbootstrapparams'); //add for plugin
        $pluginparams = json_decode($plugin->params); //add for plugin
        $exludeposition = $pluginparams->exludeposition; //add for plugin
        if($exludeposition != '')$exludeposition = explode(',',$exludeposition); //add for plugin

        foreach ( $modules as $mod)
		{
            if(!in_array($position,$exludeposition)) {
                $mod = $this->changeparams($mod,$count,$counter,$style); //add for plugin
            }

			$moduleHtml = $renderer->render($mod, $params, $content);

			if ($app->isSite() && $canEdit && trim($moduleHtml) != '' && $user->authorise('core.edit', 'com_modules.module.' . $mod->id))
			{
				$displayData = array('moduleHtml' => &$moduleHtml, 'module' => $mod, 'position' => $position, 'menusediting' => $menusEditing);
				JLayoutHelper::render('joomla.edit.frontediting_modules', $displayData);
			}

			$buffer .= $moduleHtml;
            $counter++; //add for plugin
		}
		return $buffer;
	}



    public function changeparams($module,$count,$counter,$style = null) {

        $params = new JRegistry;
        $params->loadString($module->params);

        $moduleclass_sfx = ' '.$params->get('moduleclass_sfx');

        //Check the first and last Module on Position
        switch($counter) {
            case '0':
                $moduleclass_sfx .= ' first';
                break;
            case ($counter == $count-1):
                $moduleclass_sfx .= '  last';
                break;
        }

        //Set an Modulecounter to CSS
        $moduleclass_sfx .= ' box'.$counter;


        $paramsChromeStyle = $params->get('style');

        //Check Module Style Parameter is not set jdoc style is default
        if($paramsChromeStyle) $style = $paramsChromeStyle;

        if($style!='table' and $style!='horz' and $style!='none' and $style!='outline' and  $style!='System-none'){

            //Standard col-xs-12 when nothing is set
            if(!$params->get('extra_small_devices_grid') and !$params->get('small_devices_grid') and !$params->get('medium_devices_grid') and !$params->get('large_devices_grid')){
                if($params->get('bootstrap_size'))
                    $moduleclass_sfx .=' col-xs-'.$params->get('bootstrap_size');
                else
                   $moduleclass_sfx .=' col-xs-12';
            }

            //Bootstrap Grid
            if($params->get('extra_small_devices_grid'))
                $moduleclass_sfx .=' col-xs-'.$params->get('extra_small_devices_grid');

            if($params->get('small_devices_grid'))
                $moduleclass_sfx .=' col-sm-'.$params->get('small_devices_grid');

            if($params->get('medium_devices_grid'))
                $moduleclass_sfx .=' col-md-'.$params->get('medium_devices_grid');

            if($params->get('large_devices_grid'))
                $moduleclass_sfx .=' col-lg-'.$params->get('large_devices_grid');

			//Bootstrap Offset
			if($params->get('extra_small_devices_offset'))
				$moduleclass_sfx .=' col-xs-offset-'.$params->get('extra_small_devices_offset');

			if($params->get('small_devices_offset'))
				$moduleclass_sfx .=' col-sm-offset-'.$params->get('small_devices_offset');

			if($params->get('medium_devices_offset'))
				$moduleclass_sfx .=' col-md-offset-'.$params->get('medium_devices_offset');

			if($params->get('large_devices_offset'))
				$moduleclass_sfx .=' col-lg-offset-'.$params->get('large_devices_offset');

            //visible and hidden
            if($params->get('extra_small_devices_available') == 1)
                $moduleclass_sfx .=' hidden-xs';

            if($params->get('extra_small_devices_available') == 2)
                $moduleclass_sfx .=' visible-xs';

            if($params->get('small_devices_available') == 1)
                $moduleclass_sfx .=' hidden-sm';

            if($params->get('small_devices_available') == 2)
                $moduleclass_sfx .=' visible-sm';

            if($params->get('medium_devices_available') == 1)
                $moduleclass_sfx .=' hidden-md';

            if($params->get('medium_devices_available') == 2)
                $moduleclass_sfx .=' visible-md';

            if($params->get('large_devices_available') == 1)
                $moduleclass_sfx .=' hidden-lg';

            if($params->get('large_devices_available') == 2)
                $moduleclass_sfx .=' visible-lg';

            //Print
            if($params->get('bootstrap_print') == 1)
                $moduleclass_sfx .=' hidden-print';

            if($params->get('bootstrap_print') == 2)
                $moduleclass_sfx .=' visible-print';

            //Milch
            if($params->get('fullwidth_image'))
                $moduleclass_sfx .=' fullwidth-image';

            if($params->get('last_p_nomargin'))
                $moduleclass_sfx .=' last-p-nomargin';

            if($params->get('every_p_nomargin'))
                $moduleclass_sfx .=' every-p-nomargin';

            if($params->get('nomargin_left'))
                $moduleclass_sfx .=' nomargin-left';

            if($params->get('sm_nomargin_left'))
                $moduleclass_sfx .=' sm-nomargin-left';

            if($params->get('md_nomargin_left'))
                $moduleclass_sfx .=' md-nomargin-left';

            if($params->get('lg_nomargin_left'))
                $moduleclass_sfx .=' lg-nomargin-left';

            if($params->get('nomargin_right'))
                $moduleclass_sfx .=' nomargin-right';

            if($params->get('sm_nomargin_right'))
                $moduleclass_sfx .=' sm-nomargin-right';

            if($params->get('md_nomargin_right'))
                $moduleclass_sfx .=' md-nomargin-right';

            if($params->get('lg_nomargin_right'))
                $moduleclass_sfx .=' lg-nomargin-right';

            if($params->get('nopadding_left'))
                $moduleclass_sfx .=' nopadding-left';

            if($params->get('sm_nopadding_left'))
                $moduleclass_sfx .=' sm-nopadding-left';

            if($params->get('md_nopadding_left'))
                $moduleclass_sfx .=' md-nopadding-left';

            if($params->get('lg_nopadding_left'))
                $moduleclass_sfx .=' lg-nopadding-left';

            if($params->get('nopadding_right'))
                $moduleclass_sfx .=' nopadding-right';

            if($params->get('sm_nopadding_right'))
                $moduleclass_sfx .=' sm-nopadding-right';

            if($params->get('md_nopadding_right'))
                $moduleclass_sfx .=' md-nopadding-right';

            if($params->get('lg_nopadding_right'))
                $moduleclass_sfx .=' lg-nopadding-right';

            if($params->get('sm_pull_right'))
                $moduleclass_sfx .=' sm-pull-right';

            if($params->get('md_pull_right'))
                $moduleclass_sfx .=' md-pull-right';

            if($params->get('lg_pull_right'))
                $moduleclass_sfx .=' lg-pull-right';

			$styleattr = NULL;

			//Margin
			if($params->get('margin_top'))
				$styleattr .=' margin-top: '.$params->get('margin_top').'px;';

			if($params->get('margin_right'))
				$styleattr .=' margin-right: '.$params->get('margin_right').'px;';

			if($params->get('margin_bottom'))
				$styleattr .=' margin-bottom: '.$params->get('margin_bottom').'px;';

			if($params->get('margin_left'))
				$styleattr .=' margin-left: '.$params->get('margin_left').'px;';

			//Padding
			if($params->get('padding_top'))
				$styleattr .=' padding-top: '.$params->get('padding_top').'px;';

			if($params->get('padding_right'))
				$styleattr .=' padding-right: '.$params->get('padding_right').'px;';

			if($params->get('padding_bottom'))
				$styleattr .=' padding-bottom: '.$params->get('padding_bottom').'px;';

			if($params->get('padding_left'))
				$styleattr .=' padding-left: '.$params->get('padding_left').'px;';

			//Background
			if($params->get('background'))
				$styleattr .=' background: '.$params->get('background').';';

			if(!is_null($styleattr) or $params->get('clearfix',0) ) {
				if($style == 'html5') $params->set('style','html5kickstart');
				$params->set('styleattr',$styleattr);
				$params->set('clearfix',$params->get('clearfix'));
			}

		}

        $params->set('moduleclass_sfx',$moduleclass_sfx);

        //set new Parameter
        $module->params = $params;
        return $module;
    }
}
