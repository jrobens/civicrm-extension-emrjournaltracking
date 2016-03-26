<?php

/**
 * @file
 * Specify values for EMR journal tracking. i.e. configuration items and static data. 
 * 
 * 20130905
 * gregm@interlated.com.au
 */

/**
 * 
 */
class EmrJournalTrackingVal {
  // List for civicrm_membership.membership_type_id

  const MEMBERSHIP_TYPES = "'6'"; // implode(',', array(1, 7));
  const MEMBERSHIP_STATUSES = "'1', '2'";
  const EMR_PRODUCT_ID = "1";
  // Activities
  const JOURNAL_ACTIVITY_TYPE_CODE = "43";
  const JOURNAL_ACTIVITY_SOURCE_CONTACT = "1";
  const JOURNAL_ACTIVITY_STATUS = "2"; // 2 = completed. (1=scheduled,3=cancelled,4=left message,5=unreachable,6=not required)
  // The field in civicrm_value_esa_journal_tracking_14 to store the data
  // Vulnerable to the way the custom fields are setup. If installing check this field.
  // This is the database column name that we need to query by.
  const JOURNAL_ACTIVTY_CUSTOM_TRACKING_FIELD_CODE = 'batch_id_101';
  // This is the code to tell civicrm how to save the data via the api
  const JOURNAL_ACTIVITY_CUSTOM_TRACKING_FIELD_ID = 'custom_101_-1';

  // Catchup Activities
  // Vulnerable to the way the custom fields are setup. If installing check this field.
  const CATCHUP_ACTIVITY_CUSTOM_FIELD = "civicrm_value_esa_journal_tracking_14";
  const ACTIVITY_TYPE_NAME = 'Tracked EMR Journal';
  const ACTIVITY_CODE_PREFIX = 'EMR';

}

?>
