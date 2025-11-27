# Visitor Log System CMS

A comprehensive visitor management system built with PHP and MySQL, designed to help organizations efficiently track and manage visitor information with a modern, responsive interface.


## Project Overview

The Visitor Log System CMS is a robust, web-based visitor management solution designed for organizations of all sizes. It provides a comprehensive platform for tracking visitor entry and exit, managing visitor information, and generating detailed reports. The system features a modern, responsive interface built with Bootstrap 5 and offers real-time updates, advanced search capabilities, and secure authentication.

**Key Benefits:**
- Streamlined visitor check-in/check-out process
- Comprehensive visitor record management
- Real-time dashboard with live statistics
- Secure admin authentication system
- Mobile-responsive design for all devices
- Export and reporting capabilities
- Activity logging and audit trails

## Technologies Used

### Backend Technologies
- **PHP 7.4+**: Server-side scripting language
- **MySQL 5.7+**: Relational database management system
- **PDO**: Database abstraction layer for secure database operations
- **Session Management**: Secure user authentication and session handling

### Frontend Technologies
- **HTML5**: Semantic markup structure
- **CSS3**: Modern styling with custom CSS
- **JavaScript (ES6+)**: Client-side interactivity and AJAX functionality
- **Bootstrap 5**: Responsive CSS framework for mobile-first design
- **Font Awesome**: Icon library for enhanced user interface

### Development Tools & Standards
- **Git**: Version control system
- **Composer**: PHP dependency management (if applicable)
- **PSR-12**: PHP coding standards compliance
- **Responsive Design**: Mobile-first approach with progressive enhancement

### Security Technologies
- **Password Hashing**: Bcrypt algorithm for secure password storage
- **CSRF Protection**: Cross-site request forgery prevention
- **Input Validation**: Server-side input sanitization and validation
- **SQL Injection Prevention**: Prepared statements for all database queries
- **XSS Protection**: HTML encoding for output security

## Key Features

### Core Functionality
- **User Authentication**: Secure admin login with password hashing and session management
- **Visitor Management**: Complete CRUD operations for visitor records
- **Real-time Dashboard**: Live statistics and today's visitor overview
- **Advanced Search & Filtering**: Multi-criteria search with date ranges and status filters
- **Responsive Design**: Mobile-friendly interface using Bootstrap 5
- **Activity Logging**: Comprehensive audit trail for all visitor actions

### Advanced Features
- **Auto-refresh**: Real-time updates for dashboard and today's visitors
- **Export Functionality**: CSV export for visitor data
- **Print Support**: Optimized printing for visitor records and reports
- **Duration Tracking**: Automatic calculation of visit durations
- **CSRF Protection**: Security tokens for all form submissions
- **Input Validation**: Server-side validation and sanitization

### Security Features
- **Password Hashing**: Uses PHP's `password_hash()` with bcrypt
- **Session Management**: Secure session handling with timeout
- **Login Attempts**: Protection against brute force attacks
- **Remember Me**: Secure cookie-based authentication
- **Access Control**: Authentication required for all protected pages
- **Secure Headers**: X-Frame-Options, X-Content-Type-Options, X-XSS-Protection

## User Roles

### Administrator
**Primary Role**: System administrator with full access to all features

**Capabilities:**
- Complete visitor management (add, edit, view, delete)
- System configuration and settings
- User profile management
- Generate reports and analytics
- Export data in various formats
- Access to all system modules

**Access Level**: Full system access with administrative privileges

### System User
**Primary Role**: End user with limited access to visitor management

**Capabilities:**
- View visitor information
- Basic visitor operations (add, check-in/out)
- Limited reporting access
- Profile management

**Access Level**: Restricted access based on role permissions

## Project Structure

```
visitor_log_cms/
├── config/
│   └── database.php              # Database and app configuration
├── includes/
│   ├── bootstrap.php             # Application initialization
│   ├── Database.php              # Database connection class
│   ├── Admin.php                 # Admin authentication class
│   ├── Visitor.php               # Visitor management class
│   └── Utils.php                 # Utility functions
├── templates/
│   ├── header.php                # HTML header and navigation
│   └── footer.php                # HTML footer and scripts
├── assets/
│   ├── css/
│   │   └── style.css             # Custom CSS styles
│   ├── js/
│   │   └── app.js                # JavaScript functionality
│   └── images/                   # Image assets
├── uploads/                      # File upload directory
├── logs/                         # Error logs (auto-created)
├── index.php                     # Main entry point
├── login.php                     # Login page
├── logout.php                    # Logout handler
├── dashboard.php                 # Main dashboard
├── add_visitor.php               # Add new visitor form
├── edit_visitor.php              # Edit visitor form
├── view_visitor.php              # Visitor details page
├── checkout_visitor.php          # Visitor checkout page
├── delete_visitor.php            # Delete visitor handler
├── visitors.php                  # All visitors listing
├── today_visitors.php            # Today's visitors page
├── reports.php                   # Reports and analytics
├── settings.php                  # System settings
├── profile.php                   # User profile management
├── visitor_log_system.sql        # Database schema
└── README.md                     # This documentation
```

### Key Classes and Functions

#### Database Class (`includes/Database.php`)
- `connect()`: Establish database connection
- `query()`: Execute prepared statements
- `fetchAll()`: Retrieve multiple records
- `fetch()`: Retrieve single record
- `execute()`: Execute insert/update/delete operations

#### Admin Class (`includes/Admin.php`)
- `login()`: Authenticate admin user
- `isLoggedIn()`: Check authentication status
- `logout()`: End user session
- `createAdmin()`: Create new admin user
- `updatePassword()`: Change admin password

#### Visitor Class (`includes/Visitor.php`)
- `addVisitor()`: Create new visitor record
- `updateVisitor()`: Modify visitor information
- `checkOutVisitor()`: Process visitor checkout
- `getVisitors()`: Retrieve visitor listings with filters
- `getVisitorStats()`: Generate visitor statistics

#### Utils Class (`includes/Utils.php`)
- `sanitizeInput()`: Clean and validate input data
- `formatDate()`: Format dates for display
- `uploadFile()`: Handle file uploads
- `generatePagination()`: Create pagination HTML

## Setup Instructions

### System Requirements

#### Server Requirements
- **PHP**: Version 7.4 or higher (PHP 8.0+ recommended)
- **MySQL**: Version 5.7 or higher (MySQL 8.0+ recommended)
- **Web Server**: Apache 2.4+ or Nginx 1.18+
- **Memory**: Minimum 128MB RAM (512MB recommended)
- **Storage**: Minimum 50MB disk space

#### PHP Extensions
- PDO MySQL
- Session
- JSON
- Filter
- Hash

#### Browser Support
- Chrome 90+
- Firefox 88+
- Safari 14+
- Edge 90+
- Mobile browsers (iOS Safari, Chrome Mobile)

### Installation Steps

#### Step 1: Download and Extract
```bash
# Download the system files
git clone https://github.com/soikot-shahriaar/visitor-log-cms.git
cd visitor-log-cms

# Or extract from ZIP file
unzip visitor-log-cms.zip
cd visitor-log-cms
```

#### Step 2: Web Server Setup

**Apache Configuration**
1. Copy files to your web root directory (e.g., `/var/www/html/visitor-log/`)
2. Ensure Apache has read/write permissions:
```bash
sudo chown -R www-data:www-data /var/www/html/visitor-log/
sudo chmod -R 755 /var/www/html/visitor-log/
sudo chmod -R 777 /var/www/html/visitor-log/uploads/
```

3. Create `.htaccess` file in the root directory:
```apache
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule ^(.*)$ index.php [QSA,L]

# Security headers
Header always set X-Content-Type-Options nosniff
Header always set X-Frame-Options DENY
Header always set X-XSS-Protection "1; mode=block"

# Prevent access to sensitive files
<Files "*.php">
    Order allow,deny
    Allow from all
</Files>

<FilesMatch "\.(sql|log|md)$">
    Order deny,allow
    Deny from all
</FilesMatch>
```

#### Step 3: Database Setup
```sql
-- Connect to MySQL as root
mysql -u root -p

-- Create database and user
CREATE DATABASE visitor_log_system;
CREATE USER 'visitor_user'@'localhost' IDENTIFIED BY 'secure_password';
GRANT ALL PRIVILEGES ON visitor_log_system.* TO 'visitor_user'@'localhost';
FLUSH PRIVILEGES;

-- Import the database structure
mysql -u visitor_user -p visitor_log_system < visitor_log_system.sql
```

#### Step 4: Configuration
Edit `config/database.php` to match your database settings:

```php
// Database configuration constants
define('DB_HOST', 'localhost');
define('DB_NAME', 'visitor_log_system');
define('DB_USER', 'visitor_user');
define('DB_PASS', 'your_secure_password');
define('DB_CHARSET', 'utf8mb4');

// Application configuration
define('APP_NAME', 'Visitor Log System');
define('APP_VERSION', '1.0.0');
// Set APP_URL without port number (e.g., http://localhost/project_name or http://localhost)
define('APP_URL', 'http://localhost/visitor_log_cms');
```

**Note**: The `APP_URL` should be set to `http://localhost/project_name` (without any port number like :8080). If your project is in the web root, use `http://localhost` instead.

## Demo User Data

The system comes with comprehensive demo data to help you get started quickly and explore all features. The demo data includes sample admin users, visitors, and activity logs.

### Default Admin Users

After installation, you'll have access to these demo admin accounts:

| Username | Email | Password | Full Name | Role |
|----------|-------|----------|-----------|------|
| `admin` | `admin@company.com` | `admin123` | System Administrator | Full Access |
| `john.doe` | `john.doe@company.com` | `admin123` | John Doe | Full Access |
| `sarah.wilson` | `sarah.wilson@company.com` | `admin123` | Sarah Wilson | Full Access |
| `mike.chen` | `mike.chen@company.com` | `admin123` | Mike Chen | Full Access |
| `lisa.garcia` | `lisa.garcia@company.com` | `admin123` | Lisa Garcia | Full Access |

**⚠️ Security Note**: Change all default passwords immediately after first login for production use.

### Sample Visitor Data

The demo includes realistic visitor data across different scenarios:

#### Current Day Visitors (Checked In)
- **Alice Johnson** - TechCorp Solutions - Business meeting with development team
- **Robert Smith** - Innovate Inc - Product demonstration and sales pitch
- **Maria Rodriguez** - Design Studio Pro - Creative collaboration session
- **David Kim** - Strategic Consulting - Strategic planning workshop
- **Emily Brown** - Startup Ventures - Investment discussion and pitch
- **James Wilson** - Global Suppliers Ltd - Supplier contract negotiation
- **Sophia Lee** - Strategic Partners - Partnership agreement signing

#### Previous Day Visitors (Checked Out)
- **Michael Davis** - Client Services Co - Client consultation (9:00 AM - 11:30 AM)
- **Jennifer Taylor** - Vendor Solutions - Vendor evaluation (10:00 AM - 12:00 PM)
- **Christopher Martinez** - Business Consultants - Process optimization (1:00 PM - 4:00 PM)
- **Amanda Thompson** - Audit & Compliance - Annual audit (2:00 PM - 5:00 PM)
- **Daniel Anderson** - Contract Services - Office renovation (8:00 AM - 5:00 PM)
- **Jessica White** - Training Solutions - Employee training (9:00 AM - 4:00 PM)
- **Kevin Johnson** - Safety Inspections - Safety compliance check (10:00 AM - 3:00 PM)

#### Cancelled/Postponed Visits
- **Rachel Green** - Cancelled Meeting Co - Rescheduled meeting discussion
- **Thomas Moore** - Postponed Ventures - Project kickoff meeting (postponed)

### Demo Data Features

#### Realistic Scenarios
- **Business Meetings**: Various types of corporate meetings and consultations
- **Product Demonstrations**: Sales pitches and vendor evaluations
- **Training Sessions**: Employee development and safety workshops
- **Strategic Planning**: Executive meetings and partnership discussions
- **Compliance Activities**: Audits, inspections, and regulatory reviews

#### Data Variety
- **Different Document Types**: National ID, Passport, Driver's License, Other
- **Vehicle Information**: Sample vehicle numbers for relevant visitors
- **Department Assignments**: IT, Sales, Marketing, Operations, Finance, HR, etc.
- **Time Variations**: Morning, afternoon, and full-day appointments
- **Status Diversity**: Checked in, checked out, and cancelled visits

#### Activity Logging
- Complete check-in and check-out timestamps
- Admin user tracking for all actions
- Detailed notes for each activity
- Real-time status updates

### Loading Demo Data

To populate your system with demo data:

1. **Automatic Setup**: Demo data is included in the main installation SQL file
2. **Manual Import**: If needed, run the demo data separately:
   ```bash
   mysql -u your_username -p your_database < sql/demo_data.sql
   ```

3. **Verify Installation**: Check that demo users and visitors appear in the system

### Customizing Demo Data

You can modify the demo data by editing `sql/demo_data.sql`:

- **Add More Users**: Insert additional admin accounts
- **Modify Visitors**: Change company names, purposes, or other details
- **Adjust Timestamps**: Update dates and times to match your testing needs
- **Add Departments**: Include your organization's specific departments

### Demo Data Cleanup

For production deployment, you may want to remove demo data:

```sql
-- Remove demo admin users (keep the main admin)
DELETE FROM admins WHERE username != 'admin';

-- Remove all demo visitors
DELETE FROM visitors WHERE id > 1;

-- Clear visitor logs
DELETE FROM visitor_logs WHERE id > 1;

-- Reset auto-increment counters
ALTER TABLE admins AUTO_INCREMENT = 2;
ALTER TABLE visitors AUTO_INCREMENT = 2;
ALTER TABLE visitor_logs AUTO_INCREMENT = 2;
```

## Usage

### First Login
1. Navigate to your installation URL
2. Use default credentials:
   - **Username**: `admin`
   - **Email**: `admin@company.com`
   - **Password**: `admin123`
3. **Important**: Change the default password immediately after first login

### Dashboard Overview
The dashboard provides:
- **Statistics Cards**: Today's visitors, currently checked in, monthly total, and overall total
- **Quick Actions**: Direct links to common tasks
- **Today's Visitors**: Recent visitor activity
- **System Information**: Current user and system status

### Managing Visitors

#### Adding a New Visitor
1. Click "Add New Visitor" from dashboard or navigation
2. Fill in required fields:
   - Visitor Name (required)
   - Visit Date (required)
   - Visit Time (required)
   - Purpose of Visit (required)
   - Host Person (required)
3. Optional information:
   - Contact details (email, phone)
   - Company/Organization
   - ID document information
   - Vehicle number
   - Additional notes

#### Viewing Visitor Details
1. Navigate to "All Visitors" or "Today's Visitors"
2. Click the eye icon next to any visitor
3. View complete information and activity log
4. Access quick actions (edit, check out, delete)

#### Checking Out Visitors
1. From visitor details or listings, click "Check Out"
2. Set check-out time (defaults to current time)
3. Add optional check-out notes
4. Confirm to complete the process

#### Searching and Filtering
Use the search and filter options to find specific visitors:
- **Text Search**: Name, email, company, or host person
- **Date Range**: Filter by visit dates
- **Status Filter**: Checked in, checked out, or cancelled
- **Host Filter**: Filter by specific host person

### Export and Reporting
- **CSV Export**: Click "Export CSV" on any visitor listing
- **Print**: Use "Print" button for formatted printouts
- **Activity Logs**: View detailed action history for each visitor

### User Management
Currently supports single admin user. To add more admins:
1. Use the `Admin::createAdmin()` method programmatically
2. Or insert directly into the database with hashed passwords

## Intended Use

### Primary Use Cases
- **Corporate Offices**: Track visitors entering office buildings
- **Educational Institutions**: Monitor campus visitors and guests
- **Healthcare Facilities**: Manage patient visitors and family members
- **Government Buildings**: Control access to secure facilities
- **Event Venues**: Track attendees and manage capacity
- **Retail Stores**: Monitor customer traffic and demographics

### Target Organizations
- Small to medium businesses (SMBs)
- Educational institutions
- Healthcare organizations
- Government agencies
- Non-profit organizations
- Event management companies
- Security companies

### Benefits for Organizations
- **Enhanced Security**: Track who enters and exits your premises
- **Compliance**: Maintain visitor logs for regulatory requirements
- **Analytics**: Understand visitor patterns and peak times
- **Efficiency**: Streamline visitor check-in processes
- **Professional Image**: Modern, organized visitor management
- **Data Management**: Centralized storage of visitor information

### Implementation Scenarios
- **Reception Desks**: Front desk staff can quickly register visitors
- **Security Checkpoints**: Security personnel can verify visitor credentials
- **Mobile Access**: Staff can register visitors from tablets or mobile devices
- **Multi-location**: Deploy across multiple sites with centralized management
- **Integration**: Connect with existing access control systems

## License

# 📄 License
**License for RiverTheme**
RiverTheme makes this project available for demo, instructional, and personal use. You can ask for or buy a license from [RiverTheme.com](https://RiverTheme.com) if you want a pro website, sophisticated features, or expert setup and assistance. A Pro license is needed for production deployments, customizations, and commercial use.

**Disclaimer**
The free version is offered "as is" with no warranty and might not function on all devices or browsers. It might also have some coding or security flaws. For additional information or to get a Pro license, please get in touch with [RiverTheme.com](https://RiverTheme.com).

---

**Visitor Log System CMS** - Efficient visitor management for modern organizations.