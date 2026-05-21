# CAPPOUCLA Web - Management System

## Project Overview
This is the web-based management system for **Caja de Ahorros del Personal de la Universidad Centroccidental Lisandro Alvarado (CAPPOUCLA)**. It is a legacy monolithic PHP application based on the "CatWin" framework (circa 2000-2006), adapted to run on modern PHP versions through a compatibility layer.

The system handles various administrative and financial operations including:
- **Accounting:** General ledger, balance sheets, and journal entries (Asientos).
- **Savings (Ahorros):** Contributions, withdrawals, and interest calculations.
- **Loans (Préstamos):** Loan requests, approvals, and payroll deductions.
- **Social Benefits:** Pharmacy, Rifa (raffle), funerary services, and medical assistance.
- **Reporting:** Extensive PDF generation for official documents.

## Main Technologies
- **Backend:** PHP (Legacy style, mixing logic and presentation).
- **Database:** MySQL (Connected via `mysqli` through `mysql_compat.php`).
- **Frontend:** HTML, Vanilla CSS, and JavaScript (including legacy jQuery 1.2.1 and custom AJAX wrappers).
- **Reporting:** [FPDF](http://www.fpdf.org/) for dynamic PDF generation.
- **Utilities:** [DataTables](https://datatables.net/) (Legacy version) for grid displays.

## Building and Running
As a PHP project, it does not require a compilation step. It is typically served via a web server (Apache/Nginx) with PHP support.

- **Local Development:** The project appears to be set up to run in a Docker environment (based on paths and `mysql-db` hostname in `final.php`).
- **Database Configuration:** Connection details are located in `final.php`.
- **Entry Points:** 
  - `index.php`: Main portal.
  - `indexm.php`: Likely a mobile or simplified version.
  - `conex.php`: Handles session and database initialization.

## Development Conventions
- **Core Logic:** A large portion of business logic and helper functions is centralized in `funciones.php` and `xfunciones.php`.
- **Database Access:** Use the legacy `mysql_*` function names; they are mapped to `mysqli_*` in `mysql_compat.php`.
- **File Naming:**
  - `*pdf.php`: Files dedicated to generating PDF reports.
  - `ajax*.js` / `ajax*.php`: Pairs handling asynchronous requests.
  - `sgcaf*`: Prefixes often used for database tables and related logic.
- **Versioning:** Manual versioning via filename suffixes is common (e.g., `file_2023.php`, `file_original.php`).
- **Language:** Code, comments, and variable names are primarily in Spanish.

## Architecture & Structure
- **Root Directory:** Contains most functional scripts (Controllers/Views).
- **Directories of Note:**
  - `api/`: Likely contains newer JSON-based endpoints.
  - `fpdf/`: FPDF library files.
  - `js/`, `css/`, `imagenes/`: Asset directories.
  - Module-specific folders: `farmacia/`, `rifa/`, `hospitalito/`, `funeraria/`.
