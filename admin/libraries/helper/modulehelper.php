<?php
/*------------------------------------------------------------------------

# TZ Portfolio Plus Extension

# ------------------------------------------------------------------------

# author    DuongTVTemPlaza

# copyright Copyright (C) 2015 templaza.com. All Rights Reserved.

# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL

# Websites: http://www.templaza.com

# Technical Support:  Forum - http://templaza.com/Forum

-------------------------------------------------------------------------*/

// No direct access
defined('_JEXEC') or die;

use Joomla\Registry\Registry;
use Joomla\CMS\Helper\ModuleHelper;

jimport('joomla.application.module.helper');

JLoader::import('com_tz_portfolio_plus.includes.framework',JPATH_ADMINISTRATOR.'/components');

abstract class TZ_Portfolio_PlusModuleHelper extends JModuleHelper{

    public static function getLayoutPath($module, $layout = 'default')
    {
        $_template  = \JFactory::getApplication()->getTemplate(true);
        $template   = $_template -> template;
        $defaultLayout = $layout;

        if (strpos($layout, ':') !== false)
        {
            // Get the template and file name from the string
            $temp = explode(':', $layout);
            $template = $temp[0] === '_' ? $template : $temp[0];
            $layout = $temp[1];
            $defaultLayout = $temp[1] ?: 'default';
        }

        $modParams  = new Registry();
        if($objModule  = ModuleHelper::getModule($module)){
            if(is_string($objModule -> params)) {
                $modParams->loadString($objModule->params);
            }else{
                $modParams  = $objModule -> params;
            }
        }

        if($tplId = (int) $modParams -> def('template_id', 0)) {
            $tpTemplate = TZ_Portfolio_PlusTemplate::getTemplateById($tplId);
        }
        else{
            $tpTemplate = TZ_Portfolio_PlusTemplate::getTemplate(true);
        }

        if($tpTemplate){
            $tplParams  = $tpTemplate->params;

            $tpdefPath  = null;
            $tpPath     = null;

            if(isset($tpTemplate -> home_path) && $tpTemplate -> home_path){
                $tpdefPath    = $tpTemplate -> home_path.'/' . $module . '/' . $layout . '.php';
            }
            if(isset($tpTemplate -> base_path) && $tpTemplate -> base_path){
                $tpPath    = $tpTemplate -> base_path.'/' . $module . '/' . $layout . '.php';
            }

            // Add template.css file if it has have in template
            if (JFile::exists(COM_TZ_PORTFOLIO_PLUS_TEMPLATE_PATH . '/' . $tpTemplate -> template
                . '/css/template.css')
            ) {

                $docOptions = array();
                $docOptions['template']     = $tpTemplate->template;
                $docOptions['file']         = 'template.php';
                $docOptions['params']       = $tplParams;
                $docOptions['directory']    = COM_TZ_PORTFOLIO_PLUS_TEMPLATE_PATH;

                $doc    = JFactory::getDocument();

                $docClone   = clone($doc);
                $docClone -> addStyleSheet(TZ_Portfolio_PlusUri::base(true) . '/templates/'
                    . $tpTemplate -> template . '/css/template.css');

                $docClone -> parse($docOptions);
                $doc -> setHeadData($docClone -> getHeadData());

            }
        }

        // Build the template and base path for the layout
        $tPath = JPATH_THEMES . '/' . $template . '/html/' . $module . '/' . $layout . '.php';
        $bPath = JPATH_BASE . '/modules/' . $module . '/tmpl/' . $defaultLayout . '.php';
        $dPath = JPATH_BASE . '/modules/' . $module . '/tmpl/default.php';

        // If the template has a layout override use it
        if ($tplParams->get('override_html_template_site', 0)) {

            if(file_exists($tpPath)){
                return $tpPath;
            }

            if(file_exists($tpdefPath)){
                return $tpdefPath;
            }

            if (file_exists($tPath))
            {
                return $tPath;
            }
        }else{
            if (file_exists($tPath))
            {
                return $tPath;
            }

            if(file_exists($tpPath)){
                return $tpPath;
            }

            if(file_exists($tpdefPath)){
                return $tpdefPath;
            }
        }

        if (file_exists($bPath))
        {
            return $bPath;
        }

        return $dPath;
    }

    public static function getAddOnModuleLayout($group, $name, $module, $layout='default', $folder = 'modules'){

        $template   = JFactory::getApplication()->getTemplate();

        if(!$layout){
            $layout = 'default';
        }

        $cfglayout  = $layout;

        if (strpos($layout, ':') !== false)
        {
            // Get the template and file name from the string
            $temp = explode(':', $layout);
            $template = ($temp[0] == '_') ? $template : $temp[0];
            $layout = $temp[1];
            $cfglayout = ($temp[1]) ? $temp[1] : 'default';
        }

//        $tmpl_folder    = null;
//        if($tmpl){
//            $tmpl_folder    = '/tmpl';
//        }


        // Get template
        $modParams  = new Registry();
        if($objModule  = ModuleHelper::getModule($module)){
            if(is_string($objModule -> params)) {
                $modParams->loadString($objModule->params);
            }else{
                $modParams  = $objModule -> params;
            }
        }

        if($tplId = (int) $modParams -> def('template_id', 0)) {
            $tpTemplate = TZ_Portfolio_PlusTemplate::getTemplateById($tplId);
        }
        else{
            $tpTemplate = TZ_Portfolio_PlusTemplate::getTemplate(true);
        }

        $tplParams  = $tpTemplate->params;

        // Build the template and base path for the layout
        $tpdefPath  = null;
        $tpPath     = null;


        // Path from template of TZ Portfolio Plus assigned
        if(isset($tpTemplate -> home_path) && $tpTemplate -> home_path){
            $tpdefPath    = $tpTemplate -> home_path.'/' . $module .'/plg_' . $group. '_' . $name
                . '/' . $layout . '.php';
        }
        // Path from default template of TZ Portfolio Plus
        if(isset($tpTemplate -> base_path) && $tpTemplate -> base_path){
            $tpPath    = $tpTemplate -> base_path.'/' . $module .'/plg_' . $group. '_' . $name
                . '/' . $layout . '.php';
        }

        // Path from Joomla Template
        $tPath = JPATH_THEMES . '/' . $template . '/html/'.$module.'/plg_' . $group . '_' . $name . '/' . $layout . '.php';

        // Path from module with module's layout config
        $mPath  = JPATH_SITE.'/modules/'.$module.'/tmpl/plg_'.$group.'_'.$name.'/'.$cfglayout.'.php';

//        // Path from module with module's default layout
//        $mdPath  = JPATH_SITE.'/modules/'.$module.'/tmpl/default.php';

        // Path from AddOn with module's layout config
        $bPath = COM_TZ_PORTFOLIO_PLUS_ADDON_PATH . '/' . $group . '/' . $name . '/'.$folder.'/'.$module
            .'/'. $cfglayout . '.php';

//        // Path from AddOn with module's default layout
//        $dPath = COM_TZ_PORTFOLIO_PLUS_ADDON_PATH . '/' . $group . '/' . $name . '/'.$folder.'/'.$module
//            .'/default.php';

        // If the template has a layout override use it
        if ($tplParams->get('override_html_template_site', 0)) {

            // Return path from TZ Portfolio Plus's Template assigned
            if(file_exists($tpPath)){
                return $tpPath;
            }

            // Return path from TZ Portfolio Plus's template which set default
            if(file_exists($tpdefPath)){
                return $tpdefPath;
            }

            // Return path from Joomla Template
            if (file_exists($tPath))
            {
                return $tPath;
            }

        }else{
            // If the template has a layout override use it

            // Return path from TZ Portfolio Plus's Template assigned
            if (file_exists($tPath))
            {
                return $tPath;
            }

            // Return path from Joomla Template
            if(file_exists($tpPath)){
                return $tpPath;
            }

            // Return path from TZ Portfolio Plus's template with default layout
            if(file_exists($tpdefPath)){
                return $tpdefPath;
            }
        }

        // Return path from module (If the module support the addon)
        if(file_exists($mPath)){
            return $mPath;
        }

        // Return path from TZ Porfolio Plus's addon
        if (file_exists($bPath))
        {
            return $bPath;
        }

//        if(file_exists($dPath)) {
//            return $dPath;
//        }

        return false;
    }
}