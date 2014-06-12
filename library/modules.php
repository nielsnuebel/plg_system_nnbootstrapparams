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

        $modules = JModuleHelper::getModules($position);
        $count = count($modules);
        $counter = 0;
        $style = (isset($params['style']))? $params['style'] : NULL;

        $plugin = JPluginHelper::getPlugin('system', 'nnbootstrapparams');
        $pluginparams = json_decode($plugin->params);
        $exludeposition = $pluginparams->exludeposition;
        if($exludeposition != '')$exludeposition = explode(',',$exludeposition);

        foreach ( $modules as $mod)
		{
            if(!in_array($position,$exludeposition)) {
                $mod = $this->changeparams($mod,$count,$counter,$style,$pluginparams);
            }
			$moduleHtml = $renderer->render($mod, $params, $content);

			if ($app->isSite() && $canEdit && trim($moduleHtml) != '' && $user->authorise('core.edit', 'com_modules.module.' . $mod->id))
			{
				$displayData = array('moduleHtml' => &$moduleHtml, 'module' => $mod, 'position' => $position, 'menusediting' => $menusEditing);
				JLayoutHelper::render('joomla.edit.frontediting_modules', $displayData);
			}

			$buffer .= $moduleHtml;
            $counter++;
		}
		return $buffer;
	}

    public function changeparams($module,$count,$counter,$style = null,$pluginparams) {

        $params = new JRegistry;
        $params->loadString($module->params);

        $moduleclass_sfx = $params->get('moduleclass_sfx');

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
                if($params->get('add_default_col')!=2)
				{
					if($pluginparams->add_default_col_global or $params->get('add_default_col'))
					{
						if($params->get('bootstrap_size'))
							$moduleclass_sfx .=' col-xs-'.$params->get('bootstrap_size');
						else
							$moduleclass_sfx .=' col-xs-12';
					}
				}
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


			if($pluginparams->add_margin){
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

				if(!is_null($styleattr)) {
					if($style == 'html5') $params->set('style','html5kickstart');
					$params->set('styleattr',$styleattr);
				}
			}
		}

        $params->set('moduleclass_sfx',$moduleclass_sfx);

        //set new Parameter
        $module->params = $params;
        return $module;
    }
}
