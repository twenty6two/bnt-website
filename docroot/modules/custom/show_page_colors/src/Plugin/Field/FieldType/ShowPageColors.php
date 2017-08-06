<?php

/**
 * @file
 * Contains \Drupal\show_page_colors\Plugin\Field\FieldType\ShowPageColors.
 */

namespace Drupal\show_page_colors\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Plugin implementation of the 'show_page_colors' field type.
 *
 * @FieldType (
 *   id = "show_page_colors",
 *   label = @Translation("Show Page Colors"),
 *   description = @Translation("Stores the custom colors for a Show page."),
 *   default_widget = "show_page_colors",
 *   default_formatter = "show_page_colors"
 * )
 */
class ShowPageColors extends FieldItemBase {
  /**
   * {@inheritdoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return array(
      'columns' => array(
        'left_gradient_color' => array(
          'type' => 'text',
          'size' => 'small',
          'not null' => TRUE,
        ),
        'right_gradient_color' => array(
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
    $value_left_gradient_color = $this->get('left_gradient_color')->getValue();
    $value_right_gradient_color = $this->get('right_gradient_color')->getValue();

    return (empty($value_left_gradient_color) && empty($value_right_gradient_color));
  }

  /**
   * {@inheritdoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['left_gradient_color'] = DataDefinition::create('string')
      ->setLabel(t('Left Gradient Color'))
      ->setDescription(t('Color value for the left side of the linear gradient'));

    $properties['right_gradient_color'] = DataDefinition::create('string')
      ->setLabel(t('Right Gradient Color'))
      ->setDescription(t('Color value for the right side of the linear gradient'));

    return $properties;
  }
}
