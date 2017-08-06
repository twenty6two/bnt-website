<?php

/**
 * @file
 * Contains \Drupal\show_page_colors\Plugin\Field\FieldWidget\ShowPageColorsWidget.
 */

namespace Drupal\show_page_colors\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'show_page_colors' widget.
 *
 * @FieldWidget (
 *   id = "show_page_colors",
 *   label = @Translation("Show Page Colors widget"),
 *   field_types = {
 *     "show_page_colors"
 *   }
 * )
 */
class ShowPageColorsWidget extends WidgetBase implements WidgetInterface {
  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['left_gradient_color'] = array(
      '#type' => 'textfield',
      '#title' => t('Left Gradient Color'),
      '#default_value' => isset($items[$delta]->left_gradient_color) ? $items[$delta]->left_gradient_color : NULL,
      '#size' => 25,
    );
    $element['right_gradient_color'] = array(
      '#type' => 'textfield',
      '#title' => t('Right Gradient Color'),
      '#default_value' => isset($items[$delta]->right_gradient_color) ? $items[$delta]->right_gradient_color : NULL,
      '#size' => 25,
    );

    return $element;
  }
}
