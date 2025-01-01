 
Workflow Overview 

Applications and Requirements 
• A User Application is initiated when a supervisor, Global SUpervisor or admin selects the authorisations for the 
user, outlining the specific authorisations needed from authorisations table. The system then identifies the associated requirements for these authorisations. 
• The requirements modules (elearning), f2fs (face-toface training), inducstions (f2f or elearning) and licenses, are mapped 
to the relevant authorisations. These requirements are tracked through the applications, user_authorisations and user_auth_requisites tables, which indicates their completion progress status (e.g., pending, completed). 

Detailed Steps from Application to Certification 
1. Coordinator Creates Application:
a. The coordinator selects the necessary authorisations for the user via /autorisations/registration.blade.php. 
b. The system looks up the Prerequisite Map tables to check which requirements are linked to each authorisation via applications, user_authorisations and user_auth_requisites. 
c. The supervisor, Global SUpervisor or admin endorses the application, which enters a "pending" status. 
2. User Receives Notifications: 
a. The system sends notifications to the user about the requirements they need 
to complete. For example:  
i. "You need to complete a Confined Space Entry Training session." 
ii. "You need to submit a medical clearance for Confined Space Entry." 
iii. The user also receives reminders if certain requirements are overdue. 
3. User Completes Requirements: 
a. The user completes the necessary training (modules/elearning or f2f), medical clearance, inductions, or other 
requirements. 
b. Upon completion, the system updates the user_auth_requisite and user_authoristion tables for each 
requirement associated with the application. 
4. Application Completeness Check: 
a. The system continuously monitors the status of the user’s requirements. If a 
requirement is still pending, the application remains in a "pending" status 
and cannot be forwarded for approval. 
b. Once all requirements for the authorisations, application are completed, the system 
automatically updates the application status to "ready for approval." 
5. Approval by Manager: 
a. The application is forwarded to the Manager for approval. The manager 
reviews the completed requirements and, if everything is in order, approves 
the application. 
b. If a requirement is missing or incomplete, the application is rejected or sent 
back for rework. 
6. Issuance of Certification: 
a. Upon manager approval, the system grants a certificate, marking the 
authorisation as complete in the user_authorisations table and application completed. 
b. The certificate can be issued digitally or printed, and the user’s record is 
updated with the new authorisation. 
