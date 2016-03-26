<?php

/**
 *  Export data for print labels and catchup jobs.
 *
 *
 * gregm@interlated.com.au
 * 20130905
 *
 */
/*
  +--------------------------------------------------------------------+
  | CiviCJT version 4.1                                                |
  +--------------------------------------------------------------------+
  | Copyright CiviCJT LLC (c) 2004-2011                                |
  +--------------------------------------------------------------------+
  | This file is a part of CiviCJT.                                    |
  |                                                                    |
  | CiviCJT is free software; you can copy, modify, and distribute it  |
  | under the terms of the GNU Affero General Public License           |
  | Version 3, 19 November 2007 and the CiviCJT Licensing Exception.   |
  |                                                                    |
  | CiviCJT is distributed in the hope that it will be useful, but     |
  | WITHOUT ANY WARRANTY; without even the implied warranty of         |
  | MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.               |
  | See the GNU Affero General Public License for more details.        |
  |                                                                    |
  | You should have received a copy of the GNU Affero General Public   |
  | License and the CiviCJT Licensing Exception along                  |
  | with this program; if not, contact CiviCJT LLC                     |
  | at info[AT]civicrm[DOT]org. If you have questions about the        |
  | GNU Affero General Public License or the licensing of CiviCJT,     |
  | see the CiviCJT license FAQ at http://civicrm.org/licensing        |
  +--------------------------------------------------------------------+
 */

/**
 *
 * @package CJT
 * @copyright CiviCJT LLC (c) 2004-2011
 * $Id$
 *
 */
require_once 'CRM/Admin/Form.php';
require_once 'CJT/DAO/EmrJournalTag.php';

/**
 * This class generates form components for Location Type
 *
 */
class CJT_Form_EmrJournaltracking_Export extends CRM_Admin_Form
{

    /**
     *
     */
    function preProcess()
    {
        $this->_id = CRM_Utils_Request::retrieve('id', 'Positive', $this, false, 0);

        parent::preProcess();

        $session = CRM_Core_Session::singleton();
        $id_string = "";
        if (!empty($this->_id)) {
            $id_string = "&id=" . $this->_id;
        }
        $url = CRM_Utils_System::url('civicrm/emrjournaltracking', 'reset=1' . $id_string);
        $session->pushUserContext($url);

        // No CRM/Utils/Rule.php or CRM_Core_Action::UPDATE or CRM_Core_Action::DELETE
    }

    /**
     *
     * Each batch is generated once, so there is not a lot of value in
     * loading defaults.
     *
     * @return type
     */
    function setDefaultValues()
    {
        // There is never an original ID  as we don't ever display this form
        // on edit.
        $defaults = array();

        // We don't use this batch date for anything and don't let people update it. The idea was to allow people to set when they wanted the batch to run.
        list($defaults['batch_date'], $defaults['batch_time']) = CRM_Utils_Date::setDateDefaults($defaults['batch_date'], NULL, 'd-M-Y His');

        return $defaults;
    }

    /**
     * Function to build the form.
     *
     * @return None
     * @access public
     */
    public function buildQuickForm()
    {
        parent::buildQuickForm();

        if ($this->_action & CRM_Core_Action::DELETE) {
            return;
        }

        $this->applyFilter('__ALL__', 'trim');

        $fieldName = 'code';
        $object = new CJT_DAO_EmrJournalTag( );
        $fields = &$object->fields();
        $field = CRM_Utils_Array::value($fieldName, $fields);
        $code = CRM_Core_DAO::makeAttribute($field);
    
        // $element returned, but we use the object to address it.
        $element = & $this->add('text', 'code', ts('Code'), $code, TRUE);

        $this->addRule('code', ts('Code already exists in Database.'), 'objectExists', array('CJT_DAO_EmrJournalTag', $this->_id, 'code'));
        $this->addRule('code', ts('Code can only consist of alpha-numeric characters'), 'variable');

        $fieldName = 'description';
        $object = new CJT_DAO_EmrJournalTag( );
        $fields = &$object->fields();
        $field = CRM_Utils_Array::value($fieldName, $fields);
        $description = CRM_Core_DAO::makeAttribute($field);

        // Description
        $this->add('text', 'description', ts('Description'), $description, FALSE);

        // batch_date. The date format has to be m-d-y or it won't work. Use the description to print out formatted value.
        $this->addDateTime('batch_date', ts('Approx Batch Date ') . date("d-M-Y H:i", time()), FALSE);

        $this->addButtons(array(
                array(
                    'type' => 'next',
                    'name' => ts('Export'),
                    'isDefault' => TRUE,
                ),
                array(
                    'type' => 'cancel',
                    'name' => ts('Finished'),
                ),
            )
        );
    }


    /**
     * Function to process the form
     *
     * @access public
     * @return None
     */
    public function postProcess()
    {
        //$cividiscountRoot = dirname(__FILE__) . DIRECTORY_SEPARATOR;
        require_once 'emrjournaltracking.php';

        $params = $this->exportValues();

        $tracker = new net_interlated_emrjournaltracking($params);
        $all_sql = $tracker->all();

        $csvFullFilename = CRM_Utils_File::makeFileName('CiviEmrReport.csv');

        // $export_file_name = $this->getCsvFileName();
        // Maybe able to get the header from the sql. Nice to hand code.
        $columns = $tracker->columns();

        try {
            $this->run_csv($all_sql, $csvFullFilename, $columns, $params['code']);
        } catch (Exception $e) {
            // no records found
            $params['count'] = 0;
            $this->_recordJournalBatch($params);
            return;
        }

        // Create activities for all the contacts that had a journal exported for them.
        require_once('CJT/BAO/EmrJournalTag.php');
        $count = CJT_BAO_EmrJournalTag::createActivities(EmrJournalTrackingVal::ACTIVITY_CODE_PREFIX . $params['code'],
            $all_sql);

        // Record the journal batch
        $params['count'] = $count;
        $this->_recordJournalBatch($params);

        CRM_Core_Session::setStatus(ts('The EMR journal distribution \'%1\' has been saved.', array(1 => $params['code'] ? $params['code'] : '')));

        // Did the download work option would make some sense. Would create some race conditions and the person can just create another tag.
        // How to redirect the user to the result screen?
        CRM_Utils_System::civiExit();
    }

    /**
     *
     * @return type
     */
    private function getCsvFileName()
    {
        return ts('export for EMR journal batch');
    }

    /**
     * Run the sql and export CSV data.
     *
     * @param string $all_sql
     * @param string $fileName
     * @param array $header
     *   name/label pairs.
     */
    private function run_csv($all_sql, $fileName, array $header, $tracking_id)
    {
        $writeHeader = TRUE;
        $offset = 0;
        $limit = 20000;

        require_once 'CRM/Core/Report/Excel.php';
        // $limitQuery = $all_sql . "LIMIT $offset, $limit";
        $dao = CRM_Core_DAO::executeQuery($all_sql);

        // No records then return
        if ($dao->N <= 0) {
            return;
        }

        $columns = array_values($header);
        $labels = array_keys($header);

        $componentDetails = array();
        while ($dao->fetch()) {
            $row = array();

            foreach ($columns as $column_num => $column) {
                if (property_exists($dao, $column)) {
                    $row[$column] = $dao->{$column};
                } else {
                    // Verbose mode, print out an error that the property does not exist.
                    $row[$column] = '';
                }
            }

            $componentDetails[] = $row;
        }

        // function writeCSVFile($fileName, &$header, &$rows, $titleHeader = NULL, $outputHeader = TRUE
        $core_report = new CRM_Core_Report_Excel();
        $core_report->writeCSVFile($fileName, $labels, $componentDetails);
    }

    /**
     * Saves a batch entry.
     *
     * @param array $params
     */
    private function _recordJournalBatch(array $params)
    {
        // Record the journal batch
        $journal_batch = new CJT_DAO_EmrJournalTag();
        $journal_batch->code = $params['code'];
        $journal_batch->activity_code = EmrJournalTrackingVal::ACTIVITY_CODE_PREFIX . $params['code'];

        // batch_date
        require_once 'CRM/Utils/Date.php';
        // Current date is more accurate than that passed as a parameter. There are  no dates in the query, and people get
        // catch up batch so it makes no difference.
        $journal_batch->batch_date = CRM_Utils_Date::processDate(date('YmdHis'));

        // description
        $journal_batch->description = "";
        if (!empty($params['description'])) {
            $journal_batch->description = $params['description'];
        }

        // Count
        $journal_batch->count = 0;
        if (!empty($params['count'])) {
            $journal_batch->count = $params['count'];
        }

        // save data.
        $result = $journal_batch->save();
        if ($result->_lastError) {
            CRM_Core_Session::setStatus(ts('There has been an error saving the EMR journal batch. Please check that the EMR journal batch has been recorded and watchdog log'));
        }
    }

}

