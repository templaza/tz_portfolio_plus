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

//no direct access
defined('_JEXEC') or die('Restricted access');


class TZ_Portfolio_PlusViewTemplate extends JViewLegacy
{
    protected $item = null;
    protected $tzlayout = null;
    protected $form = null;
    protected $childrens = null;

    public function display($tpl=null)
    {

        $this -> form       = $this -> get('Form');

        TZ_Portfolio_PlusHelper::addSubmenu('templates');
        $this -> sidebar    = JHtmlSidebar::render();

        $this -> addToolbar();

        parent::display($tpl);
    }

    protected function addToolbar(){

        JFactory::getApplication()->input->set('hidemainmenu', true);

        JToolBarHelper::title(JText::sprintf('COM_TZ_PORTFOLIO_PLUS_TEMPLATES_MANAGER_TASK',JText::_('COM_TZ_PORTFOLIO_PLUS_TEMPLATE_INSTALL_TEMPLATE')),'eye');
        JToolBarHelper::cancel('template.cancel',JText::_('JTOOLBAR_CLOSE'));

        JToolBarHelper::help('JHELP_CONTENT_ARTICLE_MANAGER',false,
            'https://www.tzportfolio.com/document/administration/35-how-to-use-templates-in-tz-portfolio-plus.html?tmpl=component');

        TZ_Portfolio_PlusToolbarHelper::customHelp('https://www.youtube.com/channel/UCrLN8LMXTyTahwDKzQ-YOqg/videos'
            ,'COM_TZ_PORTFOLIO_PLUS_VIDEO_TUTORIALS', 'youtube', 'youtube');
    }
}