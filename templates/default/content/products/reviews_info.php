<?php
/*
  $Id: product_reviews.php 212 2005-10-04 09:55:32 +0200 (Di, 04 Okt 2005) hpdl $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2006 osCommerce

  Released under the GNU General Public License
*/
?>

<h1 style="float: right;"><?php echo $osC_Product->getPriceFormated(true); ?></h1>

<h1><?php echo $osC_Template->getPageTitle() . ($osC_Product->hasModel() ? '<br /><span class="smallText">' . $osC_Product->getModel() . '</span>' : ''); ?></h1>

<?php
  if ($messageStack->size('reviews') > 0) {
    echo $messageStack->output('reviews');
  }

  if ($osC_Product->hasImage()) {
?>

<div style="float: right; text-align: center; padding-left: 30px;">
<?php echo '<a href="' . tep_href_link(FILENAME_PRODUCTS, 'images&' . $osC_Product->getKeyword()) . '" target="_blank" onclick="window.open(\'' . tep_href_link(FILENAME_PRODUCTS, 'images&' . $osC_Product->getKeyword()) . '\', \'popUp\', \'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=600,height=' . ($osC_Image->getHeight('large') + $osC_Image->getHeight('thumbnails') + 100) . '\'); return false;">' . $osC_Image->show($osC_Product->getImage(), $osC_Product->getTitle(), 'hspace="5" vspace="5"') . '</a>'; ?>
<?php echo '<p><a href="' . tep_href_link(basename($_SERVER['PHP_SELF']), tep_get_all_get_params(array('action')) . 'action=buy_now') . '">' . tep_image_button('button_in_cart.gif', $osC_Language->get('button_add_to_cart')) . '</a></p>'; ?>
</div>

<?php
  }

  $Qreviews = osC_Reviews::getEntry($_GET[$osC_Template->getModule()]);
?>

<p><?php echo tep_image(DIR_WS_IMAGES . 'stars_' . $Qreviews->valueInt('reviews_rating') . '.gif', sprintf($osC_Language->get('rating_of_5_stars'), $Qreviews->valueInt('reviews_rating'))) . '&nbsp;' . sprintf($osC_Language->get('reviewed_by'), $Qreviews->valueProtected('customers_name')) . '; ' . osC_DateTime::getLong($Qreviews->value('date_added')); ?></p>

<p><?php echo nl2br(tep_break_string($Qreviews->valueProtected('reviews_text'), 60, '-<br />')); ?></p>

<div class="submitFormButtons">
<?php
  if ($osC_Reviews->is_enabled === true) {
    echo '  <span style="float: right;"><a href="' . tep_href_link(FILENAME_PRODUCTS, 'reviews=new&amp;' . $osC_Product->getKeyword()) . '">' . tep_image_button('button_write_review.gif', $osC_Language->get('button_write_review')) . '</a></span>';
  }

  echo '<a href="' . tep_href_link(FILENAME_PRODUCTS, 'reviews&amp;' . $osC_Product->getKeyword()) . '">' . tep_image_button('button_back.gif', $osC_Language->get('button_back')) . '</a>';
?>
</div>
