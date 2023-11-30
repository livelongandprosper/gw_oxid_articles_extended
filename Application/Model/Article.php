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
	 * @var bool
	 */
	public $method_exists_getPreparedBasePrice = true;

	/**
	 * Should store article list with articles of same color
	 * @var null
	 */
	public $oColorArticlesList = null;

	/**
	 * TODO: maybe move this to module config because this is like a constant
	 * @var array
	 */
	private $size_match_string_array = [
		'general' => [ // trainer etc.
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
			'49' => '| UK 13,5-14 | US&#9794; 14,5-15',
			'50' => '| UK 14,5-15 | US&#9794; 15,5-16',
			'51' => '| UK 15,5 | US&#9794; 16,5',
			'52' => '| UK 16 | US&#9794; 17',
			'53' => '| UK 16,5-17 | US&#9794; 17,5',
			'54' => '| UK 17,5 | US&#9794; 18-18,5',
			'55' => '| UK 18 | US&#9794; 19',
		],
		'Urban Classics' => [
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
	private $size_match_array = [
		'general' => [ // trainer etc.
			'head' => array(
				'UK',
				'US (WOMEN)',
				'US (MEN)',
			),
			'36' => array(
				'3,5', // UK
				'5,5', // US WOMEN
				'4,5', // US MEN
			),
			'37' => array(
				'4-4,5', // UK
				'6-6,5', // US WOMEN
				'5-5,5', // US MEN
			),
			'38' => array(
				'5', // UK
				'7', // US WOMEN
				'6', // US MEN
			),
			'39' => array(
				'5,5-6', // UK
				'7,5-8', // US WOMEN
				'7,5', // US MEN
			),
			'40' => array(
				'6,5', // UK
				'8,5', // US WOMEN
				'6,5-7', // US MEN
			),
			'41' => array(
				'7-7,5', // UK
				'9-9,5', // US WOMEN
				'8-8,5', // US MEN
			),
			'42' => array(
				'8-8,5', // UK
				'10-10,5', // US WOMEN
				'9-9,5', // US MEN
			),
			'43' => array(
				'9', // UK
				'11', // US WOMEN
				'10', // US MEN
			),
			'44' => array(
				'9,5-10', // UK
				'11,5-12', // US WOMEN
				'10,5-11', // US MEN
			),
			'45' => array(
				'10,5', // UK
				'12,5', // US WOMEN
				'11,5', // US MEN
			),
			'46' => array(
				'11-11,5', // UK
				'13-13,5', // US WOMEN
				'12-12,5', // US MEN
			),
			'47' => array(
				'12', // UK
				'14', // US WOMEN
				'13', // US MEN
			),
			'48' => array(
				'12,5-13', // UK
				'-', // US WOMEN
				'13,5-14', // US MEN

			),
			'49' => array(
				'13,5-14', // UK
				'-', // US WOMEN
				'14,5-15', // US MEN

			),
			'50' => array(
				'14,5-15', // UK
				'-', // US WOMEN
				'15,5-16', // US MEN

			),
			'51' => array(
				'15,5', // UK
				'-', // US WOMEN
				'16,5', // US MEN

			),
			'52' => array(
				'16', // UK
				'-', // US WOMEN
				'17', // US MEN

			),
			'53' => array(
				'16,5-17', // UK
				'-', // US WOMEN
				'17,5-18', // US MEN

			),
			'54' => array(
				'17,5', // UK
				'-', // US WOMEN
				'18,5', // US MEN

			),
			'55' => array(
				'18', // UK
				'-', // US WOMEN
				'19', // US MEN

			),
		],
		'Urban Classics' => [
			'women' => [
				'head' => array(
					'UK',
					'US (WOMEN)',
				),
				'36' => array(
					'3,5', // UK
					'5,5', // US WOMEN
				),
				'37' => array(
					'4-4,5', // UK
					'6-6,5', // US WOMEN
				),
				'38' => array(
					'5', // UK
					'7', // US WOMEN
				),
				'39' => array(
					'5,5-6', // UK
					'7,5-8', // US WOMEN
				),
				'40' => array(
					'6,5', // UK
					'8,5', // US WOMEN
				),
				'41' => array(
					'7-7,5', // UK
					'9-9,5', // US WOMEN
				),
				'42' => array(
					'8-8,5', // UK
					'10-10,5', // US WOMEN
				),
			],
			'men' => [
				'head' => array(
					'UK',
					'US (MEN)',
				),
				'40' => array(
					'6,5', // UK
					'7,5', // US MEN
				),
				'41' => array(
					'7-7,5', // UK
					'8-8,5', // US MEN
				),
				'42' => array(
					'8-8,5', // UK
					'9-9,5', // US MEN
				),
				'43' => array(
					'9', // UK
					'10', // US MEN
				),
				'44' => array(
					'9,5-10', // UK
					'10,5-11', // US MEN
				),
				'45' => array(
					'10,5', // UK
					'11,5', // US MEN
				),
				'46' => array(
					'11-11,5', // UK
					'12-12,5', // US MEN
				),
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
	public function get_size_structure($size, $return_array=false) {
		if(method_exists($this,'getAttributesByIdent') && $size) {
			$oConfig = \OxidEsales\Eshop\Core\Registry::getConfig();
			$oCurrency = $oConfig->getActShopCurrencyObject();
			$sDecimalsSeparator = isset($oCurrency->dec) ? $oCurrency->dec : ',';
			$collection = $this->getAttributesByIdent('collection', true);

			$return_value = "";

			if($collection == 'Urban Classics') {
				$gender = $this->getAttributesByIdent('gender', true);

				if(!$return_array) {
					if($return_value = $this->size_match_string_array
						[$collection]
						[( strtolower($gender)=='frau'||strtolower($gender)=='women'?'women':'men' )]
						[strtolower($size)]
					) {
						return $return_value;
					}
				} else {
					if($return_value = $this->size_match_array
						[$collection]
						[( strtolower($gender)=='frau'||strtolower($gender)=='women'?'women':'men' )]
						[strtolower($size)]
					) {
						return $return_value;
					}

				}
			} else {
				if(!$return_array) {
					$return_value = $this->size_match_string_array['general'][strtolower($size)];
				} else {
					$return_value = $this->size_match_array['general'][strtolower($size)];
				}
			}

			if(!is_array($return_value)) {
				return str_replace(',', $sDecimalsSeparator, $return_value);
			} else {
				return $return_value;
			}
		}

		return '';
	}

	/**
	 * Return the BasePrice with correct calculated vat
	 * @return double
	 */
	public function getPreparedBasePrice() {
		$show_netto = null; // null = don't show netto price
		$oVatSelector = \OxidEsales\Eshop\Core\Registry::get(\OxidEsales\Eshop\Application\Model\VatSelector::class);
		if (($dVat = $oVatSelector->getArticleUserVat($this)) !== false) {
			$show_netto = 1;
		}
		return $this->_preparePrice($this->getBasePrice(), $this->getArticleVat(), $show_netto);
	}

	/**
	 * Get suitable products
	 */
	public function getSuitableProducts() {
		$oCrosslist = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);
		if($oParent = $this->getParentArticle()) {
			$oCrosslist->loadSuitableProducts($oParent);
		} else {
			$oCrosslist->loadSuitableProducts($this);
		}
		if ($oCrosslist->count()) {
			return $oCrosslist;
		}

		return null;
	}

	/**
	 * Get accessory articles
	 */
	public function getAccessories() {
		$oxid = $this->oxarticles__oxparentid->value;
		if(!$oxid) {
			$oxid = $this->getId();
		}
		$oCrosslist = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);

		// load manually assigned accessories
		$oCrosslist->loadArticleAccessoires($oxid);

		if ($oCrosslist->count()) {
			return $oCrosslist;
		}

		return null;
	}

	/**
	 * Get similar articles
	 */
	public function getSimilarProducts() {
		$oCrosslist = oxNew(\OxidEsales\Eshop\Application\Model\ArticleList::class);

		// load manually assigned accessories
		if($oParent = $this->getParentArticle()) {
			$oCrosslist->loadSimilarArticlesByArtNrRelation($oParent);
		} else {
			$oCrosslist->loadSimilarArticlesByArtNrRelation($this);
		}

		if ($oCrosslist->count()) {
			return $oCrosslist;
		}

		return null;
	}

	/**
	 * Genereates the base color name according to
	 * @return string
	 */
	public function getBaseColorName() {
		$baseLanguageId = \OxidEsales\Eshop\Core\Registry::getLang()->getBaseLanguage();
		$myConfig = $this->getConfig();
		$article_number_db_field = $myConfig->getConfigParam('gw_oxid_articles_extended_model_dbfield');
		$model_number_separator = $myConfig->getConfigParam('gw_oxid_articles_extended_model_separator');
		$article_number = $this->{'oxarticles__'.$article_number_db_field}->value;
		$separator_string_position = strripos($article_number, $model_number_separator);
		$colorCode = (string)substr( $article_number, $separator_string_position + 2 + strlen($model_number_separator), 2 );
		return \OxidEsales\Eshop\Core\Registry::getLang()->translateString('ZEHA_BASE_COLOR_'.$colorCode, $baseLanguageId, false);
	}

}
?>
