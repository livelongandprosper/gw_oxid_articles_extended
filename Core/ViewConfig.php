<?php
	namespace gw\gw_oxid_articles_extended\Core;

	/**
	 * @see OxidEsales\Eshop\Core\ViewConfig
	 */
	class ViewConfig extends ViewConfig_parent {
		/**
		 * Load an article from database
		 * @param $oxid
		 * @return null
		 */
		public function gwLoadArticleFromId($oxid) {
			$article = oxNew(\OxidEsales\Eshop\Application\Model\Article::class);
			if($article->load($oxid)) {
				return $article;
			} else {
				return null;
			}
		}
	}
?>
