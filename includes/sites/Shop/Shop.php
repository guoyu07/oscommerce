<?php
/*
  osCommerce Online Merchant $osCommerce-SIG$
  Copyright (c) 2010 osCommerce (http://www.oscommerce.com)

  This program is free software; you can redistribute it and/or modify
  it under the terms of the GNU General Public License v2 (1991)
  as published by the Free Software Foundation.
*/

  class OSCOM_Shop extends OSCOM_SiteAbstract {
    protected static $_default_application = 'index';
    protected static $_application = 'index';

    public static function initialize() {
      OSCOM::loadConfig();

      if ( strlen(DB_SERVER) < 1 ) {
        osc_redirect(OSCOM::getLink('Setup'));
      }

      include(OSCOM::BASE_DIRECTORY . 'database_tables.php'); // HPDL to remove
      include(OSCOM::BASE_DIRECTORY . 'filenames.php'); // HPDL to remove

      OSCOM_Registry::set('MessageStack', new OSCOM_MessageStack());
      OSCOM_Registry::set('osC_MessageStack', OSCOM_Registry::get('MessageStack')); // HPDL to delete
      OSCOM_Registry::set('Cache', new OSCOM_Cache());
      OSCOM_Registry::set('osC_Cache', OSCOM_Registry::get('Cache')); // HPDL to delete
      OSCOM_Registry::set('Database', OSCOM_Database::initialize());
      OSCOM_Registry::set('osC_Database', OSCOM_Registry::get('Database')); // HPDL to delete

// set the application parameters
      $Qcfg = OSCOM_Registry::get('Database')->query('select configuration_key as cfgKey, configuration_value as cfgValue from :table_configuration');
      $Qcfg->setCache('configuration');
      $Qcfg->execute();

      while ( $Qcfg->next() ) {
        define($Qcfg->value('cfgKey'), $Qcfg->value('cfgValue'));
      }

      $Qcfg->freeResult();

      if ( !empty($_GET) ) {
        $requested_application = osc_sanitize_string(basename(key(array_slice($_GET, 0, 1))));

        if ( $requested_application == OSCOM::getSite() ) {
          $requested_application = osc_sanitize_string(basename(key(array_slice($_GET, 1, 1))));
        }

        if ( !empty($requested_application) ) {
          if ( file_exists(OSCOM::BASE_DIRECTORY . 'sites/Shop/applications/' . $requested_application . '/' . $requested_application . '.php') ) {
            self::$_application = $requested_application;
          }
        }
      }

      OSCOM_Registry::set('osC_Services', new osC_Services());
      OSCOM_Registry::get('osC_Services')->startServices();

      OSCOM_Registry::get('osC_Language')->load(self::$_application);

      OSCOM_Registry::set('Template', OSCOM_Template::setup(self::$_application));
      OSCOM_Registry::set('osC_Template', OSCOM_Registry::get('Template')); // HPDL to remove
    }
  }
?>
