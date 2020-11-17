<?php
namespace gw\gw_oxid_articles_extended\Application\Model;

use OxidEsales\Eshop\Core\DatabaseProvider;
use oxRegistry;
use oxDb;
use OxidEsales\Eshop\Core\Registry;
use OxidEsales\Eshop\Core\Database\Adapter\DatabaseInterface;
use OxidEsales\Eshop\Core\Exception\DatabaseErrorException;

class ArticleList extends ArticleList_parent{
	/**
	 * Loads article cross selling
	 *
	 * @param string $sArticleId Article id
	 *
	 * @return null
	 */
	public function loadArticleRelatedProducts($sArticleId) {
		$myConfig = $this->getConfig();

		// Performance
		if (!$myConfig->getConfigParam('bl_perfLoadCrossselling')) {
			return null;
		}
		// get manually assigned cross selling
		$crossselling = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
		$crossselling->loadArticleCrossSell($sArticleId);

		$this->_appendList($crossselling);

		// TODO: get auto assigned crossselling
		// TODO: build similar function for accessories
	}

	/**
	 * @param ArticleList $articleList
	 */
	protected function _appendList($articleList) {
		foreach($articleList as $article) {
			$this->add($article);
		}
	}
}
