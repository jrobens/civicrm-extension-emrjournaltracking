delete from civicrm_cache         

-- From advanced export page
SELECT contact_a.id as contact_id, contact_a.contact_type  as `contact_type`, 
contact_a.contact_sub_type  as `contact_sub_type`, 
contact_a.sort_name  as `sort_name`, 
contact_a.display_name  as `display_name`, 
contact_a.do_not_email  as `do_not_email`, 
contact_a.do_not_phone  as `do_not_phone`, 
contact_a.do_not_mail  as `do_not_mail`, 
contact_a.do_not_sms  as `do_not_sms`, 
contact_a.do_not_trade  as `do_not_trade`, 
contact_a.is_opt_out  as `is_opt_out`, 
contact_a.legal_identifier  as `legal_identifier`, 
contact_a.external_identifier  as `external_identifier`, 
contact_a.nick_name  as `nick_name`, 
contact_a.legal_name  as `legal_name`, 
contact_a.image_URL  as `image_URL`, 
contact_a.preferred_mail_format  as `preferred_mail_format`, 
contact_a.first_name  as `first_name`, 
contact_a.middle_name  as `middle_name`, 
contact_a.last_name  as `last_name`, 
contact_a.job_title  as `job_title`, 
contact_a.birth_date  as `birth_date`, 
contact_a.is_deceased  as `is_deceased`, 
contact_a.deceased_date  as `deceased_date`, 
contact_a.household_name  as `household_name`, 
IF ( contact_a.contact_type = 'Individual', NULL, contact_a.organization_name ) as organization_name, 
contact_a.sic_code  as `sic_code`, 
contact_a.is_deleted  as `contact_is_deleted`, 
gender.value as gender_id, 
gender.label as gender, individual_prefix.value as individual_prefix_id, 
individual_prefix.label as individual_prefix, 
individual_suffix.value as individual_suffix_id, individual_suffix.label as individual_suffix, 
IF ( contact_a.contact_type = 'Individual', contact_a.organization_name, NULL ) as current_employer, 
civicrm_address.id as address_id, 
civicrm_address.street_address as `street_address`, 
civicrm_address.supplemental_address_1 as `supplemental_address_1`, 
civicrm_address.supplemental_address_2 as `supplemental_address_2`, 
civicrm_address.city as `city`, 
civicrm_address.postal_code_suffix as `postal_code_suffix`, 
civicrm_address.postal_code as `postal_code`, 
civicrm_address.geo_code_1 as `geo_code_1`, 
civicrm_address.geo_code_2 as `geo_code_2`, 
civicrm_state_province.id as state_province_id, 
civicrm_state_province.abbreviation as `state_province`, 
civicrm_state_province.name as state_province_name, 
civicrm_country.id as country_id, civicrm_country.name as `country`, 
civicrm_phone.id as phone_id, 
civicrm_phone.phone_type_id as phone_type_id, 
civicrm_phone.phone as `phone`, 
civicrm_email.id as email_id, 
civicrm_email.email as `email`, 
civicrm_email.on_hold as `on_hold`, 
civicrm_im.id as im_id, 
civicrm_im.provider_id as provider_id, 
civicrm_im.name as `im`, 
civicrm_worldregion.id as worldregion_id, 
civicrm_worldregion.name as `world_region`  
FROM civicrm_contact contact_a LEFT JOIN civicrm_address ON ( contact_a.id = civicrm_address.contact_id AND civicrm_address.is_primary = 1 ) 
LEFT JOIN civicrm_state_province ON civicrm_address.state_province_id = civicrm_state_province.id  
LEFT JOIN civicrm_country ON civicrm_address.country_id = civicrm_country.id  
LEFT JOIN civicrm_email ON (contact_a.id = civicrm_email.contact_id AND civicrm_email.is_primary = 1)  
LEFT JOIN civicrm_phone ON (contact_a.id = civicrm_phone.contact_id AND civicrm_phone.is_primary = 1)  
LEFT JOIN civicrm_im ON (contact_a.id = civicrm_im.contact_id AND civicrm_im.is_primary = 1)  
LEFT JOIN civicrm_worldregion ON civicrm_country.region_id = civicrm_worldregion.id  
LEFT JOIN civicrm_membership ON civicrm_membership.contact_id = contact_a.id  
LEFT JOIN civicrm_contribution_recur ccr ON ( civicrm_membership.contribution_recur_id = ccr.id ) 
LEFT JOIN civicrm_option_group option_group_gender ON (option_group_gender.name = 'gender') 
LEFT JOIN civicrm_option_value gender ON (contact_a.gender_id = gender.value AND option_group_gender.id = gender.option_group_id)  
LEFT JOIN civicrm_option_group option_group_prefix ON (option_group_prefix.name = 'individual_prefix') 
LEFT JOIN civicrm_option_value individual_prefix ON (contact_a.prefix_id = individual_prefix.value AND option_group_prefix.id = individual_prefix.option_group_id )  
LEFT JOIN civicrm_option_group option_group_suffix ON (option_group_suffix.name = 'individual_suffix') 
LEFT JOIN civicrm_option_value individual_suffix ON (contact_a.suffix_id = individual_suffix.value AND option_group_suffix.id = individual_suffix.option_group_id )  
WHERE  ( civicrm_membership.membership_type_id IN ('1','8','2','13','3','4','9','5','7','10','11','12') 
AND civicrm_membership.status_id IN ('5','2') 
AND civicrm_membership.is_test = 0 )  AND (contact_a.is_deleted = 0) 
AND contact_a.id IN ( 21483,21479,21024,20755,20236,20795,9,19994,21474,20771,18686,21015,21505,19925,20699,20479,20693,21473,19959,18682,21482,19927,21475,20065,20222,18690,20294,21517,21512,21481,20418,21127,18697,20145,20564 )    
GROUP BY contact_a.id  ORDER BY contact_a.sort_name asc, contact_a.id

