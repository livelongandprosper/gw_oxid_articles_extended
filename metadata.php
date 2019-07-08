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
$sMetadataVersion = '2'; // see https://docs.oxid-esales.com/developer/en/6.0/modules/skeleton/metadataphp/version20.html

/**
 * Module information
 */
$aModule = array(
    'id'           => 'gw_oxid_articles_extended',
    'title'        => 'Erweiterte Artikel',
//     'thumbnail'    => 'out/admin/img/logo.jpg',
    'version'      => '1.0.1',
    'author'       => 'Gregor Wendland',
    'email'		   => 'kontakt@gewend.de',
    'url'		   => 'https://www.gewend.de',
    'description'  => array(
    	'de'		=> 'Erweitert die Möglichkeiten von OXID eShop Artikeln
							<ul>
								<li>Fügt der Article Klasse die Funktion variant_has_stock($varselect) hinzu</li>
								<li>Berechnet die Schuhgrößen US und UK anhand der EU Schuhgröße (Voraussetzung: des Modul gw_oxid_attributes_extended muss installiert sein)</li>
							</ul>
						',
    ),
    'extend'       => array(
		OxidEsales\Eshop\Application\Model\Article::class => gw\gw_oxid_articles_extended\Application\Model\Article::class,

    ),
    'settings'		=> array(
    ),
    'files'			=> array(
    ),
	'blocks' => array(
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
