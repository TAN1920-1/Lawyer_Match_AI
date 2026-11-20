# Law Firm Module ERD

## Tables

### user
- `u_id`: VARCHAR(20) PK (e.g. 'Firm...', 'Lawyer...', 'Client...')
- `first_Name`: VARCHAR(100) (Name of user or firm)
- `email`: VARCHAR(100) UNIQUE
- `password`: VARCHAR(100)
- `status`: VARCHAR(100) ('Pending', 'Active', 'INACTIVE')
- `role`: VARCHAR(100) ('FIRM_ADMIN', 'FIRM_LAWYER', 'User', 'Admin')
- `firm_id`: VARCHAR(50) FK -> firm.firm_id (Optional, links lawyers to firm)
- `is_active`: TINYINT(1) (1=Active, 0=Soft-deleted)

### firm
- `firm_id`: VARCHAR(50) PK (Matches user.u_id for FIRM_ADMIN)
- `firm_name`: VARCHAR(255)
- `email`: VARCHAR(255)
- `contact`: VARCHAR(50)
- `address`: TEXT
- ...

### lawyer
- `lawyer_id`: VARCHAR(20) PK (Matches user.u_id)
- `contact_Number`: VARCHAR(15)
- `speciality`: VARCHAR(100)
- `case_handle`: VARCHAR(500) (Used for bar registration number)
- ...

### booking
- `booking_id`: INT PK
- `client_id`: VARCHAR(20) FK -> user.u_id
- `lawyer_id`: VARCHAR(20) FK -> user.u_id
- `status`: VARCHAR(20) ('Pending', 'Accepted', 'Completed', 'Cancelled')
- `date`: VARCHAR(20)
- ...

### audit_logs
- `id`: INT PK
- `action`: VARCHAR(255) (e.g. 'ADD_LAWYER', 'REMOVE_LAWYER', 'REASSIGN_BOOKING')
- `admin_id`: VARCHAR(50) FK -> user.u_id
- `target_id`: VARCHAR(50) (ID of lawyer or booking)
- `details`: TEXT
- `timestamp`: TIMESTAMP

## Relationships
- **FIRM_ADMIN** (in `user` table) owns one **firm** record via `firm_id`.
- **FIRM_LAWYER** (in `user` table) belongs to one **firm** record via `firm_id`.
- **FIRM_LAWYER** has a profile in the **lawyer** table.
- **booking** is linked to a **FIRM_LAWYER**.
- **FIRM_ADMIN** can view all **booking** records where the linked lawyer's `firm_id` matches their own.
