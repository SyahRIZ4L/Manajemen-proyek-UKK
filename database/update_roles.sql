-- Update members table role column to support new role system
ALTER TABLE members MODIFY COLUMN role ENUM('Project_Admin', 'Team_Lead', 'Developer', 'Designer', 'Member') NOT NULL DEFAULT 'Member';

-- Update existing 'admin' role to 'Team_Lead' (if any)
UPDATE members SET role = 'Team_Lead' WHERE role = 'admin';

-- Display updated roles
SELECT DISTINCT role FROM members;
