
-- membership number, name, line 1, line 2, line 3, city, state, code, country
-- supplemental_address3 doesn't seem to be used.
-- Could check join_date, start_date, end_date etc for 'is financial' or membership_status table?
select cc.id, CONCAT_WS(' ', first_name, last_name) as name, street_address, supplemental_address_1, supplemental_address_2, city, csp.abbreviation as State, postal_code, country.name as Country  
FROM civicrm_contact cc   
    LEFT JOIN civicrm_address ca ON cc.id = ca.contact_id 
    LEFT JOIN civicrm_membership cm ON cc.id = cm.contact_id
   LEFT JOIN civicrm_state_province csp ON ca.state_province_id = csp.id
    LEFT JOIN civicrm_country country ON ca.country_id = country.id

-- rules for 'journal eligible'
-- 1= standard member, 7 = life member
WHERE cm.membership_type_id IN (1,7)
AND is_primary = 1 -- primary addresses only

-- civicrm_membership_status


    SELECT 
                contact_a.id                as contact_id,
                contact_a.sort_name         as sort_name,
                contact_a.contact_type      as contact_type
   FROM   
        civicrm_contact contact_a



--                activity.id                 as activity_id,
 --               activity.activity_type_id   as activity_type_id,
 --               contact_b.sort_name         as source_contact,
--                ov1.label                   as activity_type,
--                activity.subject            as activity_subject,
--                activity.activity_date_time as activity_date,
--                ov2.label                   as activity_status,
--                cca.case_id                 as case_id,
--                activity.location           as location,
--                activity.duration           as duration,
--                activity.details            as details,
--                assignment.activity_id      as assignment_activity,
--                contact_c.display_name      as assignee
       
  --          JOIN civicrm_activity activity 
  --               ON contact_a.id = activity.source_contact_id
  --          JOIN civicrm_option_value ov1 
  --               ON activity.activity_type_id = ov1.value AND ov1.option_group_id = 2
  --          JOIN civicrm_option_value ov2 
  --               ON activity.status_id = ov2.value AND ov2.option_group_id = 25
  --          JOIN civicrm_contact contact_b 
  --               ON activity.source_contact_id = contact_b.id
 --           LEFT JOIN civicrm_case_activity cca 
 --                ON activity.id = cca.activity_id
 --           LEFT JOIN civicrm_activity_assignment assignment 
 --                ON activity.id = assignment.activity_id
--            LEFT JOIN civicrm_contact contact_c 
 --                ON assignment.assignee_contact_id = contact_c.id   ORDER BY contact_a.sort_name, activity.activity_date_time DESC, activity.activity_type_id, activity.status_id, activity.subject


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
        civicrm_contact contact_a
            JOIN civicrm_activity activity 
                 ON contact_a.id = activity.source_contact_id
            JOIN civicrm_option_value ov1 
                 ON activity.activity_type_id = ov1.value AND ov1.option_group_id = 2
            JOIN civicrm_option_value ov2 
                 ON activity.status_id = ov2.value AND ov2.option_group_id = 25
            JOIN civicrm_contact contact_b 
                 ON activity.source_contact_id = contact_b.id
            LEFT JOIN civicrm_case_activity cca 
                 ON activity.id = cca.activity_id
            LEFT JOIN civicrm_activity_assignment assignment 
                 ON activity.id = assignment.activity_id
            LEFT JOIN civicrm_contact contact_c 
                 ON assignment.assignee_contact_id = contact_c.id   ORDER BY contact_a.sort_name, activity.activity_date_time DESC, activity.activity_type_id, activity.status_id, activity.subject
