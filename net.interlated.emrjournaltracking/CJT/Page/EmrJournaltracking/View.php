<?php

/*
  +--------------------------------------------------------------------+
  | CiviCRM version 4.1                                                |
  +--------------------------------------------------------------------+
  | Copyright CiviCRM LLC (c) 2004-2011                                |
  +--------------------------------------------------------------------+
  | This file is a part of CiviCRM.                                    |
  |                                                                    |
  | CiviCRM is free software; you can copy, modify, and distribute it  |
  | under the terms of the GNU Affero General Public License           |
  | Version 3, 19 November 2007 and the CiviCRM Licensing Exception.   |
  |                                                                    |
  | CiviCRM is distributed in the hope that it will be useful, but     |
  | WITHOUT ANY WARRANTY; without even the implied warranty of         |
  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
  | See the GNU Affero General Public License for more details.        |
  |                                                                    |
  | You should have received a copy of the GNU Affero General Public   |
  | License and the CiviCRM Licensing Exception along                  |
  | with this program; if not, contact CiviCRM LLC                     |
  | at info[AT]civicrm[DOT]org. If you have questions about the        |
  | GNU Affero General Public License or the licensing of CiviCRM,     |
  | see the CiviCRM license FAQ at http://civicrm.org/licensing        |
  +--------------------------------------------------------------------+
 */

/**
 *
 * @package CRM
 * @copyright CiviCRM LLC (c) 2004-2011
 * $Id$
 *
 */
require_once 'CRM/Core/Page.php';
require_once 'CJT/DAO/EmrJournalTag.php';

/**
 * Page for displaying discount code details
 */
class CJT_Page_EmrJournaltracking_View extends CRM_Core_Page {

  /**
   * The id of the discount code
   *
   * @var int
   */
  protected $_id;
  protected $_multiValued = null;

  /**
   * The action links that we need to display for the browse screen
   *
   * @var array
   * @static
   */
  static $_links = null;

  /**
   * Get BAO Name
   *
   * @return string Classname of BAO.
   */
  function getBAOName() {
    return 'CJT_BAO_EmrJournalTag';
  }

  /**
   * Get action Links
   *
   * @return array (reference) of action links
   */
  function &links() {
    if (!(self::$_links)) {
      self::$_links = array(
        CRM_Core_Action::UPDATE => array(
          'name' => ts('Edit'),
          'url' => 'civicrm/emrjournaltracking/view',
          'qs' => '&id=%%id%%&reset=1',
          'title' => ts('View Tracked EMR Journal')
        ),
      );
    }
    return self::$_links;
  }

  /**
   * Get name of edit form
   *
   * @return string Classname of edit form.
   */
  function editForm() {
    return 'CJT_Form_Export';
  }

  /**
   * Get edit form name
   *
   * @return string name of this page.
   */
  function editName() {
    return 'EMR Journal Tracking';
  }

  /**
   * Get user context.
   *
   * @return string user context.
   */
  function userContext($mode = null) {
    return 'civicrm/emrjournaltracking/view';
  }

  /**
   * Find existing data. 
   */
  function preProcess() {
    $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this, false);

    require_once 'CRM/Utils/Rule.php';
    if (!CRM_Utils_Rule::positiveInteger($this->_id)) {
      CRM_Core_Error::fatal(ts('We need a valid export tracking ID for view'));
    }

    $this->assign('id', $this->_id);
    $defaults = array();
    $params = array('id' => $this->_id);

    // Copies existing record into the defaults.
    require_once 'CJT/BAO/EmrJournalTag.php';
    CJT_BAO_EmrJournalTag::retrieve($params, $defaults);

    $this->assign('code_id', $defaults['id']);
    $this->assign('code', $defaults['code']);
    if (array_key_exists('description', $defaults)) {
      $this->assign('description', $defaults['description']);
    }

    if (array_key_exists('batch_date', $defaults)) {
      $this->assign('batch_date', $defaults['batch_date']);
    }

    if (array_key_exists('count', $defaults)) {
      $this->assign('count', $defaults['count']);
    }

    // Find all catch up batches relating to this one.
    require_once 'CJT/BAO/EmrJournalTagCatchup.php';
    $params['code'] = $defaults['code'];
    CJT_BAO_EmrJournalTagCatchup::retrieve($params, $defaults);
    $catchup_batches = array();
    CJT_BAO_EmrJournalTagCatchup::findAssociated($params, $catchup_batches);

    $this->assign('catchup_batches', $catchup_batches);

    CRM_Utils_System::setTitle($defaults['code']);
  }

  function run() {
    $this->preProcess();
    return parent::run();
  }

}

