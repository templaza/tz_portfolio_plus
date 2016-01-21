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

class TZ_Portfolio_PlusContentHelper{
    protected static $cache = array();

    public static function getArticleById($article_id, $resetCache = false, $articleObject = null)
    {
        if (!$article_id) {
            return null;
        }

        $storeId = md5(__METHOD__ . "::" . $article_id);
        if (!isset(self::$cache[$storeId]) || $resetCache) {

            if (!is_object($articleObject)) {
                $db     = JFactory::getDbo();
                $query  = $db->getQuery(true);
                $query  -> select('article.*, m.catid');
                $query  -> from('#__tz_portfolio_plus_content AS article');
                $query  -> join('INNER', '#__tz_portfolio_plus_content_category_map AS m ON m.contentid = article.id');
                $query  -> join('LEFT', '#__tz_portfolio_plus_categories AS c ON c.id = m.catid');
                $query  -> where('article.id = ' . $article_id);
                $query  -> where('m.main = 1');
                $db     -> setQuery($query);

                $articleObject = $db->loadObject();
            }

            if ($articleObject && $articleObject->catid > 0) {
                self::$cache[$storeId] = $articleObject;
            } else {
                return $articleObject;
            }
        }

        return self::$cache[$storeId];
    }
}