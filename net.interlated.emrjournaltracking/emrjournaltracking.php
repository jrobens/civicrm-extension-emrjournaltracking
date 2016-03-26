<?php

/**
 *  Journal tracking, main function
 *
 * gregm@interlated.com.au 20130905
 *
 */
require_once 'emrjournaltracking.civix.php';
require_once 'EmrJournalTrackingVal.php';

/**
 * Implementation of hook_civicrm_config
 */
function emrjournaltracking_civicrm_config(&$config)
{
    _emrjournaltracking_civix_civicrm_config($config);
}

/**
 * Implementation of hook_civicrm_xmlMenu
 *
 * @param $files array(string)
 */
function emrjournaltracking_civicrm_xmlMenu(&$files)
{
    _emrjournaltracking_civix_civicrm_xmlMenu($files);
}

/**
 * Implementation of hook_civicrm_install( )
 */
function emrjournaltracking_civicrm_install()
{
    $emrjournaltrackingRoot =
        dirname(__FILE__) . DIRECTORY_SEPARATOR;

    $emrjournaltrackingSql =
        $emrjournaltrackingRoot . DIRECTORY_SEPARATOR .
        'emrjournaltracking_install.sql';

    CRM_Utils_File::sourceSQLFile(
        CIVICRM_DSN, $emrjournaltrackingSql
    );

    // rebuild the menu so our path is picked up
    CRM_Core_Invoke::rebuildMenuAndCaches();
}

/**
 * Implementation of hook_civicrm_uninstall( )
 */
function emrjournaltracking_civicrm_uninstall()
{
    $emrjournaltrackingRoot =
        dirname(__FILE__) . DIRECTORY_SEPARATOR;

    $emrjournaltrackingSql =
        $emrjournaltrackingRoot . DIRECTORY_SEPARATOR .
        'emrjournaltracking.uninstall.sql';

    CRM_Utils_File::sourceSQLFile(
        CIVICRM_DSN, $emrjournaltrackingSql
    );

    // rebuild the menu so our path is picked up
    CRM_Core_Invoke::rebuildMenuAndCaches();
}

require_once 'CRM/Contact/Form/Search/Interface.php';

/**
 * Search interface for performing queries against contacts.
 *
 */
class net_interlated_emrjournaltracking implements CRM_Contact_Form_Search_Interface
{

    protected $_formValues;

    /**
     *
     *
     */
    function __construct(&$formValues)
    {
        if (!empty($formValues)) {
            $this->_formValues = $formValues;
        }

        /**
         * Define the columns for search result rows
         *
         * Used to define the CSV export.
         */
        $this->_columns = array(
            ts('Membership Number') => 'id',
            ts('Name') => 'name',
            ts('Line 1') => 'street_address',
            ts('Line 2') => 'supplemental_address_1',
            ts('Line 3') => 'supplemental_address_2',
            ts('City') => 'city',
            ts('State') => 'state',
            ts('Post Code') => 'postal_code',
            ts('Country') => 'country',
            ts('Email') => 'email',
            ts('End Date') => 'end_date',
        );

        // Custom fields see ActivitySearch.php
    }

    /**
     * Define the smarty template used to layout the search form and results listings.
     */
    function templateFile()
    {
        return 'JournalResults.tpl';
    }

    /**
     * Present a form to the user with.
     * TODO - not used.
     *
     * @param type $form
     */
    function buildForm(&$form)
    {
        /**
         * You can define a custom title for the search form
         */
        $this->setTitle('Find Latest EMR Activities');

        /**
         * Define the search form fields here
         */
        // Allow user to choose which type of contact to limit search on
        $form->add('select', 'contact_type', ts('Find...'), CRM_Core_SelectValues::contactType());

        // Text box for Activity Subject
        $form->add('text', 'activity_subject', ts('Activity Subject'));

        // Select box for Activity Type
        $activityType =
            array('' => ' - select activity - ') +
            CRM_Core_PseudoConstant::activityType();

        $form->add('select', 'activity_type_id', ts('Activity Type'), $activityType, false);

        // textbox for Activity Status
        $activityStatus =
            array('' => ' - select status - ') +
            CRM_Core_PseudoConstant::activityStatus();

        $form->add('select', 'activity_status_id', ts('Activity Status'), $activityStatus, false);

        // Activity Date range
        $form->addDate('start_date', ts('Activity Date From'), false, array('formatType' => 'custom'));
        $form->addDate('end_date', ts('...through'), false, array('formatType' => 'custom'));


        // Contact Name field
        $form->add('text', 'sort_name', ts('Contact Name'));

        /**
         * If you are using the sample template, this array tells the template fields to render
         * for the search form.
         */
        $form->assign('elements', array('contact_type', 'activity_subject', 'activity_type_id',
            'activity_status_id', 'start_date', 'end_date', 'sort_name'));
    }

  /**
   * Build a list of actions - there are no actions at present (export etc).
   *
   * @param CRM_Core_Form_Search $form
   * @return array|void
   */
  function buildTaskList(CRM_Core_Form_Search $form)   {
    /*
     * 4.6.0: CRM_Contact_Form_Search_Interface->buildTaskList
Classes which implement this interface must implement a new method called buildTaskList. This method is responsible for building the list of actions (e.g., Add to Group) that may be performed on set of search results. It differs from hook_civicrm_searchTasks in that the hook allows a developer to specify tasks by entity (e.g., Contact, Event, etc.) whereas buildTaskList provides the ability to target a specific form. The new method takes a CRM_Core_Form_Search object as an argument and should return an array. Dump CRM_Core_Form_Search->_taskList to learn about the format of the array. The array returned by buildTaskList will completely replace the task list.

Aside from the community-maintained custom searches in CRM/Contact/Form/Search/Custom/, this change does not affect CiviCRM core. Custom searches which extend CRM_Contact_Form_Search_Custom_Base (as do those built on civix) will not be affected, as the method is implemented there.

See CRM-15965 for more information.
     *
     */
  }

    /**
     * Construct the search query
     */
    function all($offset = 0, $rowcount = 0, $sort = null, $includeContactIDs = false, $onlyIDs = false)
    {
        // Included a bunch of extra columns which don't get printed to CSV
        $select = 'c.id,
      c.display_name as name,
      civicrm_address.street_address, 
      civicrm_address.supplemental_address_1, 
      civicrm_address.supplemental_address_2, 
      civicrm_address.city, 
      civicrm_state_province.abbreviation AS state, 
      civicrm_address.postal_code, 
      country.name AS country,
      civicrm_email.email  ';


        $from = $this->from();

        // Needs the parameter but it is not used.
        $where = $this->where($includeContactIDs);

        if (!empty($where)) {
            $where = "WHERE $where";
            // Limit to sysadmin
            //$where .= " AND cc.id = 1";
        }


        $sql = " SELECT $select FROM $from $where GROUP BY c.id ";
        return $sql;
    }

    /**
     * Add a join and where condition limiting contacts with activities matching the code.
     *
     * @param type $offset
     * @param type $rowcount
     * @param type $sort
     * @param type $includeContactIDs
     * @param type $onlyIDs
     * @return type
     */
    function catchup($tracking_code, $offset = 0, $rowcount = 0, $sort = null, $includeContactIDs = false, $onlyIDs = false)
    {
        $sql_all = $this->all($offset = 0, $rowcount = 0, $sort = null, $includeContactIDs = false, $onlyIDs = false);

        $sql = preg_replace('/GROUP BY c\.id/', '', $sql_all);

        /*$sql .= " AND NOT c.id IN (
        SELECT target_contact_id FROM civicrm_option_value cov
        LEFT JOIN civicrm_activity activity ON activity.activity_type_id = cov.`value`
        LEFT JOIN civicrm_activity_target cat ON cat.activity_id = activity.id 
        LEFT JOIN civicrm_value_esa_journal_tracking_14 tracking ON tracking.entity_id = activity.id
        WHERE cov.name = '";*/
        $sql .= "AND NOT c.id IN (
          SELECT cac.contact_id FROM civicrm_option_value cov
        LEFT JOIN civicrm_activity activity ON activity.activity_type_id = cov.value
        LEFT JOIN civicrm_activity_contact cac ON cac.activity_id = activity.id 
        LEFT JOIN civicrm_value_esa_journal_tracking_14 tracking ON tracking.entity_id = activity.id
        WHERE cov.name = '";
        $sql .= EmrJournaltrackingVal::ACTIVITY_TYPE_NAME;
        $sql .= "'
            AND tracking.batch_id_101 = '" . $tracking_code . "'
)";

        $sql .= 'GROUP BY c.id ';
        return $sql;
    }

    /**
     * Alters the date display in the Activity Date Column. We do this after we already have
     * the result so that sorting on the date column stays pertinent to the numeric date value
     * @param type $row
     */
    function alterRow(&$row)
    {
        // $row['activity_date'] = CRM_Utils_Date::customFormat($row['activity_date'], '%B %E%f, %Y %l:%M %P');
    }

    // Regular JOIN statements here to limit results to contacts who have activities.
    function from()
    {
        return "
      civicrm_contact c
      LEFT JOIN civicrm_address ON c.id = civicrm_address.contact_id
      LEFT JOIN civicrm_membership ON c.id = civicrm_membership.contact_id
      LEFT JOIN civicrm_state_province ON civicrm_address.state_province_id = civicrm_state_province.id
      LEFT JOIN civicrm_country country ON civicrm_address.country_id = country.id
      LEFT JOIN civicrm_email ON c.id =  civicrm_email.contact_id
     ";

        // civicrm_contribution is a different join.
        //LEFT JOIN civicrm_value_esa_lists__bulletins_4 ON civicrm_contact.id = civicrm_value_esa_lists__bulletins_4.entity_id";
    }

    /**
     * WHERE clause is an array built from any required JOINS plus conditional filters based on search criteria field values
     *
     */
    function where($includeContactIDs = FALSE)
    {
        $clauses = array();

        // Are they an EMR membership?
        $clauses[] = "civicrm_membership.membership_type_id IN (" . EmrJournalTrackingVal::MEMBERSHIP_TYPES . ") ";

        // Are they a new or current member?
        $clauses[] = "civicrm_membership.status_id IN (" . EmrJournaltrackingVal::MEMBERSHIP_STATUSES . ") ";

        $clauses[] = "civicrm_membership.is_test = 0 ";
        $clauses[] = "c.is_deleted = 0 ";
        $clauses[] = "civicrm_address.is_primary = 1";
        $clauses[] = "civicrm_email.is_primary = 1";
        // Forget electronic only, not applicable for EMR.
        // Don't need to remove children in this case. They will not have a subscription.

        // add where for batch date.
        return implode(' AND ', $clauses);
    }

    /**
     * Functions below generally don't need to be modified
     */
    function count()
    {
        $sql = $this->all();

        $dao = CRM_Core_DAO::executeQuery($sql, CRM_Core_DAO::$_nullArray);
        return $dao->N;
    }

    function contactIDs($offset = 0, $rowcount = 0, $sort = null)
    {
        return $this->all($offset, $rowcount, $sort, false, true);
    }

    function &columns()
    {
        return $this->_columns;
    }

    function summary()
    {
        return null;
    }

}

/*
 * EMR initial query export.
 *

  SELECT c.id,
      c.display_name as name,
      civicrm_address.street_address,
      civicrm_address.supplemental_address_1,
      civicrm_address.supplemental_address_2,
      civicrm_address.city,
      civicrm_state_province.abbreviation AS state,
      civicrm_address.postal_code,
      country.name AS country,
      civicrm_email.email   FROM
      civicrm_contact c
      LEFT JOIN civicrm_address ON c.id = civicrm_address.contact_id
      LEFT JOIN civicrm_membership ON c.id = civicrm_membership.contact_id
      LEFT JOIN civicrm_state_province ON civicrm_address.state_province_id = civicrm_state_province.id
      LEFT JOIN civicrm_country country ON civicrm_address.country_id = country.id
      LEFT JOIN civicrm_contribution ON c.id = civicrm_contribution.contact_id
      LEFT JOIN civicrm_email ON c.id =  civicrm_email.contact_id
      GROUP BY c.id

  */