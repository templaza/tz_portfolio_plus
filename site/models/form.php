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
use Joomla\Utilities\ArrayHelper;

// Base this model on the backend version.
JLoader::register('TZ_Portfolio_PlusModelArticle', JPATH_ADMINISTRATOR
    . '/components/com_tz_portfolio_plus/models/article.php');

class TZ_Portfolio_PlusModelForm extends TZ_Portfolio_PlusModelArticle
{
    public function __construct()
    {
        $lang   = JFactory::getLanguage();
        $lang -> load('com_tz_portfolio_plus', JPATH_ADMINISTRATOR);
        $lang -> load('com_content', JPATH_ADMINISTRATOR);
        parent::__construct();
    }

    protected function populateState()
    {
        $app = JFactory::getApplication();

        // Load state from the request.
        $pk = $app->input->getInt('a_id');
        $this->setState('article.id', $pk);

        $this->setState('article.catid', $app->input->getInt('catid'));

        $return = $app->input->get('return', null, 'base64');
        $this->setState('return_page', base64_decode($return));

        // Load the parameters.
        $params = $app->getParams();
        $this->setState('params', $params);

        $this->setState('layout', $app->input->getString('layout'));
    }

    public function getItem($itemId = null)
    {

        $itemId = (int) (!empty($itemId)) ? $itemId : $this->getState('article.id');

        // Get a row instance.
        $table = $this->getTable();

        // Attempt to load the row.
        $return = $table->load($itemId);

        // Check for a table object error.
        if ($return === false && $table->getError())
        {
            $this->setError($table->getError());

            return false;
        }

        $properties = $table->getProperties(1);
        $value = ArrayHelper::toObject($properties, 'JObject');

        // Convert attrib field to Registry.
        $value->params = new Registry($value->attribs);

        // Convert the params field to an array.
        $registry = new Registry;
        $registry->loadString($value->attribs);
        $value->attribs = $registry->toArray();

        // Compute selected asset permissions.
        $user   = JFactory::getUser();
        $userId = $user->get('id');
        $asset  = 'com_tz_portfolio_plus.article.' . $value->id;

        // Check general edit permission first.
        if ($user->authorise('core.edit', $asset))
        {
            $value->params->set('access-edit', true);
        }

        // Now check if edit.own is available.
        elseif (!empty($userId) && $user->authorise('core.edit.own', $asset))
        {
            // Check for a valid user and that they are the owner.
            if ($userId == $value->created_by)
            {
                $value->params->set('access-edit', true);
            }
        }

        // Check edit state permission.
        if ($itemId)
        {
            // Existing item
            $value->params->set('access-change', $user->authorise('core.edit.state', $asset));
        }
        else
        {
            // New item.
            $catId = (int) $this->getState('article.catid');

            if ($catId)
            {
                $value->params->set('access-change', $user->authorise('core.edit.state', 'com_tz_portfolio_plus.category.' . $catId));
                $value->catid = $catId;
            }
            else
            {
                $value->params->set('access-change', $user->authorise('core.edit.state', 'com_tz_portfolio_plus'));
            }
        }

        $value->articletext = $value->introtext;

        if (!empty($value->fulltext))
        {
            $value->articletext .= '<hr id="system-readmore" />' . $value->fulltext;
        }

        // Convert the metadata field to an array.
        $registry = new Registry($value->metadata);
        $value->metadata = $registry->toArray();


        if(isset($value -> media) && !empty($value -> media)){
            $media = new Registry;
            $media -> loadString($value -> media);
            $value -> media  = $media -> toArray();
        }


        return $value;
    }

    protected function loadFormData()
    {
        // Check the session for previously entered form data.
        $app = JFactory::getApplication();
        $data = $app->getUserState('com_tz_portfolio_plus.edit.article.data', array());

        if (empty($data))
        {
            $data               = $this->getItem();
            if($second_categories  = TZ_Portfolio_PlusHelperCategories::getCategoriesByArticleId($data -> id, 0)) {
                if (is_array($second_categories)) {
                    $catids = ArrayHelper::getColumn($second_categories, 'id');
                } else {
                    $catids = $second_categories->id;
                }

                $data->set('second_catid', $catids);
            }

            if($main_category      = TZ_Portfolio_PlusHelperCategories::getCategoriesByArticleId($data -> id, 1)) {
                if (is_array($main_category)) {
                    $catid = ArrayHelper::getColumn($main_category, 'id');
                } else {
                    $catid = $main_category->id;
                }
                $data->set('catid', $catid);
            }
        }

        $this->preprocessData('com_tz_portfolio_plus.article', $data);

        return $data;
    }


    protected function preprocessForm(JForm $form, $data, $group = 'content')
    {
        $params = $this->getState()->get('params');

        if ($params && $params->get('enable_category') == 1)
        {
            $form->setFieldAttribute('catid', 'default', $params->get('catid', 1));
            $form->setFieldAttribute('catid', 'readonly', 'true');
        }

        return parent::preprocessForm($form, $data, $group);
    }

}
