<?php
namespace gw\gw_oxid_articles_extended\Application\Model;

/**
 * @see OxidEsales\Eshop\Application\Model\Article
 */
class Article extends Article_parent {
	private $gw_variant_stocks = array();

	/**
	 * @var bool
	 */
	public $method_exists_variant_has_stock = true;

	/**
	 *
	 * @param $varselect
	 * @return bool|int
	 */
	public function variant_has_stock($varselect) {
		if(!isset($this->gw_variant_stocks[$varselect])) {
			$viewName = $this->getViewName();
			$oDb = \OxidEsales\Eshop\Core\DatabaseProvider::getDb(\OxidEsales\Eshop\Core\DatabaseProvider::FETCH_MODE_ASSOC);

			// get all variants of parent article and save this to gw_variant_stocks array – doing so reduces the amount of db requests to 1 per details view
			$sSelect = "
				SELECT
					OXSTOCK, OXVARSELECT
				FROM
					$viewName
				WHERE
						OXVARSELECT <> ''
					AND
						OXPARENTID = ".$oDb->quote($this->getParentId()?$this->getParentId():$this->getId())."
	    	";
			$resultSet = $oDb->select( $sSelect );
			$allResults = $resultSet->fetchAll();
			foreach($allResults as $row) {
				$this->gw_variant_stocks[$row['OXVARSELECT']] = intval( $row['OXSTOCK'] ) > 0 ? true : false;
			};
		}
		return $this->gw_variant_stocks[$varselect];
	}
}
?>
