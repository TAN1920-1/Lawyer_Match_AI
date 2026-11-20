# Law Firm Module Rollout Plan

## Rollout Phases

### Phase 1: Database Migration
- Run `migration_firm_module.php` to add necessary columns and tables.
- **Rollback**: `ALTER TABLE user DROP COLUMN firm_id, DROP COLUMN is_active; DROP TABLE audit_logs;`

### Phase 2: Feature Flag Activation
- Current implementation uses role-based access control.
- To enable for specific firms, the `role` in `user` table must be set to `FIRM_ADMIN`.
- New firm registrations will automatically get `FIRM_ADMIN` role.

### Phase 3: Monitoring
- Check `audit_logs` for any unexpected actions.
- Monitor PHP error logs for any failed SQL queries or mail delivery errors.

## Rollback Procedure
1.  **Revert Role Update**: `UPDATE user SET role = 'Firm' WHERE role = 'FIRM_ADMIN';`
2.  **Code Revert**: Revert `db_login.php` and `firm_registation.php` to previous versions.
3.  **Data Preservation**: Soft-deleted lawyers can be restored by setting `is_active=1`.
4.  **Database Cleanup**: Optionally remove added columns if no data needs to be preserved.
