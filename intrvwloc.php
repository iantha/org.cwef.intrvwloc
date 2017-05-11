<?php

require_once 'intrvwloc.civix.php';

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function intrvwloc_civicrm_config(&$config) {
  _intrvwloc_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function intrvwloc_civicrm_xmlMenu(&$files) {
  _intrvwloc_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function intrvwloc_civicrm_install() {
  _intrvwloc_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function intrvwloc_civicrm_postInstall() {
  _intrvwloc_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function intrvwloc_civicrm_uninstall() {
  _intrvwloc_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function intrvwloc_civicrm_enable() {
  _intrvwloc_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function intrvwloc_civicrm_disable() {
  _intrvwloc_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function intrvwloc_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _intrvwloc_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function intrvwloc_civicrm_managed(&$entities) {
  _intrvwloc_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function intrvwloc_civicrm_caseTypes(&$caseTypes) {
  _intrvwloc_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function intrvwloc_civicrm_angularModules(&$angularModules) {
  _intrvwloc_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function intrvwloc_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _intrvwloc_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function intrvwloc_civicrm_preProcess($formName, &$form) {

} // */

function intrvwloc_civicrm_buildForm($formName, &$form) {
  // dpm(array($formName, $form));
  if ($formName == 'CRM_Activity_Form_Activity') {
    Civi::resources()
      ->addScriptFile('org.cwef.intrvwloc', 'js/activity.js')
      ->addStyleFile('org.cwef.intrvwloc', 'css/activity.css');

  }
}

function intrvwloc_civicrm_postProcess($formName, &$form) {
  if ($formName == 'CRM_Activity_Form_Activity') {
    intrvwloc_lookup_security_rating($form->_activityId);
  }
}

function intrvwloc_lookup_security_rating($activityId) {
  $locField = 'custom_7';
  $ratingField = 'custom_8';

  $activity = civicrm_api3('Activity', 'getsingle', array(
    'id' => $activityId,
    'return' => array($locField, $ratingField, 'activity_type_id'),
    'sequential' => 1,
  ));

  list ($lat, $long) = explode(',', $activity[$locField]);

  $response = file_get_contents(sprintf(
    'http://think.hm/secrate.php?activity_type=%s&long=%s&lat=%s',
    urlencode($activity['activity_type_id']),
    urlencode($long),
    urlencode($lat)
  ));

  $responseData = json_decode($response, TRUE);

  print_r(array(
    'activity' => $activity,
    '$responseData' => $responseData,
  ));

  civicrm_api3('Activity', 'create', array(
    'id' => $activityId,
    $ratingField => $responseData['color'],
  ));
}
