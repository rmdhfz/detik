# detik
Job Test:

Migration Service:
1. MigrationCustomer
2. MigrationMerchant
3. MigrationTransaction
4. CleanUpCustomer
5. CleanUpMerchant
6. CleanUpTransaction
7. MigrationDownCustomer
8. MigrationDownMerchant
9. MigrationDownTransaction
10. InsertDummyCustomer
11. InsertDummyMerchant
12. InsertDummyTransaction

API Service:
1. /api/create-transaction
2. /api/status-transaction

CLI Service: <br>
~ php transaction-cli.php {reference_id} {status}

example: <br>
~ php transaction-cli.php ef1561126488422c4ce4 paid

Docs: https://documenter.getpostman.com/view/4686021/UVJiiaLj

How to use ?

1. Migrate all tables
2. Insert dummy data:
    1. customer
    2. merchant
    3. transaction
3. already to use
