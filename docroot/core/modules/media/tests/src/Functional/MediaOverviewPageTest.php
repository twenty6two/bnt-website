<?php

declare(strict_types=1);

namespace Drupal\Tests\media\Functional;

use Drupal\language\Entity\ConfigurableLanguage;
use Drupal\media\Entity\Media;
use Drupal\user\Entity\Role;
use Drupal\user\RoleInterface;
use Drupal\field\Entity\FieldConfig;
use Drupal\file\Entity\File;
use Drupal\Tests\TestFileCreationTrait;

/**
 * Tests the Media overview page.
 *
 * @group media
 */
class MediaOverviewPageTest extends MediaFunctionalTestBase {

  use TestFileCreationTrait;

  /**
   * {@inheritdoc}
   */
  protected $defaultTheme = 'stark';

  /**
   * {@inheritdoc}
   */
  protected static $modules = ['language'];

  /**
   * {@inheritdoc}
   */
  protected function setUp(): void {
    parent::setUp();
    // Make the site multilingual to have a working language field handler.
    ConfigurableLanguage::create(['id' => 'es', 'title' => 'Spanish title', 'label' => 'Spanish label'])->save();
    $this->drupalLogin($this->nonAdminUser);
  }

  /**
   * Tests that the Media overview page (/admin/content/media).
   */
  public function testMediaOverviewPage(): void {
    $assert_session = $this->assertSession();

    // Check the view exists, is access-restricted, and some defaults are there.
    $this->drupalGet('/admin/content/media');
    $assert_session->statusCodeEquals(403);
    $role = Role::load(RoleInterface::AUTHENTICATED_ID);
    $this->grantPermissions($role, ['access media overview']);
    $this->getSession()->reload();
    $assert_session->statusCodeEquals(200);
    $assert_session->titleEquals('Media | Drupal');
    $assert_session->fieldExists('Media name');
    $assert_session->selectExists('type');
    $assert_session->selectExists('status');
    $assert_session->selectExists('langcode');
    $assert_session->buttonExists('Filter');
    $header = $assert_session->elementExists('css', 'th#view-thumbnail-target-id-table-column');
    $this->assertSame('Thumbnail', $header->getText());
    $header = $assert_session->elementExists('css', 'th#view-name-table-column');
    $this->assertSame('Media name', $header->getText());
    $header = $assert_session->elementExists('css', 'th#view-bundle-table-column');
    $this->assertSame('Type', $header->getText());
    $header = $assert_session->elementExists('css', 'th#view-uid-table-column');
    $this->assertSame('Author', $header->getText());
    $header = $assert_session->elementExists('css', 'th#view-status-table-column');
    $this->assertSame('Status', $header->getText());
    $header = $assert_session->elementExists('css', 'th#view-changed-table-column');
    $this->assertSame('Updated Sort ascending', $header->getText());
    $header = $assert_session->elementExists('css', 'th#view-operations-table-column');
    $this->assertSame('Operations', $header->getText());
    $assert_session->pageTextContains('No media available.');

    // Create some content for the view.
    $media_type1 = $this->createMediaType('test');
    $media_type2 = $this->createMediaType('test');
    $media1 = Media::create([
      'bundle' => $media_type1->id(),
      'name' => 'Media 1',
      'uid' => $this->adminUser->id(),
    ]);
    $media1->save();
    $media2 = Media::create([
      'bundle' => $media_type2->id(),
      'name' => 'Media 2',
      'uid' => $this->adminUser->id(),
      'status' => FALSE,
      'changed' => time() - 50,
    ]);
    $media2->save();
    $media3 = Media::create([
      'bundle' => $media_type1->id(),
      'name' => 'Media 3',
      'uid' => $this->nonAdminUser->id(),
      'changed' => time() - 100,
    ]);
    $media3->save();

    // Make sure the role save below properly invalidates cache tags.
    $this->refreshVariables();

    // Verify the view is now correctly populated. The non-admin user can only
    // view published media.
    $this->grantPermissions($role, [
      'view media',
      'update any media',
      'delete any media',
    ]);
    $this->getSession()->reload();
    $row1 = $assert_session->elementExists('css', 'table tbody tr:nth-child(1)');
    $row2 = $assert_session->elementExists('css', 'table tbody tr:nth-child(2)');

    // Media thumbnails.
    $assert_session->elementExists('css', 'td.views-field-thumbnail__target-id img', $row1);
    $assert_session->elementExists('css', 'td.views-field-thumbnail__target-id img', $row2);

    // Media names.
    $name1 = $assert_session->elementExists('css', 'td.views-field-name a', $row1);
    $this->assertSame($media1->label(), $name1->getText());
    $name2 = $assert_session->elementExists('css', 'td.views-field-name a', $row2);
    $this->assertSame($media3->label(), $name2->getText());
    $assert_session->linkByHrefExists('/media/' . $media1->id());
    $assert_session->linkByHrefExists('/media/' . $media3->id());

    // Media types.
    $type_element1 = $assert_session->elementExists('css', 'td.views-field-bundle', $row1);
    $this->assertSame($media_type1->label(), $type_element1->getText());
    $type_element2 = $assert_session->elementExists('css', 'td.views-field-bundle', $row2);
    $this->assertSame($media_type1->label(), $type_element2->getText());

    // Media authors.
    $author_element1 = $assert_session->elementExists('css', 'td.views-field-uid', $row1);
    $this->assertSame($this->adminUser->getDisplayName(), $author_element1->getText());
    $author_element3 = $assert_session->elementExists('css', 'td.views-field-uid', $row2);
    $this->assertSame($this->nonAdminUser->getDisplayName(), $author_element3->getText());

    // Media publishing status.
    $status_element1 = $assert_session->elementExists('css', 'td.views-field-status', $row1);
    $this->assertSame('Published', $status_element1->getText());
    $status_element3 = $assert_session->elementExists('css', 'td.views-field-status', $row2);
    $this->assertSame('Published', $status_element3->getText());

    // Timestamp.
    $expected = \Drupal::service('date.formatter')->format($media1->getChangedTime(), 'short');
    $changed_element1 = $assert_session->elementExists('css', 'td.views-field-changed', $row1);
    $this->assertSame($expected, $changed_element1->getText());

    // Operations.
    $assert_session->elementExists('css', 'td.views-field-operations li a:contains("Edit")', $row1);
    $assert_session->linkByHrefExists('/media/' . $media1->id() . '/edit');
    $assert_session->elementExists('css', 'td.views-field-operations li a:contains("Delete")', $row1);
    $assert_session->linkByHrefExists('/media/' . $media1->id() . '/delete');

    // Make sure the role save below properly invalidates cache tags.
    $this->refreshVariables();

    // Make the user the owner of the unpublished media item and assert the
    // media item is only visible with the 'view own unpublished media'
    // permission.
    $media2->setOwner($this->nonAdminUser)->save();
    $this->getSession()->reload();
    $assert_session->pageTextNotContains($media2->label());
    $role->grantPermission('view own unpublished media')->save();
    $this->getSession()->reload();
    $row = $assert_session->elementExists('css', 'table tbody tr:nth-child(2)');
    $name = $assert_session->elementExists('css', 'td.views-field-name a', $row);
    $this->assertSame($media2->label(), $name->getText());
    $status_element = $assert_session->elementExists('css', 'td.views-field-status', $row);
    $this->assertSame('Unpublished', $status_element->getText());

    // Assert the admin user can always view all media.
    $this->drupalLogin($this->adminUser);
    $this->drupalGet('/admin/content/media');
    $row1 = $assert_session->elementExists('css', 'table tbody tr:nth-child(1)');
    $row2 = $assert_session->elementExists('css', 'table tbody tr:nth-child(2)');
    $row3 = $assert_session->elementExists('css', 'table tbody tr:nth-child(3)');
    $name1 = $assert_session->elementExists('css', 'td.views-field-name a', $row1);
    $this->assertSame($media1->label(), $name1->getText());
    $name2 = $assert_session->elementExists('css', 'td.views-field-name a', $row2);
    $this->assertSame($media2->label(), $name2->getText());
    $name3 = $assert_session->elementExists('css', 'td.views-field-name a', $row3);
    $this->assertSame($media3->label(), $name3->getText());
    $assert_session->linkByHrefExists('/media/' . $media1->id());
    $assert_session->linkByHrefExists('/media/' . $media2->id());
    $assert_session->linkByHrefExists('/media/' . $media3->id());
  }

  /**
   * Tests the display of the alt attribute.
   */
  public function testImageAltTextDisplay(): void {
    $this->drupalLogin($this->adminUser);
    $media_type = $this->createMediaType('image');
    $media_type_id = $media_type->id();
    $media_type->setFieldMap(['name' => 'name']);
    $media_type->save();

    /** @var \Drupal\field\FieldConfigInterface $field */
    $field = FieldConfig::load("media.$media_type_id.field_media_image");
    $settings = $field->getSettings();
    $settings['alt_field'] = TRUE;
    $settings['alt_field_required'] = FALSE;
    $field->set('settings', $settings);
    $field->save();

    $file = File::create([
      'uri' => $this->getTestFiles('image')[0]->uri,
    ]);
    $file->save();

    // Set the alt text to an empty string.
    $media = Media::create([
      'name' => 'Custom name',
      'bundle' => $media_type_id,
      'field_media_image' => [
        [
          'target_id' => $file->id(),
          'alt' => '',
          'title' => 'default title',
        ],
      ],
    ]);
    $media->save();

    $this->drupalGet('/admin/content/media');

    // Confirm that the alt text attribute is present.
    $assert_session = $this->assertSession();
    $element = $assert_session->elementAttributeExists('css', 'td.views-field-thumbnail__target-id img', 'alt');
    $this->assertSame('', (string) $element->getAttribute('alt'));

  }

}
