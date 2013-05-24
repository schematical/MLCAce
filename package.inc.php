<?php
define('__MLC_ACE__', dirname(__FILE__));
define('__MLC_ACE_CORE__', __MLC_ACE__ . '/_core');
define('__MLC_ACE_CORE_CTL__', __MLC_ACE_CORE__ . '/ctl');
define('__MLC_ACE_CORE_MODEL__', __MLC_ACE_CORE__ . '/model');
define('__MLC_ACE_CORE_VIEW__', __MLC_ACE_CORE__ . '/view');

MLCApplicationBase::$arrClassFiles['MLCAceEditorPanel'] = __MLC_ACE_CORE_CTL__ . '/MLCAceEditorPanel.class.php';
MLCApplicationBase::$arrClassFiles['MLCAceKeyBinding'] = __MLC_ACE_CORE_CTL__ . '/MLCAceKeyBinding.class.php';



require_once(__MLC_ACE_CORE_MODEL__ . '/_enum.inc.php');
require_once(__MLC_ACE_CORE_CTL__ . '/_events.inc.php');
require_once(__MLC_ACE_CORE_CTL__ . '/_actions.inc.php');


