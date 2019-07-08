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
	 * @var bool
	 */
	public $method_exists_get_size_structure = true;

	/**
	 * TODO: maybe move this to module config because this is like a constant
	 * @var array
	 */
	private $size_match_array = [
		'general' => [ // trainer,
			'36' => '| UK 3,5 | US&#9792; 5,5 | US&#9794; 4,5',
			'37' => '| UK 4-4,5 | US&#9792; 6-6,5 | US&#9794; 5-5,5',
			'38' => '| UK 5 | US&#9792; 7 | US&#9794; 6',
			'39' => '| UK 5,5-6 | US&#9792; 7,5-8 | US&#9794; 6,5-7',
			'40' => '| UK 6,5 | US&#9792; 8,5 | US&#9794; 7,5',
			'41' => '| UK 7-7,5 | US&#9792; 9-9,5 | US&#9794; 8-8,5',
			'42' => '| UK 8-8,5 | US&#9792; 10-10,5 | US&#9794; 9-9,5',
			'43' => '| UK 9 | US&#9792; 11 | US&#9794; 10',
			'44' => '| UK 9,5-10 | US&#9792; 11,5-12 | US&#9794; 10,5-11',
			'45' => '| UK 10,5 | US&#9792; 12,5 | US&#9794; 11,5',
			'46' => '| UK 11-11,5 | US&#9792; 13-13,5 | US&#9794; 12-12,5',
			'47' => '| UK 12 | US&#9792; 14 | US&#9794; 13',
			'48' => '| UK 12,5-13 | US&#9794; 13,5-14',
		],
		'Urban Classic' => [
			'women' => [
				'36' => '| UK 3,5 | US&#9792; 5,5',
				'37' => '| UK 4-4,5 | US&#9792; 6-6,5',
				'38' => '| UK 5 | US&#9792; 7',
				'39' => '| UK 5,5-6 | US&#9792; 7,5-8',
				'40' => '| UK 6,5 | US&#9792; 8,5',
				'41' => '| UK 7-7,5 | US&#9792; 9-9,5',
				'42' => '| UK 8-8,5 | US&#9792; 10-10,5',
			],
			'men' => [
				'40' => '| UK 6,5 | US&#9794; 7,5',
				'41' => '| UK 7-7,5 | US&#9794; 8-8,5',
				'42' => '| UK 8-8,5 | US&#9794; 9-9,5',
				'43' => '| UK 9 | US&#9794; 10',
				'44' => '| UK 9,5-10 | US&#9794; 10,5-11',
				'45' => '| UK 10,5 | US&#9794; 11,5',
				'46' => '| UK 11-11,5 | US&#9794; 12-12,5',
			],
		]
	];

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

	/**
	 * @return string
	 */
	public function get_size_structure($size) {
		if(method_exists($this,'getAttributesByIdent') && $size) {
			$oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
			$oCurrency = $oConfig->getActShopCurrencyObject();
			$sDecimalsSeparator = isset($oCurrency->dec) ? $oCurrency->dec : ',';
			$collection = $this->getAttributesByIdent('collection', true);

			$return_value = "";

			if($collection == 'Urban Classic') {
				$gender = $this->getAttributesByIdent('gender', true);

				if($return_value = $this->size_match_array
					[$collection]
					[( strtolower($gender)=='frau'||strtolower($gender)=='women'?'women':'men' )]
					[strtolower($size)]
				) {
					return $return_value;
				}
			} else {
				$return_value = $this->size_match_array['general'][strtolower($size)];
			}

			return str_replace(',', $sDecimalsSeparator, $return_value);
		}

		return '';
	}
}
?>
