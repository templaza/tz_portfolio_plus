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

class TZ_Portfolio_Plus_AddonModelList extends JModelList
{
    protected $addon_type   = null;

    function populateState($ordering = null, $direction = null){

        // List state information.
        parent::populateState('id', 'asc');

        // Get the pk of the record from the request.
        $pk = JFactory::getApplication()->input->getInt('addon_id');
        $this->setState($this -> getName().'.addon_id', $pk);
    }

    public function getListQuery(){
        if($addonId = $this -> getState($this -> getName().'.addon_id')){
            $db     = $this -> getDbo();
            $query  = $db -> getQuery(true)
                -> select('*')
                -> from($db -> quoteName('#__tz_portfolio_plus_addon_data'))
                -> where('extension_id ='.$addonId);
            if($type = $this -> addon_type){
                $query -> where('type ='.$db -> quote($type));
            }

            // Add the list ordering clause.
            $orderCol = $this->getState('list.ordering','id');
            $orderDirn = $this->getState('list.direction','desc');

            if(!empty($orderCol) && !empty($orderDirn)){
                if(strpos($orderCol,'value.') !== false) {
                    $fields     = explode('.',$orderCol);
                    $orderCol   = array_pop($fields);
                    $query->order('substring_index(value,' . $db->quote('"'.$orderCol.'":') . ',-1) '. $orderDirn);
                }else{
                    $query->order($db->escape($orderCol . ' ' . $orderDirn));
                }
            }
            return $query;
        }
        return false;
    }

    public function getItems(){
        if($items = parent::getItems()){
            foreach($items as &$item){
                $item -> value  = json_decode($item -> value);
            }
            return $items;
        }
        return false;
    }

}