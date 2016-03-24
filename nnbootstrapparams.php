<?php
/**
 * @version 1.0.0
 * @package NNBootstrapparams
 * @copyright 2014 Niels Nübel- NN-Medienagentur
 * @license GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link http://www.nn-medienagentur.de
 */

defined('_JEXEC') or die;

require_once JPATH_PLUGINS . "/system/nnbootstrapparams/library/styles.php";

/**
 * Class plgSystemNNBootstrapparams
 *
 * @category NNBootstrapparams
 * @package NNBootstrapparams
 * @author Niels Nübel <n.nuebel@nn-medienagentur.de>
 * @license GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link http://www.nn-medienagentur.de
 * @since 1.0.0
 */
class plgSystemNNBootstrapparams extends JPlugin
{
	public function __construct(& $subject, $config) {
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	public function onContentPrepareForm($form, $data) {
		if ($form->getName() == 'com_modules.module' or $form->getName() == 'com_advancedmodules.module') {
			JForm::addFormPath(__DIR__ . '/params');
			if ($this->params->get('add_bootstrap',1))
				$form->loadFile('bootstrap', false);
			if ($this->params->get('add_margin',1))
				$form->loadFile('margin', false);
			if ($this->params->get('add_padding',1))
				$form->loadFile('padding', false);
			if ($this->params->get('add_background',1))
				$form->loadFile('background', false);
			if ($this->params->get('add_onepage',1))
				$form->loadFile('onepage', false);
		}
	}

	function onAfterInitialise() {
		$app = JFactory::getApplication();

		// version check
		if (version_compare(JVERSION, '3', 'lt')) {
			return false;
		}

		JLoader::register(
			'JFormFieldChromeStyle',
			JPATH_PLUGINS . "/system/nnbootstrapparams/library/chromestyle.php",
			true
		);

		if ($app->isSite()) {
			// Make the auto loader aware of our modified NNBootstrapparams class
			if (!version_compare(JVERSION, '3.5', 'lt'))
			{
				JLoader::register(
					'JDocumentRendererHtmlModules',
					JPATH_PLUGINS . "/system/nnbootstrapparams/library/jdocument_renderer_html_modules.php",
					true
				);
			}
			else {
				JLoader::register(
					'JDocumentRendererModules',
					JPATH_PLUGINS . "/system/nnbootstrapparams/library/jdocument_renderer_modules.php",
					true
				);
			}
		}
	}
}