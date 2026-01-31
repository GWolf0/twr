# Info
- This is a laravel project
- The project is a two wheeler renting website
- "/App/Interfaces": holds a bunch of interfaces
- "/App/Helpers": holds a helper functions
- "/App/Services": holds a bunch of services (most are registered in app service provider)
- "/App/Types": defines common types (eg. MResponse, DOE)
- "/App/Misc": defines misclaneous things (espicially the enums)

# Todo
- Take a look at "/App/Services/CRUD/UserCRUDService.php", this file implements "ICRUDInterface", to define common crud operations on the User Model.
- I want you the following for each model:
    - write the relationship functions if possible.
    - defines the protected $with, for eager loading for each relationship.
    - just like with the User model and its "UserCRUDService", I need a CRUDService that implements "ICRUDInterface" for every model, since the User model already have its crud service skip it, make sure the CRUDService for the "Media" model uses the "FileUploadService" when possible, also make sure that the crud service of the "Booking" model uses the "BookingService" when possible.9