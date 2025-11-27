-- Demo Data for Visitor Log System
-- This file contains sample data to populate the system for testing and demonstration

USE visitor_log_system;

-- Insert additional admin users (password: admin123)
INSERT INTO admins (username, email, password_hash, full_name, is_active) VALUES 
('john.doe', 'john.doe@company.com', '$2y$10$Hv/kr28C7m5Fu8r2b44.J.QcbD3.W0rObfmM82p49tveJZSZKejAy', 'John Doe', 1),
('sarah.wilson', 'sarah.wilson@company.com', '$2y$10$Hv/kr28C7m5Fu8r2b44.J.QcbD3.W0rObfmM82p49tveJZSZKejAy', 'Sarah Wilson', 1),
('mike.chen', 'mike.chen@company.com', '$2y$10$Hv/kr28C7m5Fu8r2b44.J.QcbD3.W0rObfmM82p49tveJZSZKejAy', 'Mike Chen', 1),
('lisa.garcia', 'lisa.garcia@company.com', '$2y$10$Hv/kr28C7m5Fu8r2b44.J.QcbD3.W0rObfmM82p49tveJZSZKejAy', 'Lisa Garcia', 1);

-- Insert sample visitors (checked in)
INSERT INTO visitors (visitor_name, visitor_email, visitor_phone, visitor_company, visit_date, visit_time, purpose_of_visit, host_person, host_department, id_document_type, id_document_number, status, created_by, created_at) VALUES 
('Alice Johnson', 'alice.johnson@techcorp.com', '+1-555-0101', 'TechCorp Solutions', '2024-01-15', '09:30:00', 'Business meeting with development team', 'John Doe', 'IT Development', 'national_id', 'ID123456789', 'checked_in', 1, '2024-01-15 09:25:00'),
('Robert Smith', 'robert.smith@innovate.com', '+1-555-0102', 'Innovate Inc', '2024-01-15', '10:15:00', 'Product demonstration and sales pitch', 'Sarah Wilson', 'Sales', 'passport', 'P987654321', 'checked_in', 2, '2024-01-15 10:10:00'),
('Maria Rodriguez', 'maria.rodriguez@designstudio.com', '+1-555-0103', 'Design Studio Pro', '2024-01-15', '11:00:00', 'Creative collaboration session', 'Mike Chen', 'Marketing', 'driver_license', 'DL456789123', 'checked_in', 3, '2024-01-15 10:55:00'),
('David Kim', 'david.kim@consulting.com', '+1-555-0104', 'Strategic Consulting', '2024-01-15', '13:30:00', 'Strategic planning workshop', 'Lisa Garcia', 'Operations', 'national_id', 'ID789123456', 'checked_in', 4, '2024-01-15 13:25:00'),
('Emily Brown', 'emily.brown@startup.com', '+1-555-0105', 'Startup Ventures', '2024-01-15', '14:00:00', 'Investment discussion and pitch', 'John Doe', 'Executive', 'passport', 'P123789456', 'checked_in', 1, '2024-01-15 13:55:00'),
('James Wilson', 'james.wilson@supplier.com', '+1-555-0106', 'Global Suppliers Ltd', '2024-01-15', '15:30:00', 'Supplier contract negotiation', 'Sarah Wilson', 'Procurement', 'national_id', 'ID456123789', 'checked_in', 2, '2024-01-15 15:25:00'),
('Sophia Lee', 'sophia.lee@partners.com', '+1-555-0107', 'Strategic Partners', '2024-01-15', '16:00:00', 'Partnership agreement signing', 'Mike Chen', 'Business Development', 'driver_license', 'DL789456123', 'checked_in', 3, '2024-01-15 15:55:00');

-- Insert sample visitors (checked out)
INSERT INTO visitors (visitor_name, visitor_email, visitor_phone, visitor_company, visit_date, visit_time, checkout_time, purpose_of_visit, host_person, host_department, id_document_type, id_document_number, status, created_by, created_at) VALUES 
('Michael Davis', 'michael.davis@client.com', '+1-555-0108', 'Client Services Co', '2024-01-14', '09:00:00', '11:30:00', 'Client consultation and requirements gathering', 'John Doe', 'IT Development', 'national_id', 'ID321654987', 'checked_out', 1, '2024-01-14 08:55:00'),
('Jennifer Taylor', 'jennifer.taylor@vendor.com', '+1-555-0109', 'Vendor Solutions', '2024-01-14', '10:00:00', '12:00:00', 'Vendor evaluation and product testing', 'Sarah Wilson', 'IT Operations', 'passport', 'P654321987', 'checked_out', 2, '2024-01-14 09:55:00'),
('Christopher Martinez', 'chris.martinez@consultant.com', '+1-555-0110', 'Business Consultants', '2024-01-14', '13:00:00', '16:00:00', 'Business process optimization review', 'Mike Chen', 'Operations', 'driver_license', 'DL321987654', 'checked_out', 3, '2024-01-14 12:55:00'),
('Amanda Thompson', 'amanda.thompson@auditor.com', '+1-555-0111', 'Audit & Compliance', '2024-01-14', '14:00:00', '17:00:00', 'Annual audit and compliance review', 'Lisa Garcia', 'Finance', 'national_id', 'ID987321654', 'checked_out', 4, '2024-01-14 13:55:00'),
('Daniel Anderson', 'daniel.anderson@contractor.com', '+1-555-0112', 'Contract Services', '2024-01-13', '08:00:00', '17:00:00', 'Office renovation and maintenance work', 'John Doe', 'Facilities', 'driver_license', 'DL654987321', 'checked_out', 1, '2024-01-13 07:55:00'),
('Jessica White', 'jessica.white@trainer.com', '+1-555-0113', 'Training Solutions', '2024-01-13', '09:00:00', '16:00:00', 'Employee training and development workshop', 'Sarah Wilson', 'Human Resources', 'passport', 'P321654987', 'checked_out', 2, '2024-01-13 08:55:00'),
('Kevin Johnson', 'kevin.johnson@inspector.com', '+1-555-0114', 'Safety Inspections', '2024-01-13', '10:00:00', '15:00:00', 'Workplace safety inspection and compliance check', 'Mike Chen', 'Safety & Compliance', 'national_id', 'ID654321987', 'checked_out', 3, '2024-01-13 09:55:00');

-- Insert sample visitors (cancelled)
INSERT INTO visitors (visitor_name, visitor_email, visitor_phone, visitor_company, visit_date, visit_time, purpose_of_visit, host_person, host_department, id_document_type, id_document_number, status, created_by, created_at) VALUES 
('Rachel Green', 'rachel.green@cancelled.com', '+1-555-0115', 'Cancelled Meeting Co', '2024-01-16', '10:00:00', 'Rescheduled meeting discussion', 'Lisa Garcia', 'Sales', 'national_id', 'ID123789654', 'cancelled', 4, '2024-01-15 16:00:00'),
('Thomas Moore', 'thomas.moore@postponed.com', '+1-555-0116', 'Postponed Ventures', '2024-01-16', '14:00:00', 'Project kickoff meeting', 'John Doe', 'Project Management', 'passport', 'P789654321', 'cancelled', 1, '2024-01-15 17:00:00');

-- Insert visitor logs for tracking activities
INSERT INTO visitor_logs (visitor_id, action, action_time, performed_by, notes) VALUES 
(1, 'check_in', '2024-01-15 09:30:00', 1, 'Visitor arrived on time for business meeting'),
(2, 'check_in', '2024-01-15 10:15:00', 2, 'Product demo session started'),
(3, 'check_in', '2024-01-15 11:00:00', 3, 'Creative collaboration meeting initiated'),
(4, 'check_in', '2024-01-15 13:30:00', 4, 'Strategic planning workshop commenced'),
(5, 'check_in', '2024-01-15 14:00:00', 1, 'Investment discussion meeting started'),
(6, 'check_in', '2024-01-15 15:30:00', 2, 'Supplier contract negotiation in progress'),
(7, 'check_in', '2024-01-15 16:00:00', 3, 'Partnership agreement signing ceremony'),
(8, 'check_in', '2024-01-14 09:00:00', 1, 'Client consultation session started'),
(8, 'check_out', '2024-01-14 11:30:00', 1, 'Client consultation completed successfully'),
(9, 'check_in', '2024-01-14 10:00:00', 2, 'Vendor evaluation session started'),
(9, 'check_out', '2024-01-14 12:00:00', 2, 'Vendor evaluation completed'),
(10, 'check_in', '2024-01-14 13:00:00', 3, 'Business process optimization review started'),
(10, 'check_out', '2024-01-14 16:00:00', 3, 'Business process review completed'),
(11, 'check_in', '2024-01-14 14:00:00', 4, 'Annual audit session started'),
(11, 'check_out', '2024-01-14 17:00:00', 4, 'Annual audit completed'),
(12, 'check_in', '2024-01-13 08:00:00', 1, 'Office renovation work started'),
(12, 'check_out', '2024-01-13 17:00:00', 1, 'Office renovation work completed for the day'),
(13, 'check_in', '2024-01-13 09:00:00', 2, 'Employee training workshop started'),
(13, 'check_out', '2024-01-13 16:00:00', 2, 'Employee training workshop completed'),
(14, 'check_in', '2024-01-13 10:00:00', 3, 'Workplace safety inspection started'),
(14, 'check_out', '2024-01-13 15:00:00', 3, 'Safety inspection completed'),
(15, 'check_in', '2024-01-16 10:00:00', 4, 'Meeting cancelled due to scheduling conflict'),
(16, 'check_in', '2024-01-16 14:00:00', 1, 'Project kickoff meeting postponed to next week');

-- Insert some visitors with vehicle information
UPDATE visitors SET vehicle_number = 'ABC-1234' WHERE id = 1;
UPDATE visitors SET vehicle_number = 'XYZ-5678' WHERE id = 3;
UPDATE visitors SET vehicle_number = 'DEF-9012' WHERE id = 5;
UPDATE visitors SET vehicle_number = 'GHI-3456' WHERE id = 7;

-- Insert visitors with notes
UPDATE visitors SET notes = 'VIP client - Provide premium service and refreshments' WHERE id = 2;
UPDATE visitors SET notes = 'Requires special access to design studio area' WHERE id = 3;
UPDATE visitors SET notes = 'Strategic partner - Executive meeting room preferred' WHERE id = 7;
UPDATE visitors SET notes = 'First-time visitor - Provide orientation and welcome package' WHERE id = 4;

-- Insert some visitors with different document types
UPDATE visitors SET id_document_type = 'passport', id_document_number = 'P123456789' WHERE id = 1;
UPDATE visitors SET id_document_type = 'driver_license', id_document_number = 'DL987654321' WHERE id = 6;
UPDATE visitors SET id_document_type = 'other', id_document_number = 'OTH-001' WHERE id = 8;

-- Insert some visitors for today (current date)
INSERT INTO visitors (visitor_name, visitor_email, visitor_phone, visitor_company, visit_date, visit_time, purpose_of_visit, host_person, host_department, id_document_type, id_document_number, status, created_by, created_at) VALUES 
('Today Visitor 1', 'today1@demo.com', '+1-555-0201', 'Today Corp', CURDATE(), '08:00:00', 'Morning meeting with CEO', 'John Doe', 'Executive', 'national_id', 'ID-TODAY-001', 'checked_in', 1, NOW()),
('Today Visitor 2', 'today2@demo.com', '+1-555-0202', 'Demo Company', CURDATE(), '09:30:00', 'Product demonstration', 'Sarah Wilson', 'Sales', 'passport', 'P-TODAY-002', 'checked_in', 2, NOW()),
('Today Visitor 3', 'today3@demo.com', '+1-555-0203', 'Test Solutions', CURDATE(), '10:00:00', 'Technical consultation', 'Mike Chen', 'IT Development', 'driver_license', 'DL-TODAY-003', 'checked_in', 3, NOW());

-- Insert visitor logs for today's visitors
INSERT INTO visitor_logs (visitor_id, action, action_time, performed_by, notes) VALUES 
((SELECT id FROM visitors WHERE visitor_name = 'Today Visitor 1' LIMIT 1), 'check_in', CONCAT(CURDATE(), ' 08:00:00'), 1, 'Early morning executive meeting'),
((SELECT id FROM visitors WHERE visitor_name = 'Today Visitor 2' LIMIT 1), 'check_in', CONCAT(CURDATE(), ' 09:30:00'), 2, 'Product demo for potential client'),
((SELECT id FROM visitors WHERE visitor_name = 'Today Visitor 3' LIMIT 1), 'check_in', CONCAT(CURDATE(), ' 10:00:00'), 3, 'Technical consultation session');

-- Insert some visitors for yesterday
INSERT INTO visitors (visitor_name, visitor_email, visitor_phone, visitor_company, visit_date, visit_time, checkout_time, purpose_of_visit, host_person, host_department, id_document_type, id_document_number, status, created_by, created_at) VALUES 
('Yesterday Visitor 1', 'yesterday1@demo.com', '+1-555-0301', 'Yesterday Corp', DATE_SUB(CURDATE(), INTERVAL 1 DAY), '09:00:00', '17:00:00', 'Full day workshop', 'John Doe', 'Training', 'national_id', 'ID-YEST-001', 'checked_out', 1, DATE_SUB(NOW(), INTERVAL 1 DAY)),
('Yesterday Visitor 2', 'yesterday2@demo.com', '+1-555-0302', 'Demo Yesterday', DATE_SUB(CURDATE(), INTERVAL 1 DAY), '14:00:00', '16:00:00', 'Afternoon meeting', 'Sarah Wilson', 'Marketing', 'passport', 'P-YEST-002', 'checked_out', 2, DATE_SUB(NOW(), INTERVAL 1 DAY));

-- Insert visitor logs for yesterday's visitors
INSERT INTO visitor_logs (visitor_id, action, action_time, performed_by, notes) VALUES 
((SELECT id FROM visitors WHERE visitor_name = 'Yesterday Visitor 1' LIMIT 1), 'check_in', CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), ' 09:00:00'), 1, 'Full day workshop started'),
((SELECT id FROM visitors WHERE visitor_name = 'Yesterday Visitor 1' LIMIT 1), 'check_out', CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), ' 17:00:00'), 1, 'Workshop completed successfully'),
((SELECT id FROM visitors WHERE visitor_name = 'Yesterday Visitor 2' LIMIT 1), 'check_in', CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), ' 14:00:00'), 2, 'Afternoon marketing meeting'),
((SELECT id FROM visitors WHERE visitor_name = 'Yesterday Visitor 2' LIMIT 1), 'check_out', CONCAT(DATE_SUB(CURDATE(), INTERVAL 1 DAY), ' 16:00:00'), 2, 'Marketing meeting completed');

-- Insert some visitors for last week
INSERT INTO visitors (visitor_name, visitor_email, visitor_phone, visitor_company, visit_date, visit_time, checkout_time, purpose_of_visit, host_person, host_department, id_document_type, id_document_number, status, created_by, created_at) VALUES 
('Last Week Visitor 1', 'lastweek1@demo.com', '+1-555-0401', 'Last Week Corp', DATE_SUB(CURDATE(), INTERVAL 7 DAY), '10:00:00', '15:00:00', 'Weekly review meeting', 'Mike Chen', 'Operations', 'driver_license', 'DL-LW-001', 'checked_out', 3, DATE_SUB(NOW(), INTERVAL 7 DAY)),
('Last Week Visitor 2', 'lastweek2@demo.com', '+1-555-0402', 'Demo Last Week', DATE_SUB(CURDATE(), INTERVAL 7 DAY), '13:00:00', '16:00:00', 'Project status update', 'Lisa Garcia', 'Project Management', 'national_id', 'ID-LW-002', 'checked_out', 4, DATE_SUB(NOW(), INTERVAL 7 DAY));

-- Insert visitor logs for last week's visitors
INSERT INTO visitor_logs (visitor_id, action, action_time, performed_by, notes) VALUES 
((SELECT id FROM visitors WHERE visitor_name = 'Last Week Visitor 1' LIMIT 1), 'check_in', CONCAT(DATE_SUB(CURDATE(), INTERVAL 7 DAY), ' 10:00:00'), 3, 'Weekly review meeting started'),
((SELECT id FROM visitors WHERE visitor_name = 'Last Week Visitor 1' LIMIT 1), 'check_out', CONCAT(DATE_SUB(CURDATE(), INTERVAL 7 DAY), ' 15:00:00'), 3, 'Weekly review completed'),
((SELECT id FROM visitors WHERE visitor_name = 'Last Week Visitor 2' LIMIT 1), 'check_in', CONCAT(DATE_SUB(CURDATE(), INTERVAL 7 DAY), ' 13:00:00'), 4, 'Project status update meeting'),
((SELECT id FROM visitors WHERE visitor_name = 'Last Week Visitor 2' LIMIT 1), 'check_out', CONCAT(DATE_SUB(CURDATE(), INTERVAL 7 DAY), ' 16:00:00'), 4, 'Project status update completed');

-- Display summary of inserted data
SELECT 'Demo Data Summary' as info;
SELECT 'Admins' as table_name, COUNT(*) as count FROM admins;
SELECT 'Visitors' as table_name, COUNT(*) as count FROM visitors;
SELECT 'Visitor Logs' as table_name, COUNT(*) as count FROM visitor_logs;

SELECT 'Visitors by Status' as status_info, status, COUNT(*) as count FROM visitors GROUP BY status;
SELECT 'Visitors by Department' as dept_info, host_department, COUNT(*) as count FROM visitors GROUP BY host_department ORDER BY count DESC;
