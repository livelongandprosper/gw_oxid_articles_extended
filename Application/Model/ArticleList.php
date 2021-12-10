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
	 * @param $oArticle
	 * @return null
	 */
	public function loadSuitableProducts(\OxidEsales\Eshop\Application\Model\Article $oArticle) {
		$myConfig = $this->getConfig();

		// Performance
		if (!$myConfig->getConfigParam('bl_perfLoadCrossselling')) {
			return null;
		}

		// get manually assigned cross selling
		$crossselling = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
		$crossselling->loadArticleCrossSell($oArticle->getId());
		if(sizeof($crossselling)) {
			$this->_appendList($crossselling);
		}

		// get suitable
		$suitable = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
		$suitable->loadSuitableArticles($oArticle);
		if(sizeof($suitable)) {
			$this->_appendList($suitable);
		}
	}

	/**
	 * Load suitable products by attributes
	 * @param $oArticle
	 */
	public function loadSuitableArticles($oArticle) {
		$return_value = null;
		$myConfig = $this->getConfig();

		// ZEHA Socken für Erwachsene
		if( method_exists($oArticle, 'getAttributesByIdent') && trim($oArticle->oxarticles__gw_delivery_id->value) == "SCH" && trim(strtolower($oArticle->getAttributesByIdent('gender', true))) != 'kind' ) {
			// socken kategorie id b5c60a585409d88d971898203a5a2127
			$this->loadCategoryArticles('b5c60a585409d88d971898203a5a2127', [], 10);
		}

		// ZEHA Socken für Erwachsene
		if( method_exists($oArticle, 'getAttributesByIdent') && trim($oArticle->oxarticles__gw_delivery_id->value) == "SCH" && trim(strtolower($oArticle->getAttributesByIdent('gender', true))) == 'kind' ) {
			// kinder socken kategorie id 460aab9ea55703d046d4405a9697afb4
			$this->loadCategoryArticles('460aab9ea55703d046d4405a9697afb4', [], 10);
		}
	}

	/**
	 * Loads all articles that has the same model number suffix e.g. xxx.0185
	 * @param \OxidEsales\Eshop\Application\Model\Article $oArticle
	 * @return void |null
	 */
	public function loadSimilarArticlesByArtNrRelation($oArticle) {
		$return_value = null;
		$myConfig = $this->getConfig();
		$article_number_db_field = $myConfig->getConfigParam('gw_oxid_articles_extended_model_dbfield');
		$suffix_length = intval($myConfig->getConfigParam('gw_oxid_articles_extended_similar_suffix_length'));
		$model_number_separator = $myConfig->getConfigParam('gw_oxid_articles_extended_model_separator');
		$article_number = $oArticle->{'oxarticles__'.$article_number_db_field}->value;
		$separator_string_position = strripos($article_number, $model_number_separator);
		$suffix_to_compare = "";

		if($article_number && $model_number_separator && $separator_string_position ) {

			if($suffix_length) {
				$suffix_to_compare = substr( $article_number, $separator_string_position+strlen($model_number_separator), $suffix_length );
			} else {
				$suffix_to_compare = substr( $article_number, $separator_string_position+strlen($model_number_separator) );
			}
		}
		if($suffix_to_compare) {
			$sArticleTable = $oArticle->getViewName();

			$sSearch = "
				SELECT 
					$sArticleTable.*
				FROM
					$sArticleTable
				WHERE 
						" . $oArticle->getSqlActiveSnippet() . "
					and $sArticleTable.$article_number_db_field LIKE '%" . $model_number_separator . $suffix_to_compare . ( $suffix_length ? '%' : '' ) . "'
					and $sArticleTable.OXPARENTID = ''
					and $sArticleTable.OXID != '".$oArticle->getId()."' # uncomment this line if the current article should not be listed
					" . ( isset($oArticle->oxarticles__gw_delivery_id) ? "and $sArticleTable.gw_delivery_id='" . $oArticle->oxarticles__gw_delivery_id->value . "'" : "" ) . "
				ORDER BY
					rand()
				LIMIT
					10
			";
			// TODO: order by what??
			// $sSearch .= ' order by rand() ';

			$this->selectString($sSearch);
		}
	}

	/**
	 * Improve standard function so that articles of a category can added to every article as accessory
	 * @param $sArticleId
	 */
	public function loadArticleAccessoires($sArticleId) {
		parent::loadArticleAccessoires($sArticleId);

		$myConfig = $this->getConfig();
		if($accessoriesCategoryId = $myConfig->getConfigParam('gw_oxid_articles_extended_accessories_category')) {
			$categoryAccesories = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
			if($categoryAccesories->loadCategoryArticles($accessoriesCategoryId, null)) {
				$this->_appendList($categoryAccesories);
			}
		}
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
