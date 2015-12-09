<?php

return [

	'parms_not_to_be_considered' => ['q', 'size', 'skip', 'fields', 'zip', 'radius','sort','sort_order'],


	'parms_to_be_considered' => ['type', 'network', 'acpt_new_pat', 'address1', 'anctest', 'board_certified', 'ccx', 'city', 'county', 'degree', 'dentemax', 'ehr', 'female', 'firstname', 'group_name', 'hosp', 'language', 'lastname', 'male', 'name', 'nationalproviderid', 'offcode', 'phone', 'practice_limitation', 'qrp', 'rpq', 'specialties', 'specialties_auto', 'hospitals', 'state', 'bid', 'name_auto', 'name_raw', 'gender'],

	'bool_needs_to_be_formatted' => ['male', 'female', 'dentemax', 'offcode', 'anctest', 'board_certified', 'hnjh_ind', 'hosp', 'rpq', 'ehr', 'qrp', 'ncqa', 'practice_limitation', 'acpt_new_pat', "ccx"],

	'dates_needs_to_be_formatted' => ['net_start_date', 'omt1_eff_dt', 'omt1_end_dt', 'omt2_eff_dt', 'omt2_end_dt', 'oat1_eff_dt', 'oat1_end_dt', 'ost2_eff_dt', 'ost2_end_dt', 'ost1_eff_dt', 'ost1_end_dt', 'oat2_eff_dt', 'oat2_end_dt', 'trad_eff_dt', 'trad_end_dt', 'mgcn_eff_dt', 'mgcn_end_dt', 'mcbl_eff_dt', 'mcbl_end_dt', 'advn_eff_dt', 'advn_end_dt', 'aco_eff_dt', 'aco_end_dt', 'pcmh_eff_dt', 'pcmh_end_dt', 'mbpc_eff_dt', 'mbpc_end_dt', 'brnb_eff_dt', 'brnb_end_dt', 'shmc_eff_dt', 'shmc_end_dt'],

	'network_needs_to_be_formatted' => ["mgcn", "trad", "stny", "njpl", "mcbl", "hdtc", "hddc", "hddp", "hddt", "mcny", "vstany", "nyep", "nypp", "advn", "aco", "pcmh", "mbpc", "brnb", "shmc", "omt1", "omt2", "oat1", "ost2", "ost1", "oat2"],



	'specialty_mapping' => ['1' => 'Acupuncture',
            '13' => 'Neurophysiology',
            '14' => 'Nuclear Cardiology',
            '149' => 'Surgery',
            '15' => 'Nurse',
            '150' => 'Vascular Surgery',
            '151' => 'Pediatric Surgery',
            '152' => 'Surgical Critical Care',
            '153' => 'Thoracic Surgery',
            '154' => 'Urology',
            '155' => 'Oral and Maxillofacial Surgery',
            '156' => 'Transplant Surgery',
            '158' => 'Chiropractic Medicine',
            '159' => 'Midwifery',
            '160' => 'Nurse Practitioner',
            '161' => 'Podiatry',
            '162' => 'Physician Assistant',
            '163' => 'Physical Therapy',
            '164' => 'Occupational Therapy',
            '165' => 'Optometry',
            '166' => 'Speech Therapy',
            '167' => 'Audiology',
            '168' => 'Dietician/Nutrition',
            '177' => 'Pediatric Allergy and Immunology',
            '178' => 'Pediatric Critical Care Medicine',
            '179' => 'Pediatric Cardiology',
            '180' => 'Pediatric Gastroenterology',
            '181' => 'Pediatric Hematology-Oncology',
            '182' => 'Pediatric Infectious Disease',
            '183' => 'Pediatric Nephrology',
            '184' => 'Neonatal-Perinatal Medicine',
            '185' => 'Pediatric Pulmonology',
            '186' => 'Pediatric Rheumatology',
            '187' => 'Pediatric Sports Medicine',
            '188' => 'Physical Medicine and Rehabilitation',
            '189' => 'Spinal Cord Injury Medicine',
            '19' => 'Social Worker',
            '190' => 'Plastic and Reconstructive Surgery',
            '191' => 'General Preventive Medicine',
            '194' => 'Occupational Medicine',
            '197' => 'Psychiatry',
            '199' => 'Neurology',
            '201' => 'Child and Adolescent Psychiatry',
            '202' => 'Clinical Neurophysiology',
            '210' => 'Pediatric Radiology',
            '213' => 'Radiology',
            '215' => 'Radiation Oncology',
            '220' => 'Clinical and Laboratory Immunology',
            '222' => 'Anesthesiology',
            '223' => 'Critical Care Medicine',
            '224' => 'Pain Management',
            '225' => 'Colon and Rectal Surgery',
            '226' => 'Dermatology',
            '228' => 'Dermatologic Immunology',
            '229' => 'Dermatopathology',
            '233' => 'Family Practice',
            '234' => 'Geriatric Medicine',
            '235' => 'Sports Medicine',
            '236' => 'Internal Medicine',
            '237' => 'Adolescent Medicine',
            '238' => 'Cardiac Electrophysiology',
            '240' => 'Cardiovascular Disease',
            '241' => 'Endocrinology  Diabetes and Metabolism',
            '242' => 'Endocrinology',
            '243' => 'Gastroenterology',
            '244' => 'Hematology',
            '245' => 'Interventional Cardiology',
            '246' => 'Infectious Disease',
            '247' => 'Nephrology',
            '248' => 'Allergy and Immunology',
            '249' => 'Oncology',
            '250' => 'Pulmonary Disease',
            '251' => 'Rheumatology',
            '252' => 'Genetics',
            '259' => 'Neurological Surgery',
            '260' => 'Nuclear Medicine',
            '261' => 'Obstetrics and Gynecology',
            '262' => 'Gynecologic Oncology',
            '263' => 'Maternal and Fetal Medicine',
            '264' => 'Reproductive Endocrinology',
            '265' => 'Ophthalmology',
            '266' => 'Orthopedic Surgery',
            '267' => 'Hand Surgery',
            '268' => 'Ear Nose and Throat (Otolaryngology)',
            '270' => 'Pediatric Otolaryngology',
            '276' => 'Blood Banking Transfusion Medicine',
            '301' => 'Pediatrics',
            '302' => 'Proctology',
            '304' => 'Psychology',
            '305' => 'Osteopathic Medicine',
            '306' => 'Pediatric Neurology',
            '327' => 'Pediatric Urology',
            '330' => 'Pediatric Endocrinology',
            '350' => 'Hearing Aid Supplier',
            '351' => 'Optician',
            '352' => 'Pediatric Neurodevelopmental Disabilities',
            '367' => 'Gynecology',
            '528' => 'Pediatric Rehabilitation Medicine',
            '567' => 'Pediatric Nutrition',
            '587' => 'Pediatric Orthopedic Surgery',
            '608' => 'Pediatric Genetics',
            '610' => 'Pediatric Ophthalmology',
            '617' => 'Certified Addictions Couselor (CAC)',
            '639' => 'Psychiatric Nurse',
            '659' => 'Licensed Professional Counselor',
            '660' => 'Licensed Marriage and Family Therapist',
            '663' => 'Certified Nurse Practitioner - Magellan',
            '700' => 'Critical Care Surgery',
            '701' => 'General Surgery',
            '703' => 'Cardiology',
            '704' => 'Cardiovascular Medicine',
            '705' => 'General Practice',
            '706' => 'Hepatology',
            '707' => 'Hyperbaric Medicine',
            '709' => 'Obstetrics and Gynecology',
            '710' => 'Plastic Surgery',
            '715' => 'Pulmonology',
            '716' => 'Cardiothoracic Surgery',
            '720' => 'Adolescent Medicine Physician',
            '727' => 'Surgical Oncologist',
            '729' => 'Maternal Fetal Medicine',
            '731' => 'Neonatology/Perinatology',
            '732' => 'Neonatology',
            '734' => 'Perinatology',
            '735' => 'Neurodevelopmental Pediatrician',
            '736' => 'Pediatric Child Development',
            '741' => 'Hematology/Oncology',
            '743' => 'Pediatric General Surgery',
            '748' => 'Repro Endocrinology Infertility',
            '750' => 'Allergy and Immunology',
            '754' => 'Gynecology Surgeon',
            '756' => 'Orthodontics',
            '758' => 'Laboratory',
            '766' => 'Certified Nurse Midwife',
            '770' => 'Clinical Nurse Specialist',
            '776' => 'Speech Pathologist',
            '777' => 'Counselor (Pastoral/Alcohol/Marriage)',
            '778' => 'Respiratory Therapist',
            '789' => 'Neuro Ophthalmology',
            '790' => 'Ophthalmology Surgeon',
            '791' => 'Radiation Therapy',
            '797' => 'Dietician/Nutrition',
            '801' => 'Registered Nurse (RN)',
            '802' => 'Social Worker - Doctoral (DSW)',
            '803' => 'Nurse (CNP)',
            '805' => 'Nurse (LPN)',
            '806' => 'Nurse (MSN)',
            '807' => 'Nurse (NP)',
            '808' => 'Nurse (RN)',
            '809' => 'Social Worker (ACSW)',
            '810' => 'Social Worker (CASW)',
            '811' => 'Social Worker (CSW)',
            '812' => 'Social Worker (DSW)',
            '813' => 'Social Worker (LCSW)',
            '814' => 'Social Worker (MA)',
            '815' => 'Social Worker (MSN)',
            '816' => 'Social Worker (PhD)',
            '821' => 'Registered Nurse First Assistant',
            '891' => 'Social Worker - Masters (MSW/ACSW)',
            '9' => 'Geriatrics',
            '931' => 'Toxicology',
            '935' => 'ABA Certified Therapist',
            '940' => 'Sleep Medicine',
            '941' => 'Thoracic Surgery',
            '942' => 'Palliative Medicine',
            '943' => 'Interventional Radiology',
            'ADOLESCENT MEDICINE' => 'Adolescent Medicine',
            'ADULT MEDICAL DAY CARE' => 'Adult Medical Day Care',
            'ALLERGY AND IMMUNOLOGY' => 'Allergy and Immunology',
            'ALLERGY-IMMUNOLOGY' => 'Allergy and Immunology',
            'AMBULANCE PROVIDER' => 'Ambulance Provider',
            'AMBULANCE PROVIDERS' => 'Ambulance Provider',
            'AMBULANCE VENDORS' => 'Ambulance Provider',
            'AMBULATORY SURGERY CENTER' => 'Ambulatory Surgery Center',
            'AMBULATORY SURGERY CENTERS' => 'Ambulatory Surgery Center',
            'AMBULATORYSURGERYCENTERS' => 'Ambulatory Surgery Center',
            'ANESTHESIOLOGY' => 'Anesthesiology',
            'ANGIOGRAPHY' => 'Angiography',
            'AUDIOLOGY' => 'Audiology',
            'BANDING CENTER' => 'Banding Center',
            'BIRTHING CENTER' => 'Birthing Center',
            'BONE DENSITY/DEXA' => 'Bone Density',
            'BONE DENSITY' => 'Bone Density',
            'BREAST MRI' => 'Breast MRI',
            'BREAST ULTRASOUND' => 'Breast Ultrasound',
            'CARDIAC CATHERIZATION' => 'Cardiac Catherization',
            'CARDIAC CTA' => 'Cardiac CTA',
            'CARDIAC ELECTROPHYSIOLOGY' => 'Cardiac Electrophysiology',
            'CARDIAC MONITORING' => 'Cardiac Monitoring',
            'CARDIAC SERVICES' => 'Cardiac Monitoring',
            'CARDIOVASCULAR DISEASE' => 'Cardiovascular Disease',
            'CHIROPRACTIC MEDICINE' => 'Chiropractic Medicine',
            'CLINICAL NEUROPHYSIOLOGY' => 'Clinical Neurophysiology',
            'COMPREHENSIVE OUTPATIENT REHABILITATION FACILITY' => 'Comprehensive Outpatient Rehabilitation',
            'CRITICAL CARE MEDICINE' => 'Critical Care Medicine',
            'CT ANGIOGRAPHY' => 'CT Angiography',
            'CT SCAN' => 'CT Scan',
            'DERMATOLOGY' => 'Dermatology',
            'DERMATOPATHOLOGY' => 'Dermatopathology',
            'DIABETIC EDUCATION CENTER' => 'Diabetic Education Center',
            'DIABETIC EDUCATION' => 'Diabetic Education',
            'DIABETIC EDUCATOR' => 'Diabetic Education',
            'DIALYSIS CENTERS' => 'Dialysis Provider',
            'DIALYSIS PROVIDER - HOSPITAL BASED' => 'Dialysis Provider - Hospital Based',
            'DIALYSIS PROVIDER' => 'Dialysis Provider',
            'DIALYSIS PROVIDERS' => 'Dialysis Provider',
            'DIETICIAN-NUTRITION' => 'Dietician/Nutrition',
            'DIGITAL MAMMOGRAPHY' => 'Digital Mammography',
            'DME - BONE GROWTH STIMULATORS' => 'DME-Bone Growth Stimulators',
            'DME - CPM MACHINES' => 'DME-CPM Machines',
            'DME - INSULIN PUMPS & RELATED SUPPLIES' => 'DME- Insulin Pumps & Related Supplies',
            'DME - INSULIN PUMPS AND RELATED SUPPLIES' => 'DME- Insulin Pumps & Related Supplies',
            'DME - MEDICAL SUPPLIES' => 'DME- Medical Supplies',
            'DME - SLEEP APNEA EQUIPMENT' => 'DME-Sleep Apnea Equipment',
            'DME - SPECIALTY BEDS/MATTRESSES' => 'DME-Specialty Beds/Mattresses',
            'DME - SPECIALTY/CUSTOM WHEELCHAIRS' => 'DME- Specialty/Custom Wheelchairs',
            'DME - SPECIALTY/MOBILITY SCOOTERS' => 'DME- Specialty/Power Mobility Scooters',
            'DME - SPECIALTY/POWER MOBILITY SCOOTERS' => 'DME- Specialty/Power Mobility Scooters',
            'DME - SPEECH GENERATING DEVICES' => 'DME- Speech Generating Devices',
            'DME - TELEMONITORING EQUIPMENT' => 'DME- Telemonitoring Equipment',
            'DME - TENS UNITS' => 'DME- TENS Units',
            'DME & O&P' => 'Durable Medical Equipment Supplier',
            'DME/O&P' => 'Durable Medical Equipment Supplier',
            'DME' => 'Durable Medical Equipment Supplier',
            'DURABLE MEDICAL EQUIPMENT SPECIALTY SUPPLIER' => 'Durable Medical Equipment Supplier',
            'DURABLE MEDICAL EQUIPMENT SUPPLIER' => 'Durable Medical Equipment Supplier',
            'DURABLE MEDICAL EQUIPMENT' => 'Durable Medical Equipment Supplier',
            'ECHOCARDIOGRAPHY' => 'Echocardiography',
            'EMERGENCY MEDICINE' => 'Emergency Medicine',
            'ENDOCRINOLOGY' => 'Endocrinology',
            'ENDOCRINOLOGY-DIABETES-METABOLISM' => 'Endocrinology',
            'ENDODONTICS' => 'Endodontics  Diabetes and Metabolism',
            'FAMILY PRACTICE' => 'Family Practice',
            'FAMILY-GENERAL PRACTICE' => 'Family-General Practice',
            'FEDERALLY QUALIFIED HEALTH CENTER' => 'Federally Qualified Health Center',
            'FLOUROSCOPY' => 'Fluoroscopy',
            'FLUOROSCOPY' => 'Fluoroscopy',
            'GASTROENTEROLOGY' => 'Gastroenterology',
            'GENERAL DENTISTRY' => 'General Dentistry',
            'GENERAL PREVENTIVE MEDICINE' => 'General Preventive Medicine',
            'GENETICS' => 'Genetics',
            'GERIATRIC MEDICINE' => 'Geriatric Medicine',
            'GYNECOLOGIC ONCOLOGY' => 'Gynecologic Oncology',
            'GYNECOLOGY' => 'Gynecology',
            'HEARING AID SUPPLIER' => 'Hearing Aid Supplier',
            'HEMATOLOGY' => 'Hematology',
            'HOME HEALTH & HOSPICE CENTERS' => 'Home Health and Hospice Centers',
            'HOME HEALTH & HOSPICE PROVIDERS' => 'Home Health and Hospice Centers',
            'HOME HEALTH CARE' => 'Home Health Care',
            'HOME HEALTH HOSPICE CENTERS' => 'Home Health and Hospice Centers',
            'HOME HEALTH' => 'Home Health Care',
            'HOME HEALTH-HOSPICE CENTERS' => 'Home Health and Hospice Centers',
            'HOME INFUSION' => 'Home Infusion',
            'HOME SLEEP TESTING' => 'Home Sleep Testing',
            'HOSPICE CENTER' => 'Hospice Centers',
            'HOSPICE CENTERS' => 'Hospice Centers',
            'HOSPICE' => 'Hospice',
            'HOSPITAL' => 'Hospital',
            'HOSPITAL-NONPAR' => 'Non-Par Hospital',
            'INFECTIOUS DISEASE' => 'Infectious Disease',
            'INFUSION THERAPY' => 'Home Infusion',
            'INPATIENT ACUTE REHABILITATION PROVIDER' => 'Inpatient Acute Rehabilitation Provider',
            'INTERNAL MEDICINE' => 'Internal Medicine',
            'INTERVENTIONAL RADIOLOGY' => 'Interventional Radiology',
            'LAB' => 'Laboratory',
            'LABORATORIES' => 'Laboratories',
            'LABORATORY PATIENT SERVICE CENTERS' => 'Laboratory Patient Service Centers',
            'LABORATORY PHYSICIAN ACCESS ONLY' => 'Laboratory Physician Access Only',
            'LABORATORY' => 'Laboratory',
            'LABORATORY-PATIENT CENTERS' => 'Laboratory-Patient Centers',
            'LABORATORY-PATIENT SERVICE CENTERS' => 'LABORATORY-PATIENT SERVICE CENTERS',
            'LABORATORY-PHYSICIAN ACCESS ONLY' => 'Laboratory-Physician Access Only',
            'LICENSED PROFESSIONAL COUNSELOR' => 'Licensed Professional Counselor',
            'LITHOTRIPSY CENTER' => 'Lithotripsy Center',
            'LITHOTRIPSY' => 'Lithotripsy',
            'MAMMOGRAPHY' => 'Mammography',
            'MATERNAL AND FETAL MEDICINE ' => 'Maternal and Fetal Medicine',
            'MATERNAL FETAL MEDICINE' => 'Maternal Fetal Medicine',
            'MATERNITY MANAGEMENT SERVICES' => 'Maternity Management Services',
            'MATERNITY MANAGEMENT' => 'Maternity Management',
            'MATERNTITY MANAGEMENT SERVICES' => 'MATERNTITY MANAGEMENT SERVICES',
            'MENTAL HEALTH & SUBSTANCE ABUSE PROVIDERS' => 'Mental Health And Substance Abuse Providers',
            'MENTAL HEALTH AND SUBSTANCE ABUSE PROVIDERS' => 'Mental Health And Substance Abuse Providers',
            'MENTAL HEALTH PROVIDER-INPATIENT' => 'Mental Health Provider  Inpatient',
            'MENTAL HEALTH PROVIDER-OUTPATIENT' => 'Mental Health Provider  Outpatient',
            'MIDWIFERY' => 'Midwifery',
            'MOBILE DIAGNOSTICS' => 'Mobile Diagnostics',
            'MRA' => 'MRA',
            'MRI' => 'MRI',
            'NEONATAL-PERINATAL MEDICINE' => 'Neonatal and Perinatal Medicine',
            'NEPHROLOGY' => 'Nephrology',
            'NEUROLOGY' => 'Neurology',
            'NUCLEAR CARDIAC - STRESS TEST' => 'Nuclear Cardiac Stress Test',
            'NUCLEAR CARDIOLOGY' => 'Nuclear Cardiology',
            'NUCLEAR MEDICINE' => 'Nuclear Medicine',
            'NURSE PRACTITIONER' => 'Nurse Practitioner',
            'OBSTETRICS AND GYNECOLOGY' => 'Obstetrics and Gynecology',
            'OBSTETRICS-GYNECOLOGY' => 'Obstetrics and Gynecology',
            'OCCUPATIONAL THERAPY' => 'Occupational Therapy',
            'ONCOLOGY' => 'Oncology',
            'OPEN MRI' => 'Open MRI',
            'OPHTHALMOLOGY' => 'Ophthalmology',
            'OPTOMETRY' => 'Optometry',
            'ORTHODONTICS' => 'Orthodontics',
            'ORTHOTICS AND PROSTHETICS - CRANIAL ORTHOSIS' => 'Orthotics and Prosthetics',
            'ORTHOTICS AND PROSTHETICS - MASTECTOMY & RELATED S' => 'Orthotics&Prosthetics- Mastectomy&Related Supplies',
            'ORTHOTICS AND PROSTHETICS - MASTECTOMY AND RELATED' => 'Orthotics&Prosthetics- Mastectomy&Related Supplies',
            'ORTHOTICS AND PROSTHETICS' => 'Orthotics and Prosthetics',
            'ORTHOTICS-PROSTHETICS' => 'Orthotics and Prosthetics',
            'OTOLARYNGOLOGY' => 'Otolaryngology',
            'OTOLOGY' => 'Otology',
            'OUTPATIENT REHABILITATION PROVIDER' => 'Outpatient Rehabilitation Provider',
            'OUTPATIENT REHABILITATION' => 'Outpatient Rehabilitation Provider',
            'PAIN MANAGEMENT' => 'Pain Management',
            'PATHOLOGY' => 'Pathology',
            'PEDIATRIC ALLERGY AND IMMUNOLOGY' => 'Pediatric Allergy and Immunology',
            'PEDIATRIC ALLERGY-IMMUNOLOGY' => 'Pediatric Allergy and Immunology',
            'PEDIATRIC CARDIOLOGY' => 'Pediatric Cardiology',
            'PEDIATRIC CRITICAL CARE MEDICINE' => 'Pediatric Critical Care Medicine',
            'PEDIATRIC DENTISTRY' => 'Pediatric Dentistry',
            'PEDIATRIC EMERGENCY MEDICINE' => 'Pediatric Emergency Medicine',
            'PEDIATRIC ENDOCRINOLOGY' => 'Pediatric Endocrinology',
            'PEDIATRIC GASTROENTEROLOGY' => 'Pediatric Gastroenterology',
            'PEDIATRIC GENETICS' => 'Pediatric Genetics',
            'PEDIATRIC HEMATOLOGY-ONCOLOGY' => 'Pediatric Hematology-Oncology',
            'PEDIATRIC INFECTIOUS DISEASE' => 'Pediatric Infectious Disease',
            'PEDIATRIC MEDICAL DAY CARE' => 'Pediatric Medical Day Care',
            'PEDIATRIC NEPHROLOGY' => 'Pediatric Nephrology',
            'PEDIATRIC NEURODEVELOPMENTAL DISABILITIES' => 'Pediatric Neuodevelopmental Disabilities',
            'PEDIATRIC NEUROLOGY' => 'Pediatric Neurology',
            'PEDIATRIC NUTRITION' => 'Pediatric Nutrition',
            'PEDIATRIC OPHTHALMOLOGY' => 'Pediatric Ophthalmology',
            'PEDIATRIC ORTHOPEDIC SURGERY' => 'Pediatric Orthopedic Surgery',
            'PEDIATRIC OTOLARYNGOLOGY' => 'Pediatric Otolaryngology',
            'PEDIATRIC PULMONOLOGY' => 'Pediatric Pulmonolgy',
            'PEDIATRIC RADIOLOGY' => 'Pediatric Radiology',
            'PEDIATRIC REHABILITATION MEDICINE' => 'Pediatric Rehabilitation',
            'PEDIATRIC RHEUMATOLOGY' => 'Pediatric Rheumatology',
            'PEDIATRIC SPORTS MEDICINE' => 'Pediatric Sports Medicine',
            'PEDIATRIC SURGERY' => 'Pediatric Surgery',
            'PEDIATRIC UROLOGY' => 'Pediatric Urology',
            'PEDIATRICS' => 'Pediatrics',
            'PERIODONTICS' => 'Periodontics',
            'PERSONAL CARE ASSISTANT' => 'Personal Care Assistant',
            'PET SCAN' => 'PET Scan',
            'PET/CT SCANNER' => 'PET/CT Scan',
            'PHYSIATRY' => 'Physiatry',
            'PHYSICAL MEDICINE AND REHABILITATION' => 'Physical Medicine and Rehabilitation',
            'PHYSICAL MEDICINE-REHABILITATION' => 'Physical Medicine and Rehabilitation',
            'PHYSICAL THERAPY' => 'Physical Therapy',
            'PHYSICIAN ASSISTANT' => 'Physician Assistant',
            'PODIATRY' => 'Podiatry',
            'PRIVATE DUTY NURSING' => 'Private Duty Nursing',
            'PROCTOLOGY' => 'Proctology',
            'PROSTHETICS & ORTHOTICS' => 'Orthotics and Prosthetics',
            'PROSTHETICS AND ORTHOTICS' => 'Prosthetics and Orthotics',
            'PROSTHODONTICS' => 'Prosthodontics',
            'PSYCHIATRY' => 'Psychiatry',
            'PSYCHOLOGY' => 'Psychology',
            'PULMONARY DISEASE' => 'Pulmonary Disease',
            'RADIATION ONCOLOGY' => 'Radiation Oncology',
            'RADIATION-ONCOLOGY' => 'Radiation Oncology',
            'RADIOLOGY IMAGING CENTERS' => 'Radiology Imaging Centers',
            'RADIOLOGY' => 'Radiology',
            'RADIOLOGY-ONCOLOGY' => 'Radiation Oncology',
            'REPRODUCTIVE ENDOCRINOLOGY' => 'Reproductive Endocrinology',
            'RETAIL HEALTH CENTER' => 'Retail Health Center',
            'RHEUMATOLOGY' => 'Rheumatology',
            'SKILLED NURSING FACILITIES' => 'Skilled Nursing Facilities',
            'SKILLED NURSING/SUBACUTE' => 'Skilled Nursing/Subacute',
            'SLEEP LABORATORY' => 'Sleep Laboratory',
            'SLEEP MEDICINE' => 'Sleep Medicine',
            'SLEEP STUDY' => 'Sleep Study',
            'SLEEPING DISORDERS' => 'Sleeping Disorders',
            'SOCIAL WORKER' => 'Social Worker',
            'SPECIALTY PHARMACY' => 'Specialty Pharmacy',
            'SPEECH THERAPY' => 'Speech Therapy',
            'SPEECH-HEARING AND CRANIOFACIAL' => 'Speech  Hearing and Craniofacial',
            'SPORTS MEDICINE' => 'Sports Medicine',
            'STAND-UP MRI' => 'Open MRI',
            'STEREOTACTIC BREAST BIOPSY' => 'Stereotactic Breast Biopsy',
            'SUBACUTE FACILITIES' => 'Subacute Facilities',
            'SUBSTANCE ABUSE PROVIDER-INPATIENT' => 'Substance Abuse Provider  Inpatient',
            'SUBSTANCE ABUSE PROVIDER-OUTPATIENT' => 'Substance Abuse Provider  Outpatient',
            'SURGERY-COLON-RECTAL' => 'Colon and Rectal Surgery',
            'SURGERY-GENERAL' => 'General Surgery',
            'SURGERY-HAND' => 'Hand Surgery',
            'SURGERY-NEUROLOGICAL' => 'Neurological Surgery',
            'SURGERY-ONCOLOGICAL' => 'Oncological Surgery',
            'SURGERY-ORAL AND MAXILLOFACIAL' => 'Surgery Oral and Maxillofacial',
            'SURGERY-ORAL-MAXILLOFACIAL' => 'Oral and Maxillofacial Surgery',
            'SURGERY-ORTHOPEDIC' => 'Orthopedic Surgery',
            'SURGERY-PLASTIC AND RECONSTRUCTIVE' => 'Plastic and Reconstructive Surgery',
            'SURGERY-PLASTIC-RECONSTRUCTIVE' => 'Plastic and Reconstructive Surgery',
            'SURGERY-THORACIC (CARDIOVASCULAR)' => 'Surgery Thoracic (Cardiovascular)',
            'SURGERY-TRANSPLANT' => 'Transplant Surgery',
            'SURGERY-VASCULAR' => 'Vascular Surgery',
            'SURGICAL CRITICAL CARE' => 'Surgical Critical Care',
            'TRANSITIONAL CARE UNIT' => 'Transitional Care Unit',
            'ULTRASOUND' => 'Ultrasound',
            'URGENT CARE CENTER' => 'Urgent Care Center',
            'UROLOGY' => 'Urology',
            'VENTILATOR SERVICES' => 'Ventilator Services',
            'WOUND CARE PROGRAMS' => 'Wound Care Programs',
            'WOUND CARE' => 'Wound Care Programs',
            'XRAY (GENERAL)' => 'XRAY',
            'XRAY' => 'XRAY',
            'RYAN WHITE' => 'Ryan White',
            'FAMILY PLANNING' => 'Family Planning',
            'INDIAN HEALTH PROVIDERS' => 'Indian Health Providers',
            'STD CLINICS' => 'STD Clinics',
            'TB CLINICS' => 'TB Clinics',
            'HEMOPHILIA TREATMENT CENTER' => 'Hemophilia Treatment Center',
            'BLACK LUNG CLINICS' => 'Black Lung Clinics',],

];