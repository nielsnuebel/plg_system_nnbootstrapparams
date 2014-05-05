<?php
/**
 * @version 1.0.0
 * @package NNBootstrapparams
 * @copyright 2014 Niels Nübel- NN-Medienagentur
 * @license GNU GPLv3 <http://www.gnu.org/licenses/gpl.html>
 * @link http://www.nn-medienagentur.de
 */

defined('_JEXEC') or die;

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
            $form->loadFile('bootstrap', false);
        }
    }

    function onAfterInitialise() {
        $app = JFactory::getApplication();

        // version check
        $version = new JVersion();

        // abort if the current Joomla release is older
        if( version_compare( $version->getShortVersion(), "3", 'lt' ) ) {
            return false;
        }

        if ($app->isSite()) {
            // Make the auto loader aware of our modified NNBootstrapparams class
            JLoader::register(
                'JDocumentRendererModules',
                JPATH_PLUGINS . "/system/nnbootstrapparams/library/modules.php",
                true
            );
        }
    }
}