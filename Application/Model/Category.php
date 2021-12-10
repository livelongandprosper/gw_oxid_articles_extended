<?php
namespace gw\gw_oxid_articles_extended\Application\Model;

/**
 * @see OxidEsales\Eshop\Application\Model\Category
 */
class Category extends Category_parent {
	/**
	 * @param $fieldName
	 * @return bool
	 */
	public function isMultilingualField($fieldName) {
		if(
			$fieldName == 'gw_frontend_title'
		) {
			return true;
		}
		return parent::isMultilingualField($fieldName);
	}
}
