<?php
/*
  $Id: index.php 199 2005-09-22 17:56:13 +0200 (Do, 22 Sep 2005) hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/
?>

<?php echo tep_image(DIR_WS_IMAGES . $osC_Template->getPageImage(), $osC_Template->getPageTitle(), HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT, 'class="pageIcon"'); ?>

<h1><?php echo $osC_Template->getPageTitle(); ?></h1>

<table border="0" width="100%" cellspacing="0" cellpadding="2">

<?php
  $Qproducts = osC_Product::getListingNew();

  if ($Qproducts->numberOfRows() > 0) {
    while ($Qproducts->next()) {
      if ($osC_Services->isStarted('specials') && ($new_price = $osC_Specials->getPrice($Qproducts->valueInt('products_id')))) {
        $products_price = '<s>' . $osC_Currencies->displayPrice($Qproducts->value('products_price'), $Qproducts->valueInt('products_tax_class_id')) . '</s> <span class="productSpecialPrice">' . $osC_Currencies->displayPrice($new_price, $Qproducts->valueInt('products_tax_class_id')) . '</span>';
      } else {
        $products_price = $osC_Currencies->displayPrice($Qproducts->value('products_price'), $Qproducts->valueInt('products_tax_class_id'));
      }
?>

  <tr>
    <td width="<?php echo SMALL_IMAGE_WIDTH + 10; ?>" valign="top" class="main">

<?php
      if (osc_empty($Qproducts->value('image')) === false) {
        echo '<a href="' . tep_href_link(FILENAME_PRODUCTS, $Qproducts->value('products_keyword')) . '">' . $osC_Image->show($Qproducts->value('image'), $Qproducts->value('products_name')) . '</a>';
      }
?>

    </td>
    <td valign="top" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS, $Qproducts->value('products_keyword')) . '"><b><u>' . $Qproducts->value('products_name') . '</u></b></a><br />' . $osC_Language->get('date_added') . ' ' . osC_DateTime::getLong($Qproducts->value('products_date_added')) . '<br />' . $osC_Language->get('manufacturer') . ' ' . $Qproducts->value('manufacturers_name') . '<br /><br />' . $osC_Language->get('price') . ' ' . $products_price; ?></td>
    <td align="right" valign="middle" class="main"><?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS, tep_get_all_get_params(array('action')) . 'action=buy_now&amp;products_id=' . $Qproducts->value('products_id')) . '">' . tep_image_button('button_in_cart.gif', $osC_Language->get('button_add_to_cart')) . '</a>'; ?></td>
  </tr>
  <tr>
    <td colspan="3"><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>

<?php
    }
  } else {
?>

  <tr>
    <td class="main"><?php echo $osC_Language->get('no_new_products'); ?></td>
  </tr>
  <tr>
    <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
  </tr>

<?php
  }
?>

</table>

<div class="listingPageLinks">
  <span style="float: right;"><?php echo $Qproducts->displayBatchLinksPullDown('page', 'new'); ?></span>

  <?php echo $Qproducts->displayBatchLinksTotal($osC_Language->get('result_set_number_of_products')); ?>
</div>
