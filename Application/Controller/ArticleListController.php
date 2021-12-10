<?php
namespace gw\gw_oxid_articles_extended\Application\Controller;

/**
 * @see OxidEsales\Eshop\Application\Controller\ArticleListController
 */
class ArticleListController extends ArticleListController_parent {
	/**
	 * @return string
	 */
	public function getTitle() {
		if($activeCategory = $this->getActiveCategory()) {
			if($activeCategory->oxcategories__gw_frontend_title->value != "") {
				return $activeCategory->oxcategories__gw_frontend_title->value;
			}
		}
		return parent::getTitle();
	}
}

