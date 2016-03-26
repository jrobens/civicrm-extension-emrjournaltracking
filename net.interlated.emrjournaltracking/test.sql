SELECT 
                contact_a.id                as contact_id,
                contact_a.sort_name         as sort_name,
                contact_a.contact_type      as contact_type,
                activity.id                 as activity_id,
                activity.activity_type_id   as activity_type_id,
                contact_b.sort_name         as source_contact,
                ov1.label                   as activity_type,
                activity.subject            as activity_subject,
                activity.activity_date_time as activity_date,
                ov2.label                   as activity_status,
                cca.case_id                 as case_id,
                activity.location           as location,
                activity.duration           as duration,
                activity.details            as details,
                assignment.activity_id      as assignment_activity,
                contact_c.display_name      as assignee
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