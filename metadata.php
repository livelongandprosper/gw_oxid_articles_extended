<?php
/**
 * @abstract
 * @author 	Gregor Wendland <gregor@gewend.de>
 * @copyright Copyright (c) 2019, Gregor Wendland
 * @package gw
 * @version 2019-07-01
 */

/**
 * Metadata version
 */
$sMetadataVersion = '2.0'; // see https://docs.oxid-esales.com/developer/en/6.0/modules/skeleton/metadataphp/version20.html

/**
 * Module information
 */
$aModule = array(
    'id'           => 'gw_oxid_articles_extended',
    'title'        => 'Erweiterte Artikel',
//     'thumbnail'    => 'out/admin/img/logo.jpg',
    'version'      => '1.2',
    'author'       => 'Gregor Wendland',
    'email'		   => 'hello@gregor-wendland.com',
    'url'		   => 'https://gregor-wendland.com',
    'description'  => array(
    	'de'		=> 'Erweitert die Möglichkeiten von OXID eShop Artikeln
							<ul>
								<li>Fügt der Article Klasse die Funktion variant_has_stock($varselect) hinzu</li>
								<li>Berechnet die Schuhgrößen US und UK anhand der EU Schuhgröße (Voraussetzung: des Modul gw_oxid_attributes_extended muss installiert sein)</li>
								<li>Ermöglicht über die Funktion Article::getRelatedProducts() alle Crossselling-Artikel eines Artikels in einer Liste zu erhalten</li>
								<li>Ermöglicht passende Artikel über ein Matching über die Artikelnummer zu ermitteln</li>
								<li>Ermöglicht einen Artikel über ViewConfig zu laden</li>
								<li>Ermöglicht die Pflege eines alternativ im Frontend angezeigten Titels</li>
							</ul>
						',
    ),
    'extend'       => array(
		OxidEsales\Eshop\Core\ViewConfig::class => gw\gw_oxid_articles_extended\Core\ViewConfig::class,
		OxidEsales\Eshop\Application\Model\Article::class => gw\gw_oxid_articles_extended\Application\Model\Article::class,
		OxidEsales\Eshop\Application\Model\ArticleList::class => gw\gw_oxid_articles_extended\Application\Model\ArticleList::class,
		OxidEsales\Eshop\Application\Model\Category::class => gw\gw_oxid_articles_extended\Application\Model\Category::class,
		OxidEsales\Eshop\Application\Controller\ArticleListController::class => gw\gw_oxid_articles_extended\Application\Controller\ArticleListController::class,
    ),
    'settings'		=> array(
		array('group' => 'gw_oxid_articles_extended_crossselling', 'name' => 'gw_oxid_articles_extended_model_dbfield', 'type' => 'str', 'value' => 'oxmpn'),
		array('group' => 'gw_oxid_articles_extended_crossselling', 'name' => 'gw_oxid_articles_extended_model_separator', 'type' => 'str', 'value' => '.'),
		array('group' => 'gw_oxid_articles_extended_crossselling', 'name' => 'gw_oxid_articles_extended_similar_suffix_length', 'type' => 'str', 'value' => '0'),
		array('group' => 'gw_oxid_articles_extended_crossselling', 'name' => 'gw_oxid_articles_extended_accessories_category', 'type' => 'str', 'value' => ''),
    ),
	'blocks' => array(
		// backend
		array(
			'template' => 'include/category_main_form.tpl',
			'block' => 'admin_category_main_form',
			'file' => 'Application/views/blocks/admin/admin_category_main_form.tpl'
		),
	),
	'events'       => array(
		'onActivate'   => '\gw\gw_oxid_articles_extended\Core\Events::onActivate',
		'onDeactivate' => '\gw\gw_oxid_articles_extended\Core\Events::onDeactivate'
	),
	'controllers'  => [
	],
	'templates' => [
	]
);
?>
