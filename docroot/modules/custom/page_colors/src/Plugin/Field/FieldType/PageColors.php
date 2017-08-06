<?php

/**
 * @file
 * Contains \Drupal\page_colors\Plugin\Field\FieldType\PageColors.
 */

namespace Drupal\page_colors\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'page_colors' field type.
 *
 * @FieldType (
 *   id = "page_colors",
 *   label = @Translation("Page Colors"),
 *   description = @Translation("Stores the custom colors for a page."),
 *   default_widget = "page_colors",
 *   default_formatter = "page_colors"
 * )
 */
class PageColors extends FieldItemBase {
  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'logo_color' => array(
          'type' => 'text',
          'size' => 'small',
          'not null' => TRUE,
        ),
        'page_element_color' => array(
          'type' => 'text',
          'size' => 'small',
          'not null' => TRUE,
        ),
      ),
    );
  }

  /**
   * {@inheritdoc}
  */
  public function isEmpty() {
    $value_logo_color = $this->get('logo_color')->getValue();
    $value_page_element_color = $this->get('page_element_color')->getValue();

    return (empty($value_logo_color) && empty($value_page_element_color));
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['logo_color'] = DataDefinition::create('string')
      ->setLabel(t('Logo Color'))
      ->setDescription(t('Accent color value for the main company logo'));

    $properties['page_element_color'] = DataDefinition::create('string')
      ->setLabel(t('Page Element Color'))
      ->setDescription(t('Color value for the designated page elements'));

    return $properties;
  }
}
