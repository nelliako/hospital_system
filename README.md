# Hospital Management System - Technical Documentation

An end-to-end web-based Hospital Management System designed for QMC doctors and administrators to securely access and manage patient data. Built on a modular LAMP stack using Docker containerization, the system is engineered to handle data scaling and seamless feature expansion.

---

## 1. Installation and System Architecture

### a. Installation Instructions
Follow these steps to deploy the application locally or on a production web server:

1. **Deploy Application Files**: Copy all application files to your web server's root directory (e.g., `/var/www/html` or your local Apache `htdocs` folder).
2. **Configure Database Connection**: Open `Includes/config.php` and verify/update the database hostname and credentials.
3. **Import Database Schema**: Upload and execute the `hospital_database.sql` file from the `Database` folder into your database management system to reconstruct the relational schema and initial data.
4. **Access the Application**: Open a web browser and navigate to the configured local address (e.g., `http://localhost:80`).

### b. System Overview
The system provides a robust, web-based platform tailored for clinical and administrative workflows. While the initial database deployment is lightweight, the architecture is decoupled to support continuous data scaling and modular feature additions. The core environment is built completely on the standard **LAMP (Linux, Apache, MySQL/MariaDB, PHP)** stack.

### c. Infrastructure and Network Architecture
The infrastructure comprises three interconnected services communicating over an isolated, internal Docker network. Specific ports are exposed for end-user interaction and database administration:

| Service Container | Protocol / Internal Port | Exposed Host Port | Purpose / Traffic Handled |
| :--- | :--- | :--- | :--- |
| **php-apache-1** | HTTP / 80 | `80` | Handles incoming HTTP traffic, processes form inputs, and renders dynamic HTML via PHP. |
| **mariadb-1** | MySQL / 3306 | `3306` (Internal) | Manages the core relational database system and handles SQL queries from the app. |
| **phpmyadmin-1** | HTTP / 80 | `8000` | Provides a web-based graphical user interface for database management and administration. |

<img width="830" height="361" alt="Screenshot 2026-05-18 at 10 36 43" src="https://github.com/user-attachments/assets/adcf5401-763b-48d1-b806-cf1c0e5b7531" />

*Figure 1: Infrastructure Network Topology and MySQL Communication Flow*

### d. File Organisation
The project maintains a structured, modular file organization mapped directly to the web server's document root (`/var/www/html` inside the container):

* **Root Directory (`/`)**: Contains primary user-facing pages, application logic handlers, and server configurations:
  * `prescribe.php`: Handles patient medication and test prescription workflows.
  * `add_doctor.php`: Administrative page for adding new medical personnel.
  * `add_test.php`: Allows creation of new diagnostic test profiles.
  * `error_page.php`: A generic error-handling template page.
  * `.htaccess`: Apache server configuration file for routing and directory controls.
* **Includes Directory (`/Includes/`)**: Stores globally reusable components, helper scripts, and application configuration files:
  * `config.php`: Manages core database connection string settings and environment variables.
  * `header.php` / `footer.php` / `left_menu`: Structural UI fragments ensuring a uniform look and feel across the platform.
  * `loginaction.php`: Processes authentication requests and contains login business logic.
  * `audit_trail.php`: Core security module containing utility functions called on pages executing CRUD operations to record user activity for audit logs.
* **CSS Directory (`/css/`)**: Houses stylesheet assets containing custom overrides and global themes.

---

## 2. Design Decisions

### a. Database Structure & Relational Schema
The relational database schema is strictly designed to adhere to the **Third Normal Form (3NF)** to eliminate data redundancy and preserve transactional integrity.

* Categorical attributes (such as gender, consultant status, and department names) are abstracted away from primary tables into dedicated lookup tables (`gender`, `consultant_status`, `department`).
* These values are referenced via explicit foreign keys rather than repetitive text strings, optimizing index sizes and ensuring consistency.

<img width="701" height="698" alt="Screenshot 2026-05-18 at 10 37 00" src="https://github.com/user-attachments/assets/102cc700-bc37-444a-b68b-009fee0a1bd9" />
*Figure 2: Relational Database Entity-Relationship Diagram (ERD)*

#### Parking Module Cardinality & Workflow Constraints
The parking management module implements strict data constraints to reflect real-world operational rules:
* **One Request Per Doctor**: The `parking_request` table utilizes the `doctorid` simultaneously as its **Primary Key (PK)** and **Foreign Key (FK)**. This unique constraint strictly guarantees that a single doctor can maintain at maximum one active request at any given time.
* **Linear Workflow Enforcement**: The foreign key relationships enforce a rigid sequential workflow lifecycle: `Doctor` → `Request` → `Permit`.
* **Approval Link Enforcement**: The `parking_permit` table links directly back to its originating request, programmatically preventing a permit from being generated without an existing, approved request.

#### Referential Integrity Constraints
To safeguard data against accidental orphaned records or corruption, explicit referential constraints are configured on all foreign keys:
* `ON UPDATE CASCADE`: Applied to relational identifiers. For example, if a doctor's `staffno` is updated, the change automatically ripples down to all associated child records (such as `patient_test`).
* `ON DELETE RESTRICT`: Implemented to lock out destructive deletions. For instance, a diagnostic test definition cannot be deleted from the system if it has already been prescribed to a patient.

### b. Frontend Styling Approach
The frontend interface leverages a clean, layered presentation model:
1. **Bootstrap 5 Framework**: Utilized as the primary framework layer to manage responsive grid systems, structural components, and form layouts.
2. **Custom Global CSS**: Overrides the default Bootstrap theme to apply QMC branding, unified corporate color palettes, and custom typography.
3. **Minimal Inline CSS**: Reserved exclusively for granular, page-specific layout corrections where external rules are impractical.

---

## 3. Key Data Flows

### a. PHP Pages Logic & Form Validation
All core application pages follow a defensive **"Check First, Act Later"** software design pattern. This architectural safeguard ensures state modifications only occur after comprehensive verification.

Using `prescribe.php` as a primary example, the processing workflow executes as follows:
1. **Session & Authentication Check**: Validates if the user session is active. Unauthenticated requests are immediately stopped and issued an HTTP redirect to the login page.
2. **Request Type Routing**: Detects the HTTP verb (`POST` vs `GET` with `action=delete`).
3. **Data Pre-Validation (Form Submission)**:
   * Verifies whether the target `patient` exists. If not found, throws a `"Patient not found"` error and dynamically renders an `"Add patient"` button.
   * Verifies whether the target `test` exists. If not found, throws a `"Test not found"` error and displays an `"Add test"` button.
4. **Execution & Error Catching**: Depending on the state, it attempts an `INSERT` or `UPDATE` operation inside a structured block to catch runtime database exceptions.

<img width="701" height="738" alt="Screenshot 2026-05-18 at 10 37 20" src="https://github.com/user-attachments/assets/f57496e3-73af-4c5d-a13e-382454f5ca6c" />

*Figure 3: Prescribe Page Workflow Logic Flowchart*

#### Deletion Workflow Lifecycle
When a user triggers a deletion via the UI:
* The system extracts and thoroughly validates URL parameter constraints (`pid`, `tid`, `date`).
* Before issuing a SQL `DELETE`, the application checks for potential relational integrity blockades.
* It explicitly catches specific database exceptions to block deletions of active prescriptions linked to current test results, mitigating structural data corruption risks.

### b. Auditing and Logging Infrastructure
To fulfill strict regulatory audit compliance requirements for health data, application pages never execute database mutation queries directly. Instead, they must proxy all operations through an audit middleware layer.

* **The `executeAndLog()` Function**: Defined globally within `Includes/audit_trail.php`, this function acts as an intermediary choke-point for all `INSERT`, `UPDATE`, and `DELETE` queries across the application layer.
* **Transactional Sequence**:
  1. An application page passes raw SQL statements along with metadata/context to `executeAndLog()`.
  2. The function attempts execution against the target database table (e.g., `.doctor`).
  3. If the query succeeds, an entry is written into the centralized `changes_log` table tracking the activity before returning a success state back to the caller page.
  4. If the query fails, changes are caught, no audit log is misattributed, and a `false` boolean is propagated.

<img width="588" height="721" alt="Screenshot 2026-05-18 at 10 37 46" src="https://github.com/user-attachments/assets/7f057773-8e10-4807-a622-6746d6c8f374" />  
*Figure 4: Audit Trail Service Data Flow Architecture*

* **Administrator Reporting**: The `audit_admin.php` control panel reads directly from the `changes_log` table, translating structural relational audit records into a clean, human-readable tabular interface for system auditors.

---

## 4. Extending Functionality & Future Roadmap

While the current deployment satisfies fundamental functional specifications, the system roadmap highlights security hardening as the absolute highest priority for future iterations:

1. **Cryptographic Password Hashing**: Transition away from plain-text credential storage immediately. Implement secure, modern cryptographic hashing algorithms by adopting PHP's native `password_hash()` function with a resilient hashing standard (e.g., Argon2id or bcrypt).
2. **Strict Input Sanitization & Validation**: Although parameterized prepared statements are currently active and mitigating structural SQL Injection (SQLi) risks, they should be supplemented with strict server-side form input validation rules (e.g., regex checks, type casting, length limits) to protect against auxiliary vulnerabilities like Cross-Site Scripting (XSS).
