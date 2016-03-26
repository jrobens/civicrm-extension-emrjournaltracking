<?php

require_once 'CRM/Core/Page/Basic.php';

class CJT_Page_EmrJournaltracking_JournalControls extends CRM_Core_Page_Basic {

  function run() {
    // Example: Set the page-title dynamically; alternatively, declare a static title in xml/Menu/*.xml
    CRM_Utils_System::setTitle(ts('EMR Journal Controls'));

    // Example: Assign a variable for use in a template
 //   $this->assign('currentTime', date('Y-m-d H:i:s'));


    parent::run();
  }

  // discount/List.php

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

    function browse() {
        // Sort by date descending
        parent::browse(CRM_Core_Action::VIEW, "batch_date desc");
    }

  /**
   * Get action Links
   *
   * @return array (reference) of action links
   */
  function &links() {
    if (!(self::$_links)) {
      self::$_links = array(
        CRM_Core_Action::VIEW => array(
          'name' => ts('Detail'),
          'url' => 'civicrm/emrjournaltracking/view',
          'qs' => 'id=%%id%%&reset=1',
          'title' => ts('View Tracked Journal')
        )
        
      );
    }
    return self::$_links;
  }

  /**
   * Get name of edit form
   * 
   * Not required.
   *
   * @return string Classname of edit form.
   */
  function editForm() {
    return 'CJT_Form_Item';
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

}
