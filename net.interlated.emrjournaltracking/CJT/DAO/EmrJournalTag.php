<?php

/**
 * @file
 * Creates a record of a journal run.
 *
 * From cividiscount CDM_DAO_Item
 *
 * gregm@interlated.com.au 2013
 */
/**
 *
 * @package CJT
 * @copyright CiviCJT LLC (c) 2004-2011
 * $Id$
 *
 */
require_once 'CRM/Core/DAO.php';
require_once 'CRM/Utils/Type.php';

class CJT_DAO_EmrJournalTag extends CRM_Core_DAO
{

    /**
     * static instance to hold the table name
     *
     * @var string
     * @static
     */
    static $_tableName = 'civiemrjournal_item';

    /**
     * static instance to hold the field values
     *
     * @var array
     * @static
     */
    static $_fields = null;

    /**
     * static instance to hold the FK relationships
     *
     * @var string
     * @static
     */
    static $_links = null;

    /**
     * static instance to hold the values that can
     * be imported
     *
     * @var array
     * @static
     */
    static $_import = null;

    /**
     * static instance to hold the values that can
     * be exported
     *
     * @var array
     * @static
     */
    static $_export = null;

    /**
     * static value to see if we should log any modifications to
     * this table in the civicrm_log table
     *
     * @var boolean
     * @static
     */
    static $_log = false;

    /**
     * Tracking Item ID
     *
     * @var int unsigned
     */
    public $id;

    /**
     * Tracking Code.  Form version
     *
     * @var string
     */
    public $code;

    /**
     * Activity Tracking Code. Added extension prefix so that it is distinct across extensions.
     *
     * @var string
     */
    public $activity_code;


    /**
     * Journal Export Description.
     *
     * @var string
     */
    public $description;

    /**
     * When was this batch export run?
     *
     * @var datetime
     */
    public $batch_date;

    /**
     * Count of the number of activities created.
     *
     * @var int unsigned
     */
    public $count;

    /**
     *
     */
    function __construct()
    {
        parent::__construct();
    }

    /**
     * return foreign links
     *
     * @access public
     * @return array
     */
    function &links()
    {
        if (!(self::$_links)) {
            self::$_links = array(
                'organization_id' => 'civicrm_contact:id',
            );
        }
        return self::$_links;
    }

    /**
     * returns all the column names of this table
     *
     * @access public
     * @return array
     */
    static function &fields()
    {
        if (!(self::$_fields)) {
            self::$_fields = array(
                'id' => array(
                    'name' => 'id',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ),
                'code' => array(
                    'name' => 'code',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Code'),
                    'required' => TRUE,
                    'maxlength' => 255,
                    'size' => CRM_Utils_Type::HUGE,
                ),
                'activity_code' => array(
                    'name' => 'activity_code',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Activity Code'),
                    'required' => TRUE,
                    'maxlength' => 255,
                    'size' => CRM_Utils_Type::HUGE,
                ),
                'description' => array(
                    'name' => 'description',
                    'type' => CRM_Utils_Type::T_STRING,
                    'title' => ts('Description'),
                    'required' => true,
                    'maxlength' => 255,
                    'size' => CRM_Utils_Type::HUGE,
                ),
                'batch_date' => array(
                    'name' => 'batch_date',
                    'type' => CRM_Utils_Type::T_DATE + CRM_Utils_Type::T_TIME,
                    'label' => ts('EMR Journal Batch Date'),
                    'title' => ts('EMR Journal Batch Date'),
                    'required' => TRUE,
                ),
                'count' => array(
                    'name' => 'count',
                    'type' => CRM_Utils_Type::T_INT,
                    'required' => true,
                ),
            );
        }
        return self::$_fields;
    }

    /**
     * returns the names of this table
     *
     * @access public
     * @return string
     */
    static function getTableName()
    {
        return CRM_Core_DAO::getLocaleTableName(self::$_tableName);
    }

    /**
     * returns if this table needs to be logged
     *
     * @access public
     * @return boolean
     */
    function getLog()
    {
        return self::$_log;
    }

    /**
     * returns the list of fields that can be imported
     *
     * @access public
     * return array
     */
    function &import($prefix = false)
    {
        if (!(self::$_import)) {
            self::$_import = array();
            $fields = self::fields();
            foreach ($fields as $name => $field) {
                if (CJT_Utils_Array::value('import', $field)) {
                    if ($prefix) {
                        self::$_import['ount_item'] = & $fields[$name];
                    } else {
                        self::$_import[$name] = & $fields[$name];
                    }
                }
            }
        }
        return self::$_import;
    }

    /**
     * returns the list of fields that can be exported
     *
     * @access public
     * return array
     */
    function &export($prefix = false)
    {
        if (!(self::$_export)) {
            self::$_export = array();
            $fields = self::fields();
            foreach ($fields as $name => $field) {
                if (CJT_Utils_Array::value('export', $field)) {
                    if ($prefix) {
                        self::$_export['ount_item'] = & $fields[$name];
                    } else {
                        self::$_export[$name] = & $fields[$name];
                    }
                }
            }
        }
        return self::$_export;
    }

}

?>