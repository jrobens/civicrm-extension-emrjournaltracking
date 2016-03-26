
-- Version created from sql trace.
SELECT 
        cc.id, CONCAT_WS(" ", first_name, last_name) as name, 
        street_address, 
        supplemental_address_1, 
        supplemental_address_2, 
        city, 
        csp.abbreviation as State, 
        postal_code, 
        country.name as Country  
                 FROM   
      civicrm_contact cc   
        LEFT JOIN civicrm_address ca ON cc.id = ca.contact_id 
        LEFT JOIN civicrm_membership cm ON cc.id = cm.contact_id
        LEFT JOIN civicrm_state_province csp ON ca.state_province_id = csp.id
        LEFT JOIN civicrm_country country ON ca.country_id = country.id 
WHERE (cm.membership_type_id IN ('1','8','2','13','3','4','9','5','7','10','11','12') 
AND cm.status_id IN ('5','2') 
AND cm.is_test = 0 )  AND (cc.is_deleted = 0) 
AND is_primary = 1 


-- activity_type_id = 36
-- civicrm_option_group: name = activity_type
-- civicrm_option_value: value =
select * from civicrm_activity order by id desc  


select * from civicrm_option_group cog 
  LEFT JOIN civicrm_option_value cov ON cog.option_group_id = cov.id
WHERE cog.`name` = 'activity_type'

-- Find all the contacts that have been sent an id and filter them out. 
select target_contact_id from civicrm_option_value cov
  LEFT JOIN civicrm_activity activity ON activity.activity_type_id = cov.`value`
  LEFT JOIN civicrm_activity_target cat ON cat.activity_id = activity.id 
  LEFT JOIN civicrm_value_esa_journal_tracking_batch_id_14 tracking ON tracking.entity_id = activity.id
WHERE cov.name = 'Tracked Journal'
AND tracking.batch_id_101 = 'yzd62zjaa';

-- civicrm_value_esa_journal_tracking_batch_id_14

SELECT 
        cc.id, CONCAT_WS(" ", first_name, last_name) as name, 
        street_address, 
        supplemental_address_1, 
        supplemental_address_2, 
        city, 
        csp.abbreviation as State, 
        postal_code, 
        country.name as Country  
                 FROM   
      civicrm_contact cc   
        LEFT JOIN civicrm_address ca ON cc.id = ca.contact_id 
        LEFT JOIN civicrm_membership cm ON cc.id = cm.contact_id
        LEFT JOIN civicrm_state_province csp ON ca.state_province_id = csp.id
        LEFT JOIN civicrm_country country ON ca.country_id = country.id 
WHERE (cm.membership_type_id IN ('1','8','2','13','3','4','9','5','7','10','11','12') 
AND cm.status_id IN ('5','2') 
AND cm.is_test = 0 )  AND (cc.is_deleted = 0) 
AND is_primary = 1 
AND NOT cc.id IN (
    SELECT target_contact_id FROM civicrm_option_value cov
        LEFT JOIN civicrm_activity activity ON activity.activity_type_id = cov.`value`
        LEFT JOIN civicrm_activity_target cat ON cat.activity_id = activity.id 
        LEFT JOIN civicrm_value_esa_journal_tracking_batch_id_14 tracking ON tracking.entity_id = activity.id
        WHERE cov.name = 'Tracked Journal'
            AND tracking.batch_id_101 = 'yzd62zjaa'
)


 SELECT 
        cc.id, CONCAT_WS(" ", first_name, last_name) as name, 
        street_address, 
        supplemental_address_1, 
        supplemental_address_2, 
        city, 
        csp.abbreviation as State, 
        postal_code, 
        country.name as Country  
                 FROM   
      civicrm_contact cc   
        LEFT JOIN civicrm_address ca ON cc.id = ca.contact_id 
        LEFT JOIN civicrm_membership cm ON cc.id = cm.contact_id
        LEFT JOIN civicrm_state_province csp ON ca.state_province_id = csp.id
        LEFT JOIN civicrm_country country ON ca.country_id = country.id WHERE cm.membership_type_id IN ('1','8','2','13','3','4','9','5','7','10','11','12') AND cm.status_id IN ('5','2') AND cm.is_test = 0 AND cc.is_deleted = 0 AND is_primary = 1 AND NOT cc.id IN (
    SELECT target_contact_id FROM civicrm_option_value cov
        LEFT JOIN civicrm_activity activity ON activity.activity_type_id = cov.`value`
        LEFT JOIN civicrm_activity_target cat ON cat.activity_id = activity.id 
        LEFT JOIN civicrm_value_esa_journal_tracking_batch_id_14 tracking ON tracking.entity_id = activity.id
        WHERE cov.name = 'Tracked Journal'
            AND tracking.batch_id_101 = 'mbxjb5gwa'
)


-- 
SELECT 
        cc.id, CONCAT_WS(" ", first_name, last_name) as name, 
        street_address, 
        supplemental_address_1, 
        supplemental_address_2, 
        city, 
        csp.abbreviation as State, 
        postal_code, 
        country.name as Country  
                 FROM   
      civicrm_contact cc   
        LEFT JOIN civicrm_address ca ON cc.id = ca.contact_id 
        LEFT JOIN civicrm_membership cm ON cc.id = cm.contact_id
        LEFT JOIN civicrm_state_province csp ON ca.state_province_id = csp.id
        LEFT JOIN civicrm_country country ON ca.country_id = country.id 
WHERE cm.membership_type_id IN ('1','8','2','13','3','4','9','5','7','10','11','12') AND cm.status_id IN ('5','2') 
AND cm.is_test = 0 AND cc.is_deleted = 0 AND is_primary = 1

-- delete from civicrm_cache

-- find associated catchup batches
select code,batch_date,description from civijournal_catchup where code = 'abc';

SELECT 
        cc.id, CONCAT_WS(" ", first_name, last_name) as name, 
        street_address, 
        supplemental_address_1, 
        supplemental_address_2, 
        city, 
        csp.abbreviation as State, 
        postal_code, 
        country.name as Country  
                 FROM   
      civicrm_contact cc   
        LEFT JOIN civicrm_address ca ON cc.id = ca.contact_id 
        LEFT JOIN civicrm_membership cm ON cc.id = cm.contact_id
        LEFT JOIN civicrm_state_province csp ON ca.state_province_id = csp.id
        LEFT JOIN civicrm_country country ON ca.country_id = country.id WHERE cm.membership_type_id IN ('1','8','2','13','3','4','9','5','7','10','11','12') AND cm.status_id IN ('5','2') AND cm.is_test = 0 AND cc.is_deleted = 0 AND is_primary = 1 AND NOT cc.id IN (
    SELECT target_contact_id FROM civicrm_option_value cov
        LEFT JOIN civicrm_activity activity ON activity.activity_type_id = cov.`value`
        LEFT JOIN civicrm_activity_target cat ON cat.activity_id = activity.id 
        LEFT JOIN civicrm_value_esa_journal_tracking_batch_id_14 tracking ON tracking.entity_id = activity.id
        WHERE cov.name = 'Tracked Journal'
            AND tracking.batch_id_101 = 'kmjpnz7mr'
)

ALTER TABLE civijournal_catchup ADD  count int not null;
ALTER TABLE civijournal_item ADD count int not null;