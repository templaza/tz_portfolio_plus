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
jimport('joomla.application.component.controllerform');

class TZ_Portfolio_PlusControllerField extends JControllerForm
{

//    public function save($key = null, $urlVar = null){
//        // Check for request forgeries.
//        JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
//
//        $app   = JFactory::getApplication();
//        $lang  = JFactory::getLanguage();
//        $model  = $this -> getModel();
//        $table = $model->getTable();
//        $context = "$this->option.edit.$this->context";
//        $task = $this->getTask();
//
//        // Determine the name of the primary key for the data.
//        if (empty($key))
//        {
//            $key = $table->getKeyName();
//        }
//
//        // To avoid data collisions the urlVar may be different from the primary key.
//        if (empty($urlVar))
//        {
//            $urlVar = $key;
//        }
//
//        $recordId = $this -> input -> getInt($urlVar);
//        $data   = $this -> input -> post -> get('jform', array(), 'array');
//
//        $context = "$this->option.edit.$this->context";
//        $task = $this->getTask();
//
//        // The save2copy task needs to be handled slightly differently.
//        if ($task == 'save2copy')
//        {
//            // Reset the ID and then treat the request as for Apply.
//            $data[$key] = 0;
//            $task = 'apply';
//        }
//
//        // Attempt to save the data.
//        if(!$model -> save($data)){
//            // Redirect back to the edit screen.
//            $this->setError(JText::sprintf('JLIB_APPLICATION_ERROR_SAVE_FAILED', $model->getError()));
//            $this->setMessage($this->getError(), 'error');
//            $this->setRedirect(
//                JRoute::_(
//                    'index.php?option=' . $this->option . '&view=' . $this->view_item
//                        . $this->getRedirectToItemAppend($recordId, $urlVar), false
//                )
//            );
//
//            return false;
//        }
//
//        // Redirect the user and adjust session state based on the chosen task.
//        switch ($task)
//        {
//            case 'apply':
//                // Set the record data in the session.
//                $recordId = $model->getState($this->context . '.id');
//                $app->setUserState($context . '.data', null);
//
//                // Redirect back to the edit screen.
//                $this->setRedirect(
//                    JRoute::_(
//                        'index.php?option=' . $this->option . '&view=' . $this->view_item
//                            . $this->getRedirectToItemAppend($recordId, $urlVar), false
//                    ),
//                    JText::_('COM_TZ_PORTFOLIO_PLUS_FIELD_SUCCESS')
//                );
//                break;
//
//            case 'save2new':
//                // Clear the record id and data from the session.
//                $this->releaseEditId($context, $recordId);
//                $app->setUserState($context . '.data', null);
//
//                // Redirect back to the edit screen.
//                $this->setRedirect(
//                    JRoute::_(
//                        'index.php?option=' . $this->option . '&view=' . $this->view_item
//                            . $this->getRedirectToItemAppend(null, $urlVar), false
//                    ),
//                    JText::_('COM_TZ_PORTFOLIO_PLUS_FIELD_SUCCESS')
//                );
//                break;
//
//            default:
//                // Clear the record id and data from the session.
//                $this->releaseEditId($context, $recordId);
//                $app->setUserState($context . '.data', null);
//
//                // Redirect to the list screen.
//                $this->setRedirect(
//                    JRoute::_(
//                        'index.php?option=' . $this->option . '&view=' . $this->view_list
//                            . $this->getRedirectToListAppend(), false
//                    ),
//                    JText::_('COM_TZ_PORTFOLIO_PLUS_FIELD_SUCCESS')
//                );
//                break;
//        }
//        return true;
//    }

    protected function allowAdd($data = array())
    {
        $user = TZ_Portfolio_PlusUser::getUser();
        return ($user->authorise('core.create','com_tz_portfolio_plus.group')
            || count($user->getAuthorisedFieldGroups('core.create')) > 0);
    }

    protected function allowEdit($data = array(), $key = 'id')
    {
        $recordId   = (int) isset($data[$key]) ? $data[$key] : 0;
        $user       = TZ_Portfolio_PlusUser::getUser();

        // Existing record already has an owner, get it
        $record = $this->getModel()->getItem($recordId);

        if (empty($record))
        {
            return false;
        }

        $canEdit	    = $user->authorise('core.edit',		  $this -> option.'.field.'.$recordId)
            && (count($user -> getAuthorisedFieldGroups('core.edit', $record -> groupid)) > 0);
        $canEditOwn	    = $user->authorise('core.edit.own', $this -> option.'.field.'.$recordId)
            && $record->created_by == $user->id
            && (count($user -> getAuthorisedFieldGroups('core.edit.own', $record -> groupid)) > 0);

        // Check edit on the record asset (explicit or inherited)
        if ($canEdit)
        {
            return true;
        }

        // Check edit own on the record asset (explicit or inherited)
        if ($canEditOwn)
        {
            return true;
        }

        // Zero record (id:0), return component edit permission by calling parent controller method
        if (!$recordId)
        {
            return parent::allowEdit($data, $key);
        }

        return false;
    }
}