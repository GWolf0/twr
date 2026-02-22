You are in a laravel app project folder
I am using nonly blade (no inertia)
I want you to:
- check the pages in "/views/admin/page"
- check the partials of the admin pages in "/views/admin/partial", only check the partials related to the users table ("usersRecordsTable", "usersFilterForm", "userNewRecordForm", "userEditRecordForm")
- the partials related to "vehicles" and "bookings" are not implemented, based on how I previously implemented the "users" related partials you checked earlier, implement first the partials related to "vehicles", for the "media" column of the "vehicles" table use the component "/views/components/forms/vehicle-images-field.blade.php" for the creating and editing form, then implement the "bookings" partials as well
- now that all partials related to the admin user pages are implemented, update the pages in "/views/admin/page" to render the adequate partial based on the requested table