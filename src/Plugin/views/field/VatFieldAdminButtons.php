<?php
/**
 * @file
 * Definition of
 * Drupal\views_admintools\Plugin\views\field\VatFieldAdminButtons
 */

namespace Drupal\views_admintools\Plugin\views\field;

use Drupal\Core\Form\FormStateInterface;
use Drupal\user\Entity\Role;
use Drupal\views\Plugin\views\field\FieldPluginBase;
use Drupal\Core\Url;
use Drupal\views\ResultRow;

/**
 * Field handler to add Edit and Delete Buttons.
 *
 * @ingroup views_field_handlers
 *
 * @ViewsField("vat_field_admin_buttons")
 */
class VatFieldAdminButtons extends FieldPluginBase
{

  /**
   * @{inheritdoc}
   */
  public function query()
  {
    // Leave empty to avoid a query on this field.
  }

  /**
   * Define the available options
   *
   * @return array
   */
  protected function defineOptions()
  {
    $options = parent::defineOptions();
    $options['button_edit'] = ['default' => TRUE];
    $options['button_delete'] = ['default' => TRUE];

    $options['button_label'] = ['default' => FALSE];
    $options['button_icon'] = ['default' => TRUE];

    $options['show_as'] = ['default' => 'button'];
    $options['button_class'] = ['default' => FALSE];

    $options['destination_options'] = ['default' => 1]; // active View
    $options['destination_other'] = ['default' => ''];
    $options['destination_path'] = ['default' => ''];

    //  Modal
    $options['modal'] = ['default' => TRUE];
    $options['modal_width'] = ['default' => 800]; //
    $options['modal_height'] = ['default' => '90%']; //

    // Icon Set
    $options['icon_set'] = ['default' => 0];    // Automatic
    $options['icon_size'] = ['default' => 1];  // normal

    // Icon Names for Iconfonts
    $options['icon_edit'] = ['default' => 'edit'];  // normal
    $options['icon_delete'] = ['default' => 'trash'];  // normal

    // Role
    $role_objects = Role::loadMultiple();

    foreach ($role_objects as $role) {
      $option_name = 'roles-' . $role->id();
      $options[$option_name]['default'] = false;  // normal
    }

    return $options;
  }

  /**
   * Provide the options form.
   */
  public function buildOptionsForm(&$form, FormStateInterface $form_state)
  {
    // Show Buttons
    // ------------------------------

    // Fieldset Start
    $form['group_buttons_start'] = [
      '#type' => 'html_tag',
      '#tag' => 'label',
      '#value' => $this->t('Show Buttons'),
      '#prefix' => '<fieldset class="vat-options-group">',
    ];


    $form['button_edit'] = [
      '#title' => $this->t('Edit'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['button_edit'],
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',
    ];

    $form['button_delete'] = [
      '#title' => $this->t('Delete'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['button_delete'],
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',

    ];

    // Fieldset End
    $form['group_buttons_end'] = [
      '#type' => 'html_tag',
      '#tag' => 'span',
      '#suffix' => '</fieldset>',
    ];

    // Destination
    // ------------------------------

    // Fieldset Start
    $form['group_destination_start'] = [
      '#type' => 'html_tag',
      '#tag' => 'label',
      '#value' => $this->t('Chose Destination'),
      '#prefix' => '<fieldset class="vat-options-group">',
    ];


    $options_destination = [
      'Show Content',
      'this view',
      '<content_type>-admin',
      'other path',
    ];

    $form['destination_options'] = [
      '#title' => $this->t('Chose destination after save'),
      '#type' => 'select',
      '#default_value' => $this->options['destination_options'],
      '#options' => $options_destination,
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',

    ];

    $form['destination_path'] = [
      '#title' => $this->t('Other destination path'),
      '#type' => 'textfield',
      '#default_value' => $this->options['destination_path'],
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',
    ];

    // Fieldset End
    $form['group_destination_end'] = [
      '#type' => 'html_tag',
      '#tag' => 'span',
      '#suffix' => '</fieldset>',
    ];

    // Design
    // ------------------------------


    // Fieldset Start
    $form['group_design_start'] = [
      '#type' => 'html_tag',
      '#tag' => 'label',
      '#value' => $this->t('Design:'),
      '#prefix' => '<fieldset class="vat-options-group">',
    ];


    $form['button_label'] = [
      '#title' => $this->t('label'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['button_label'],
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',
    ];

    $form['button_icon'] = [
      '#title' => $this->t('icon'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['button_icon'],
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',
    ];


    // Link or Button

    $options = ['button', 'link'];
    $form['show_as'] = [
      '#title' => $this->t('Display as'),
      '#type' => 'select',
      '#default_value' => $this->options['show_as'],
      '#options' => $options,
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',
    ];

    // Icon Set

    $options_icon_set = [
      'automatic',
      'Font Awesome',
      'Twitter Bootstrap',
      'Drupal / jQuery Ui',
    ];

    $form['icon_set'] = [
      '#title' => $this->t('Icon Set'),
      '#type' => 'select',
      '#default_value' => $this->options['icon_set'],
      '#options' => $options_icon_set,
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',
    ];


    // Icon Size
    // ------------------------------

    $options_icon_size = [
      'Small',
      'Normal',
      'Large',
    ];

    $form['icon_size'] = [
      '#title' => $this->t('Icon Size'),
      '#type' => 'select',
      '#default_value' => $this->options['icon_size'],
      '#options' => $options_icon_size,
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',
    ];

    $form['group_design_end'] = [
      '#type' => 'html_tag',
      '#tag' => 'span',
      '#suffix' => '</fieldset>',
    ];


    // Icon Names for Iconfonts
    // ------------------------------

    // Title
    $form['group_iconfonts_start'] = [
      '#type' => 'html_tag',
      '#tag' => 'label',
      '#value' => $this->t('Icon Name (without prefix)'),
      '#prefix' => '<fieldset class="vat-options-group">',
    ];


    // edit
    $form['icon_edit'] = [
      '#title' => $this->t('edit'),
      '#type' => 'textfield',
      '#attributes' => array('maxlength' => 10, 'size' => 10),
      '#default_value' => $this->options['icon_edit'],
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',
    ];

    // delete
    $form['icon_delete'] = [
      '#title' => $this->t('delete'),
      '#type' => 'textfield',
      '#attributes' => array('maxlength' => 10, 'size' => 10),
      '#default_value' => $this->options['icon_delete'],
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',
    ];

    $form['group_iconfonts_end'] = [
      '#type' => 'html_tag',
      '#tag' => 'span',
      '#suffix' => '</fieldset>',
    ];


    // Modal
    // ------------------------------


    // Fieldset Start
    $form['group_modal_start'] = [
      '#type' => 'html_tag',
      '#tag' => 'span',
      '#value' => '',
      '#prefix' => '<fieldset class="vat-options-group">',
    ];


    // Modal
    $form['modal'] = [
      '#title' => $this->t('Use Modal Dialog?'),
      '#type' => 'checkbox',
      '#default_value' => $this->options['modal'],
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',
    ];

    // Modal Width
    $form['modal_width'] = [
      '#title' => $this->t('Modal Width'),
      '#type' => 'textfield',
      '#attributes' => array('maxlength' => 10, 'size' => 10),
      '#default_value' => $this->options['modal_width'],
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',
    ];

    // Modal height
    $form['modal_height'] = [
      '#title' => $this->t('Modal Height'),
      '#type' => 'textfield',
      '#attributes' => array('maxlength' => 10, 'size' => 10),
      '#default_value' => $this->options['modal_height'],
      '#prefix' => '<span class="vat-options-inline">',
      '#suffix' => '</span>',
    ];

    // Fieldset End
    $form['group_modal_end'] = [
      '#type' => 'html_tag',
      '#tag' => 'span',
      '#suffix' => '</fieldset>',
    ];

    // User Roles
    // ------------------------------


    // Fieldset Start
    $form['group_roles_start'] = [
      '#type' => 'html_tag',
      '#tag' => 'label',
      '#value' => $this->t('Roles:'),
      '#prefix' => '<fieldset class="vat-options-group">',
    ];

    $roles = [];
    $i = 0;
    $role_objects = Role::loadMultiple();

    foreach ($role_objects as $role) {
      $roles[$i]['id'] = $role->id();
      $roles[$i]['label'] = $role->label();
      $i++;
    }

    foreach ($roles as $role) {

      $role_form_name = 'roles-' . $role['id'];
      $form[$role_form_name] = [
        '#title' => $this->t($role['label']),
        '#type' => 'checkbox',
        '#default_value' => $this->options[$role_form_name],
      ];
    }

    // Fieldset End
    $form['group_roles_end'] = [
      '#type' => 'html_tag',
      '#tag' => 'span',
      '#suffix' => '</fieldset>',
    ];

    // Parent Options
    // ------------------------------

    parent::buildOptionsForm($form, $form_state);
  }

  /**
   * @{inheritdoc}
   */
  public function render(ResultRow $values)
  {

    $node = $values->_entity;
    $bundle = $node->bundle();
    $nid = $values->_entity->id();
    $display_path = $this->displayHandler->getPath();
    $buttons = ['edit', 'delete'];
    $elements = [];
    $icon_prefix = '';
    $icon = '<span></span>';
    $label = '';


    // Roles with access
    $system_roles = [];
    $i = 0;
    $role_objects = Role::loadMultiple();
    foreach ($role_objects as $role) {
      $system_roles[$i]['id'] = $role->id();
      $system_roles[$i]['label'] = $role->label();
      $i++;
    }

    $access_roles = [];

    foreach ($role_objects as $role) {
      $role_id = $role->id();
      $option_name = 'roles-' . $role_id;
      if ($this->options[$option_name]) {
        $access_roles[] = $role_id;
      }

    }

    // Get User Roles
    $current_user = \Drupal::currentUser();
    $user_roles = $current_user->getRoles();
    $user_id = $current_user->id();


    // is user roles in access roles ?
    $has_access = false;

    // If User is Admin
    if ($user_id == 1) {
      $has_access = true;

    } else {
      // Check user roles

      foreach ($user_roles as $user_role) {
        if (in_array($user_role, $access_roles)) {
          $has_access = true;
          break;
        }
      }
    }

    $elements = [];

    if ($has_access) {

      switch ($this->options['destination_options']) {
        case 1:
          // this view
          $destination = '?destination=' . $display_path;
          break;
        case 2:
          // <content_type>-admin
          $destination = '?destination=' . $bundle . '-admin';
          break;
        case 3:
          // other path
          $path_other = $this->options['destination_path'];
          $destination = '?destination=' . $path_other;
          break;
        default:
          //Show Content
          $destination = '';
          break;
      }


      // Size Class
      switch ($this->options['icon_size']) {
        case 0: // small
          $class_size = 'btn-sm vat-button-sm';
          break;

        case 1:  // normal
          $class_size = 'btn-md vat-button-md';
          break;

        case 2: // large
          $class_size = 'btn-lg vat-button-lg';
          break;

        default:
          $class_size = '';
          break;
      }


      //  Icon Theme prefix
      if ($this->options['button_icon']) {

        switch ($this->options['icon_set']) {

          case 1: // 'Font Awesome 5'
            $icon_prefix = 'fas fa-';
            break;

          case 2: // 'Bootstrap'
            $icon_prefix = 'glyphicon glyphicon-';
            break;

          case 3:  // 'Drupal / jQuery Ui'
            $icon_prefix = 'ui-icon ui-icon-';
            break;

          default: //'automatic'

            // Font Awesome
            //
            if (\Drupal::moduleHandler()->moduleExists('fontawesome')) {
              $icon_prefix = 'fas fa-';
            } // Twitter Bootstap 3
            elseif (\Drupal::moduleHandler()
              ->moduleExists('bootstrap_library')) {
              $icon_prefix = 'glyphicon glyphicon-';
            } // Drupal Default / jQuery UI Icons
            else {
              $icon_prefix = 'ui-icon ui-icon-';
            }
            break;

        }
      }

      // show as
      switch ($this->options['show_as']) {

        case  0: // Button:
          if (\Drupal::moduleHandler()->moduleExists('bootstrap_library')) {
            $class_show_as = ' btn btn-default vat-button';
          } else {
            $class_show_as = 'vat-button';
          }
          break;

        case 1: // Link:
          $class_show_as = 'vat-link';
          break;

        default:
          $class_show_as = 'vat-default';
          break;

      }


      foreach ($buttons as $button_name) {

        // if Button selected
        $options_button_active = 'button_' . $button_name;

        if ($this->options[$options_button_active]) {


          // Link
          switch ($button_name) {

            case 'edit':
              $link = 'node/' . $nid . '/edit' . $destination;
              $icon_name = $this->options['icon_edit'];


              break;

            case 'delete':
              $link = 'node/' . $nid . '/delete' . $destination;
              $icon_name = $this->options['icon_delete'];


              break;

            default:
              $link = 'node/' . $nid;
              $icon_name = FALSE;
              break;

          }


          // Options Icon
          if ($this->options['button_icon']) {
            $icon = '<span class="' . $icon_prefix . $icon_name . '" aria-hidden="true"></span>';
          }

          // Options Label
          if ($this->options['button_label']) {
            $label = '<span class="vat-row-label">' . $this->t($button_name) . '</span>';
          }


          $title = ['#markup' => $icon . $label];


          $elements[$button_name] = [
            '#title' => $title,
            '#type' => 'link',
            '#url' => Url::fromUri('internal:/' . $link),
            '#attributes' => [
              'class' => 'vat-button-inline ' . $class_show_as . ' ' . $class_size,
              'role' => 'button',
            ],
            '#prefix' => '<span><span class="vat-no-break">',
            '#suffix' => '</span></span>',
          ];


          // Modal Dialog

          if ($this->options['modal']) {
            $elements[$button_name]['#attributes'] = [
              'class' => 'use-ajax ' . $class_show_as . ' ' . $class_size,
              'data-dialog-type' => 'modal',
              'data-dialog-options' => json_encode([
                'width' => $this->options['modal_width'],
                'height' => $this->options['modal_height']]),


            ];
          }


        }
      }

      $elements['#attached']['library'][] = 'views_admintools/views_admintools.admin';

    }

    return $elements;

  }
}
