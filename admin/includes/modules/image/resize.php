<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/

  class osC_Image_Admin_resize extends osC_Image_Admin {

// Private variables

    var $_code = 'resize',
        $_has_parameters = true;

// Class constructor

    function osC_Image_Admin_resize() {
      global $osC_Language;

      parent::osC_Image_Admin();

      $osC_Language->loadConstants('modules/image/resize.php');

      $this->_title = MODULE_IMAGE_RESIZE;
    }

// Public methods

    function getParameters() {
      $groups = array();
      $groups_ids = array();

      foreach ($this->_groups as $group) {
        if ($group['id'] != '1') {
          $groups[] = array('text' => $group['title'],
                            'id' => $group['id']);

          $groups_ids[] = $group['id'];
        }
      }

      return array(array('key' => MODULE_IMAGE_RESIZE_PROCESS_GROUPS,
                         'field' => osc_draw_pull_down_menu('groups[]', $groups, $groups_ids, 'multiple="multiple" size="5"')),
                   array('key' => MODULE_IMAGE_RESIZE_OVERWRITE_IMAGES,
                         'field' => osc_draw_checkbox_field('overwrite', '1')));
    }

// Private methods

    function _setHeader() {
      $this->_header = array(MODULE_IMAGE_RESIZE_GROUPS,
                             MODULE_IMAGE_RESIZE_NUMBER_OF_IMAGES);
    }

    function _setData() {
      global $osC_Database, $osC_Language;

      $overwrite = false;

      if (isset($_POST['overwrite']) && ($_POST['overwrite'] == '1')) {
        $overwrite = true;
      }

      if (!isset($_POST['groups']) || !is_array($_POST['groups'])) {
        return false;
      }

      $Qoriginals = $osC_Database->query('select image from :table_products_images');
      $Qoriginals->bindTable(':table_products_images', TABLE_PRODUCTS_IMAGES);
      $Qoriginals->execute();

      $counter = array();

      while ($Qoriginals->next()) {
        foreach ($this->_groups as $group) {
          if ( ($group['id'] != '1') && in_array($group['id'], $_POST['groups'])) {
            if (!isset($counter[$group['id']])) {
              $counter[$group['id']] = 0;
            }

            if ( ($overwrite === true) || !file_exists(DIR_FS_CATALOG . DIR_WS_IMAGES . 'products/' . $group['code'] . '/' . $Qoriginals->value('image')) ) {
              $this->resize($Qoriginals->value('image'), $group['id']);

              $counter[$group['id']]++;
            }
          }
        }
      }

      foreach ($counter as $key => $value) {
        $this->_data[] = array($this->_groups[$key]['title'],
                               $value);
      }
    }
  }
?>