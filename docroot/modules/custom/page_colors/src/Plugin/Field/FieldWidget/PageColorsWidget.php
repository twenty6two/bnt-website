<?php

/**
 * @file
 * Contains \Drupal\page_colors\Plugin\Field\FieldWidget\PageColorsWidget.
 */

namespace Drupal\page_colors\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Field\WidgetInterface;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'page_colors' widget.
 *
 * @FieldWidget (
 *   id = "page_colors",
 *   label = @Translation("Page Colors widget"),
 *   field_types = {
 *     "page_colors"
 *   }
 * )
 */
class PageColorsWidget extends WidgetBase implements WidgetInterface {
  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['logo_color'] = array(
      '#type' => 'textfield',
      '#title' => t('Logo Color'),
      '#default_value' => isset($items[$delta]->logo_color) ? $items[$delta]->logo_color : NULL,
      '#size' => 25,
    );
    $element['page_element_color'] = array(
      '#type' => 'textfield',
      '#title' => t('Page Element Color'),
      '#default_value' => isset($items[$delta]->page_element_color) ? $items[$delta]->page_element_color : NULL,
      '#size' => 25,
    );

    return $element;
  }
}
