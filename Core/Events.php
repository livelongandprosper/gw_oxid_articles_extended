<?php
	namespace gw\gw_oxid_articles_extended\Core;

	use OxidEsales\Eshop\Core\DbMetaDataHandler;
	use OxidEsales\Eshop\Core\DatabaseProvider;

	class Events {
		/**
		 * add_db_key function.
		 *
		 * @access private
		 * @static
		 * @param mixed $table_name
		 * @param mixed $keyname
		 * @param mixed $column_names
		 * @param bool $unique (default: false)
		 * @return void
		 */
		private static function add_db_key($table_name, $keyname, $column_names, $unique=false) {
			// create key
			if($unique) {
				DatabaseProvider::getDb()->execute("
					ALTER TABLE  `$table_name` ADD UNIQUE  `$keyname` (  `".implode('`,`', $column_names)."` );
				");
			} else {
				DatabaseProvider::getDb()->execute("
					ALTER TABLE  `$table_name` ADD INDEX `$keyname` (  `".implode('`,`', $column_names)."` ) ;
				");
			}
		}

		/**
		 * @param $table_name
		 * @param $column_name
		 * @param $datatype
		 */
		private static function add_db_field($table_name, $column_name, $datatype) {
			$gw_head_exists = DatabaseProvider::getDb()->GetOne("SHOW COLUMNS FROM `$table_name` LIKE '$column_name'");
			if(!$gw_head_exists) {
				DatabaseProvider::getDb()->execute(
					"ALTER TABLE `$table_name` ADD `$column_name` $datatype;"
				);
			}
		}


		public static function onActivate() {
			try {
				self::add_db_key('oxarticles', 'gw_OXVARSELECT', array("OXVARSELECT"));
				self::add_db_key('oxarticles', 'gw_OXVARSELECT_1', array("OXVARSELECT_1"));

				self::add_db_key('oxarticles', 'gw_OXVARSELECT_OXPARENT', array("OXVARSELECT","OXPARENTID"));
				self::add_db_key('oxarticles', 'gw_OXVARSELECT_1_OXPARENT', array("OXVARSELECT_1","OXPARENTID"));

				self::add_db_field('oxcategories', 'gw_frontend_title', "VARCHAR(255) NOT NULL COMMENT 'alternative page title'");
				self::add_db_field('oxcategories', 'gw_frontend_title_1', "VARCHAR(255) NOT NULL COMMENT 'alternative page title secondary lang'");

			}	catch (OxidEsales\Eshop\Core\Exception\DatabaseErrorException $e) {
				// do nothing... php will ignore and continue
			}

			$oDbMetaDataHandler = oxNew(DbMetaDataHandler::class);
			$oDbMetaDataHandler->updateViews();
		}
		public static function onDeactivate() {
			$config = \OxidEsales\Eshop\Core\Registry::getConfig();
			DatabaseProvider::getDb()->execute("DELETE FROM oxtplblocks WHERE oxshopid='".$config->getShopId()."' AND oxmodule='gw_oxid_articles_extended';");
			DatabaseProvider::getDb()->execute("ALTER TABLE oxarticles DROP INDEX gw_OXVARSELECT;");
			DatabaseProvider::getDb()->execute("ALTER TABLE oxarticles DROP INDEX gw_OXVARSELECT_1;");
			DatabaseProvider::getDb()->execute("ALTER TABLE oxarticles DROP INDEX gw_OXVARSELECT_OXPARENT;");
			DatabaseProvider::getDb()->execute("ALTER TABLE oxarticles DROP INDEX gw_OXVARSELECT_1_OXPARENT;");

			exec( "rm -f " .$config->getConfigParam( 'sCompileDir' )."/smarty/*" );
			exec( "rm -Rf " .$config->getConfigParam( 'sCompileDir' )."/*" );
			$oDbMetaDataHandler = oxNew(DbMetaDataHandler::class);
			$oDbMetaDataHandler->updateViews();
		}
	}
?>
