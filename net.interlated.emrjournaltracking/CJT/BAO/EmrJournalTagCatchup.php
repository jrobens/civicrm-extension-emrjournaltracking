<?php

/**
 * Business logic methods for journal tracking.
 *
 * gregm@interlated.com.au 20130905
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
require_once 'CJT/DAO/EmrJournalTag.php';
require_once 'CJT/DAO/EmrJournalTagCatchup.php';


class CJT_BAO_EmrJournalTagCatchup extends CJT_DAO_EmrJournalTag
{

    /**
     * class constructor
     */
    function __construct()
    {
        parent::__construct();
    }


    /**
     *
     * Used in View.php. Not sure why it isn't inherited.
     *
     * @param array $params (reference ) an assoc array of name/value pairs
     * @param array $defaults (reference ) an assoc array to hold the flattened values
     *
     * @return object CJT_BAO_Item object on success, null otherwise
     * @access public
     * @static
     */
    static function retrieve(&$params, &$defaults)
    {
        $tracker = new CJT_DAO_EmrJournalTag();
        $tracker->copyValues($params);
        if ($tracker->find(true)) {
            // More like stash - just puts the values in the array.
            CRM_Core_DAO::storeValues($tracker, $defaults);
            return $tracker;
        }
        return null;
    }


    /**
     * Finds catchup batches associated with the original batch.
     *
     * @param type $params array
     *   contains the code parameter to search for. Must be spet specifically.
     *
     * @param type $batches
     *   Return matching batches.
     */
    static function findAssociated($params, &$batches)
    {
        // Safe sql as only alpha and numbers are allowed in codes.
        $sql = "select code,catchup_date,description,count from " . CJT_DAO_EmrJournalTagCatchup::getTableName() . " where code  = '" . $params['code'] . "' order by catchup_date desc";
        $dao = CRM_Core_DAO::executeQuery($sql);

        // No records then return
        if ($dao->N <= 0) {
            return;
        }

        while ($dao->fetch()) {
            $row = array();

            $row['code'] = $dao->code;
            $row['catchup_date'] = $dao->catchup_date;
            $row['description'] = $dao->description;
            $row['count'] = $dao->count;

            $batches[] = $row;
        }
    }

}

?>    