# Struktur File Sistem Laravel App - Sanggar Seni

## Daftar Lengkap File dan Direktori (Core & Support)

### Root Directory Files
```
laravel-app-2/
├── artisan
├── composer.json
├── composer.lock
├── package.json
├── package-lock.json
├── README.md
├── phpunit.xml
├── vite.config.js
├── tailwind.config.js
├── postcss.config.js
├── .gitignore
├── .gitattributes
├── .editorconfig
└── .env.example
```

### app/ - Aplikasi Utama
- 📂 **Console**
  - 📂 **Commands**
    - 📄 [AutoCompleteEvents.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Console/Commands/AutoCompleteEvents.php)
    - 📄 [StressTestCommand.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Console/Commands/StressTestCommand.php)
- 📂 **Http**
  - 📂 **Controllers**
    - 📂 **Admin**
      - 📄 [BookingController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Admin/BookingController.php)
      - 📄 [CancellationController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Admin/CancellationController.php)
      - 📄 [CmsController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Admin/CmsController.php)
      - 📄 [CostumeController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Admin/CostumeController.php)
      - 📄 [EventController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Admin/EventController.php)
      - 📄 [FinancialController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Admin/FinancialController.php)
      - 📄 [PaymentController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Admin/PaymentController.php)
      - 📄 [PersonnelController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Admin/PersonnelController.php)
      - 📄 [RehearsalController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Admin/RehearsalController.php)
      - 📄 [ServiceCatalogController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Admin/ServiceCatalogController.php)
    - 📂 **Auth**
      - 📄 [AuthenticatedSessionController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Auth/AuthenticatedSessionController.php)
      - 📄 [ConfirmablePasswordController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Auth/ConfirmablePasswordController.php)
      - 📄 [EmailVerificationNotificationController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Auth/EmailVerificationNotificationController.php)
      - 📄 [EmailVerificationPromptController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Auth/EmailVerificationPromptController.php)
      - 📄 [NewPasswordController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Auth/NewPasswordController.php)
      - 📄 [PasswordController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Auth/PasswordController.php)
      - 📄 [PasswordResetLinkController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Auth/PasswordResetLinkController.php)
      - 📄 [RegisteredUserController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Auth/RegisteredUserController.php)
      - 📄 [VerifyEmailController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Auth/VerifyEmailController.php)
    - 📂 **Klien**
      - 📄 [BookingController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Klien/BookingController.php)
    - 📂 **Personnel**
      - 📄 [AttendanceController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Personnel/AttendanceController.php)
      - 📄 [FinancialController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Personnel/FinancialController.php)
      - 📄 [PersonnelProfileController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Personnel/PersonnelProfileController.php)
      - 📄 [PersonnelUnavailabilityController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Personnel/PersonnelUnavailabilityController.php)
    - 📄 [AuthController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/AuthController.php)
    - 📄 [Controller.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/Controller.php)
    - 📄 [ProfileController.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Controllers/ProfileController.php)
  - 📂 **Middleware**
    - 📄 [EnsurePersonnelIsActive.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Middleware/EnsurePersonnelIsActive.php)
    - 📄 [RoleMiddleware.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Middleware/RoleMiddleware.php)
  - 📂 **Requests**
    - 📂 **Auth**
      - 📄 [LoginRequest.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Requests/Auth/LoginRequest.php)
    - 📄 [ProfileUpdateRequest.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Http/Requests/ProfileUpdateRequest.php)
- 📂 **Models**
  - 📄 [Booking.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/Booking.php)
  - 📄 [Cancellation.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/Cancellation.php)
  - 📄 [CostumeRental.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/CostumeRental.php)
  - 📄 [CostumeUsage.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/CostumeUsage.php)
  - 📄 [CostumeVendor.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/CostumeVendor.php)
  - 📄 [Event.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/Event.php)
  - 📄 [EventLog.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/EventLog.php)
  - 📄 [FeeReference.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/FeeReference.php)
  - 📄 [FinancialAudit.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/FinancialAudit.php)
  - 📄 [FinancialRecord.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/FinancialRecord.php)
  - 📄 [OperationalCost.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/OperationalCost.php)
  - 📄 [Personnel.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/Personnel.php)
  - 📄 [PersonnelUnavailability.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/PersonnelUnavailability.php)
  - 📄 [Rehearsal.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/Rehearsal.php)
  - 📄 [SanggarCostume.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/SanggarCostume.php)
  - 📄 [ServiceCatalog.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/ServiceCatalog.php)
  - 📄 [SiteContent.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/SiteContent.php)
  - 📄 [User.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Models/User.php)
- 📂 **Notifications**
  - 📄 [BookingStatusChanged.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Notifications/BookingStatusChanged.php)
- 📂 **Providers**
  - 📄 [AppServiceProvider.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/Providers/AppServiceProvider.php)
- 📂 **View**
  - 📂 **Components**
    - 📄 [AppLayout.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/View/Components/AppLayout.php)
    - 📄 [GuestLayout.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app/View/Components/GuestLayout.php)

### bootstrap/ - Bootstrap Aplikasi
- 📂 **cache**
  - 📄 [.gitignore](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/bootstrap/cache/.gitignore)
  - 📄 [packages.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/bootstrap/cache/packages.php)
  - 📄 [services.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/bootstrap/cache/services.php)
- 📄 [app.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/bootstrap/app.php)
- 📄 [providers.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/bootstrap/providers.php)

### config/ - Konfigurasi
- 📄 [app.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/config/app.php)
- 📄 [auth.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/config/auth.php)
- 📄 [cache.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/config/cache.php)
- 📄 [database.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/config/database.php)
- 📄 [filesystems.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/config/filesystems.php)
- 📄 [logging.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/config/logging.php)
- 📄 [mail.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/config/mail.php)
- 📄 [queue.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/config/queue.php)
- 📄 [services.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/config/services.php)
- 📄 [session.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/config/session.php)

### database/ - Database & SQL
- 📂 **factories**
  - 📄 [UserFactory.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/factories/UserFactory.php)
- 📂 **migrations**
  - 📄 [0001_01_01_000000_create_users_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/0001_01_01_000000_create_users_table.php)
  - 📄 [0001_01_01_000001_create_cache_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/0001_01_01_000001_create_cache_table.php)
  - 📄 [0001_01_01_000002_create_jobs_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/0001_01_01_000002_create_jobs_table.php)
  - 📄 [2026_03_29_000003_create_personnel_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000003_create_personnel_table.php)
  - 📄 [2026_03_29_000004_create_fee_references_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000004_create_fee_references_table.php)
  - 📄 [2026_03_29_000005_create_bookings_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000005_create_bookings_table.php)
  - 📄 [2026_03_29_000006_create_events_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000006_create_events_table.php)
  - 📄 [2026_03_29_000007_create_event_personnel_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000007_create_event_personnel_table.php)
  - 📄 [2026_03_29_000008_create_rehearsals_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000008_create_rehearsals_table.php)
  - 📄 [2026_03_29_000009_create_sanggar_costumes_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000009_create_sanggar_costumes_table.php)
  - 📄 [2026_03_29_000010_create_costume_usages_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000010_create_costume_usages_table.php)
  - 📄 [2026_03_29_000011_create_costume_vendors_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000011_create_costume_vendors_table.php)
  - 📄 [2026_03_29_000012_create_costume_rentals_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000012_create_costume_rentals_table.php)
  - 📄 [2026_03_29_000013_create_financial_records_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000013_create_financial_records_table.php)
  - 📄 [2026_03_29_000014_create_operational_costs_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000014_create_operational_costs_table.php)
  - 📄 [2026_03_29_000015_create_financial_audits_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000015_create_financial_audits_table.php)
  - 📄 [2026_03_29_000016_create_cancellations_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000016_create_cancellations_table.php)
  - 📄 [2026_03_29_000017_create_event_logs_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000017_create_event_logs_table.php)
  - 📄 [2026_03_29_000018_create_client_feedbacks_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000018_create_client_feedbacks_table.php)
  - 📄 [2026_03_29_000019_create_personnel_schedules_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000019_create_personnel_schedules_table.php)
  - 📄 [2026_03_29_000020_create_sql_objects.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_03_29_000020_create_sql_objects.php)
  - 📄 [2026_04_06_000021_add_coordinates_and_payment_proof_to_tables.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_04_06_000021_add_coordinates_and_payment_proof_to_tables.php)
  - 📄 [2026_04_09_000022_add_price_range_fullpaid_to_bookings.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_04_09_000022_add_price_range_fullpaid_to_bookings.php)
  - 📄 [2026_05_08_021844_create_site_contents_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_05_08_021844_create_site_contents_table.php)
  - 📄 [2026_05_08_023215_create_notifications_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_05_08_023215_create_notifications_table.php)
  - 📄 [2026_05_08_030508_add_full_payment_proof_to_bookings_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_05_08_030508_add_full_payment_proof_to_bookings_table.php)
  - 📄 [2026_05_12_032421_add_soft_deletes_to_core_tables.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_05_12_032421_add_soft_deletes_to_core_tables.php)
  - 📄 [2026_05_16_101628_add_profile_fields_to_personnel_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_05_16_101628_add_profile_fields_to_personnel_table.php)
  - 📄 [2026_05_16_104410_create_personnel_unavailabilities_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_05_16_104410_create_personnel_unavailabilities_table.php)
  - 📄 [2026_05_18_020000_add_image_fields_to_site_contents.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_05_18_020000_add_image_fields_to_site_contents.php)
  - 📄 [2026_05_18_090000_create_service_catalogs_table.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_05_18_090000_create_service_catalogs_table.php)
  - 📄 [2026_05_18_143000_add_catalog_fields_to_service_catalogs_and_bookings.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_05_18_143000_add_catalog_fields_to_service_catalogs_and_bookings.php)
  - 📄 [2026_05_22_000030_create_missing_stored_procedures.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/migrations/2026_05_22_000030_create_missing_stored_procedures.php)
- 📂 **seeders**
  - 📄 [CostumeVendorSeeder.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/seeders/CostumeVendorSeeder.php)
  - 📄 [DatabaseSeeder.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/seeders/DatabaseSeeder.php)
  - 📄 [FeeReferenceSeeder.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/seeders/FeeReferenceSeeder.php)
  - 📄 [SampleBookingSeeder.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/seeders/SampleBookingSeeder.php)
  - 📄 [SanggarCostumeSeeder.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/seeders/SanggarCostumeSeeder.php)
  - 📄 [UserSeeder.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/seeders/UserSeeder.php)
- 📂 **sql**
  - 📄 [dcl_user_privileges.sql](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/sql/dcl_user_privileges.sql)
  - 📄 [replication_setup.sql](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database/sql/replication_setup.sql)

### public/ - File Publik & Assets
- 📂 **css**
  - 📄 [admin.css](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/css/admin.css)
  - 📄 [app.css](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/css/app.css)
- 📂 **images**
  - 📄 [batik_border.png](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/images/batik_border.png)
  - 📄 [hero_tari_sunda.png](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/images/hero_tari_sunda.png)
- 📂 **storage**
  - 📂 **cms**
    - 📄 [a2tUrD7nJwG4K7lI1xU6QqpcJO03OMWaTOWOMeAi.jpg](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/storage/cms/a2tUrD7nJwG4K7lI1xU6QqpcJO03OMWaTOWOMeAi.jpg)
  - 📂 **personnel-photos**
    - 📄 [iWjCR7CB2asS4kkBSaCXAWxb47V0QOe8YtyzCL7t.png](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/storage/personnel-photos/iWjCR7CB2asS4kkBSaCXAWxb47V0QOe8YtyzCL7t.png)
  - 📂 **proofs**
    - 📄 [D2ia3KXaC9Ba5Y4oeL0X26cNkoFMmK3fSnr8Xfkp.jpg](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/storage/proofs/D2ia3KXaC9Ba5Y4oeL0X26cNkoFMmK3fSnr8Xfkp.jpg)
    - 📄 [JhhJ6qy6zjUQsmX0CRJrDl3r7kGYrKYHy6SRB9JE.jpg](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/storage/proofs/JhhJ6qy6zjUQsmX0CRJrDl3r7kGYrKYHy6SRB9JE.jpg)
    - 📄 [lfCvPx7yPm8QQ1QDfHBUuEoyJnPeIgqvOb4Ixs4Z.jpg](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/storage/proofs/lfCvPx7yPm8QQ1QDfHBUuEoyJnPeIgqvOb4Ixs4Z.jpg)
    - 📄 [vdKzf5ZSuStYSgWuZ4IWjZU47MtZ9pmHajpMntJe.jpg](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/storage/proofs/vdKzf5ZSuStYSgWuZ4IWjZU47MtZ9pmHajpMntJe.jpg)
  - 📄 [.gitignore](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/storage/.gitignore)
- 📄 [.htaccess](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/.htaccess)
- 📄 [favicon.ico](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/favicon.ico)
- 📄 [index.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/index.php)
- 📄 [robots.txt](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/public/robots.txt)

### resources/ - Frontend Resources & Views
- 📂 **css**
  - 📄 [app.css](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/css/app.css)
- 📂 **js**
  - 📄 [app.js](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/js/app.js)
  - 📄 [bootstrap.js](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/js/bootstrap.js)
- 📂 **views**
  - 📂 **admin**
    - 📂 **bookings**
      - 📄 [create.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/bookings/create.blade.php)
      - 📄 [dp-verification.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/bookings/dp-verification.blade.php)
      - 📄 [index.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/bookings/index.blade.php)
      - 📄 [show.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/bookings/show.blade.php)
    - 📂 **cancellations**
      - 📄 [index.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/cancellations/index.blade.php)
    - 📂 **catalogs**
      - 📄 [create.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/catalogs/create.blade.php)
      - 📄 [edit.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/catalogs/edit.blade.php)
      - 📄 [index.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/catalogs/index.blade.php)
    - 📂 **cms**
      - 📄 [index.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/cms/index.blade.php)
    - 📂 **costumes**
      - 📄 [create-asset.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/costumes/create-asset.blade.php)
      - 📄 [create-rental.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/costumes/create-rental.blade.php)
      - 📄 [index.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/costumes/index.blade.php)
    - 📂 **events**
      - 📄 [index.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/events/index.blade.php)
      - 📄 [monitoring-detail.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/events/monitoring-detail.blade.php)
      - 📄 [monitoring.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/events/monitoring.blade.php)
      - 📄 [plotting.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/events/plotting.blade.php)
      - 📄 [show.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/events/show.blade.php)
    - 📂 **financials**
      - 📄 [index.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/financials/index.blade.php)
      - 📄 [pdf.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/financials/pdf.blade.php)
      - 📄 [post-event-list.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/financials/post-event-list.blade.php)
      - 📄 [post-event.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/financials/post-event.blade.php)
    - 📂 **payments**
      - 📄 [index.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/payments/index.blade.php)
    - 📂 **personnel**
      - 📄 [create.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/personnel/create.blade.php)
      - 📄 [edit.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/personnel/edit.blade.php)
      - 📄 [index.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/personnel/index.blade.php)
    - 📂 **rehearsals**
      - 📄 [index.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/rehearsals/index.blade.php)
    - 📄 [dashboard.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/admin/dashboard.blade.php)
  - 📂 **auth**
    - 📄 [confirm-password.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/auth/confirm-password.blade.php)
    - 📄 [forgot-password.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/auth/forgot-password.blade.php)
    - 📄 [login.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/auth/login.blade.php)
    - 📄 [register.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/auth/register.blade.php)
    - 📄 [reset-password.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/auth/reset-password.blade.php)
    - 📄 [verify-email.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/auth/verify-email.blade.php)
  - 📂 **components**
    - 📄 [application-logo.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/application-logo.blade.php)
    - 📄 [auth-session-status.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/auth-session-status.blade.php)
    - 📄 [danger-button.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/danger-button.blade.php)
    - 📄 [dropdown-link.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/dropdown-link.blade.php)
    - 📄 [dropdown.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/dropdown.blade.php)
    - 📄 [gold-modal.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/gold-modal.blade.php)
    - 📄 [input-error.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/input-error.blade.php)
    - 📄 [input-label.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/input-label.blade.php)
    - 📄 [modal.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/modal.blade.php)
    - 📄 [nav-link.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/nav-link.blade.php)
    - 📄 [primary-button.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/primary-button.blade.php)
    - 📄 [responsive-nav-link.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/responsive-nav-link.blade.php)
    - 📄 [secondary-button.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/secondary-button.blade.php)
    - 📄 [text-input.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/components/text-input.blade.php)
  - 📂 **klien**
    - 📂 **bookings**
      - 📄 [create.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/klien/bookings/create.blade.php)
      - 📄 [show.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/klien/bookings/show.blade.php)
    - 📄 [dashboard.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/klien/dashboard.blade.php)
  - 📂 **layouts**
    - 📄 [admin.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/layouts/admin.blade.php)
    - 📄 [app.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/layouts/app.blade.php)
    - 📄 [guest.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/layouts/guest.blade.php)
    - 📄 [klien.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/layouts/klien.blade.php)
    - 📄 [navigation.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/layouts/navigation.blade.php)
    - 📄 [personnel.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/layouts/personnel.blade.php)
  - 📂 **personnel**
    - 📄 [dashboard.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/personnel/dashboard.blade.php)
    - 📄 [keuangan.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/personnel/keuangan.blade.php)
    - 📄 [pending.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/personnel/pending.blade.php)
    - 📄 [profile_edit.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/personnel/profile_edit.blade.php)
  - 📂 **profile**
    - 📂 **partials**
      - 📄 [delete-user-form.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/profile/partials/delete-user-form.blade.php)
      - 📄 [update-password-form.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/profile/partials/update-password-form.blade.php)
      - 📄 [update-profile-information-form.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/profile/partials/update-profile-information-form.blade.php)
    - 📄 [edit.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/profile/edit.blade.php)
  - 📄 [dashboard.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/dashboard.blade.php)
  - 📄 [welcome.blade.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views/welcome.blade.php)

### routes/ - Routing Aplikasi
- 📄 [auth.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/routes/auth.php)
- 📄 [console.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/routes/console.php)
- 📄 [web.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/routes/web.php)

### storage/ - Storage Aplikasi
- 📂 **app**
  - 📂 **private**
    - 📄 [.gitignore](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/storage/app/private/.gitignore)
  - 📂 **public**
    - 📂 **cms**
      - 📄 [a2tUrD7nJwG4K7lI1xU6QqpcJO03OMWaTOWOMeAi.jpg](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/storage/app/public/cms/a2tUrD7nJwG4K7lI1xU6QqpcJO03OMWaTOWOMeAi.jpg)
    - 📂 **personnel-photos**
      - 📄 [iWjCR7CB2asS4kkBSaCXAWxb47V0QOe8YtyzCL7t.png](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/storage/app/public/personnel-photos/iWjCR7CB2asS4kkBSaCXAWxb47V0QOe8YtyzCL7t.png)
    - 📂 **proofs**
      - 📄 [D2ia3KXaC9Ba5Y4oeL0X26cNkoFMmK3fSnr8Xfkp.jpg](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/storage/app/public/proofs/D2ia3KXaC9Ba5Y4oeL0X26cNkoFMmK3fSnr8Xfkp.jpg)
      - 📄 [JhhJ6qy6zjUQsmX0CRJrDl3r7kGYrKYHy6SRB9JE.jpg](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/storage/app/public/proofs/JhhJ6qy6zjUQsmX0CRJrDl3r7kGYrKYHy6SRB9JE.jpg)
      - 📄 [lfCvPx7yPm8QQ1QDfHBUuEoyJnPeIgqvOb4Ixs4Z.jpg](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/storage/app/public/proofs/lfCvPx7yPm8QQ1QDfHBUuEoyJnPeIgqvOb4Ixs4Z.jpg)
      - 📄 [vdKzf5ZSuStYSgWuZ4IWjZU47MtZ9pmHajpMntJe.jpg](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/storage/app/public/proofs/vdKzf5ZSuStYSgWuZ4IWjZU47MtZ9pmHajpMntJe.jpg)
    - 📄 [.gitignore](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/storage/app/public/.gitignore)
  - 📄 [.gitignore](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/storage/app/.gitignore)
- 📂 **framework**
  - 📄 [.gitignore](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/storage/framework/.gitignore)
- 📂 **logs**
  - 📄 [.gitignore](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/storage/logs/.gitignore)
  - 📄 [laravel.log](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/storage/logs/laravel.log)

### tests/ - Automated Tests
- 📂 **Feature**
  - 📂 **Auth**
    - 📄 [AuthenticationTest.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/tests/Feature/Auth/AuthenticationTest.php)
    - 📄 [EmailVerificationTest.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/tests/Feature/Auth/EmailVerificationTest.php)
    - 📄 [PasswordConfirmationTest.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/tests/Feature/Auth/PasswordConfirmationTest.php)
    - 📄 [PasswordResetTest.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/tests/Feature/Auth/PasswordResetTest.php)
    - 📄 [PasswordUpdateTest.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/tests/Feature/Auth/PasswordUpdateTest.php)
    - 📄 [RegistrationTest.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/tests/Feature/Auth/RegistrationTest.php)
  - 📄 [ExampleTest.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/tests/Feature/ExampleTest.php)
  - 📄 [ProfileTest.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/tests/Feature/ProfileTest.php)
- 📂 **Unit**
  - 📄 [ExampleTest.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/tests/Unit/ExampleTest.php)
- 📄 [Pest.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/tests/Pest.php)
- 📄 [TestCase.php](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/tests/TestCase.php)

## Ringkasan File per Kategori

| Kategori | Jumlah File | Deskripsi |
| :--- | :---: | :--- |
| **Models** | 18 | Model Eloquent representasi tabel database (termasuk ServiceCatalog, SiteContent, dll.) |
| **Controllers** | 27 | Controller untuk menangani request HTTP (Admin, Auth, Klien, Personnel) |
| **Migrations** | 33 | File migrasi database untuk membangun skema tabel sistem |
| **Blade Templates (Views)** | 69 | Tampilan visual antarmuka pengguna berbasis blade templates |
| **View Component Classes** | 2 | Class PHP pembantu untuk tata letak halaman layout |
| **Routes** | 3 | File routing web, konsol, dan otentikasi (web.php, console.php, auth.php) |
| **Seeders** | 6 | Pengisian data awal/seeding database untuk pengujian |
| **Factories** | 1 | Factory model user untuk automated testing |
| **SQL Scripts** | 2 | Script privilege user DCL dan replikasi basis data |
| **Console Commands** | 2 | Custom command Artisan laravel (AutoCompleteEvents & StressTest) |
| **Notifications** | 1 | Notification class untuk pengiriman pemberitahuan status booking |
| **Middleware** | 2 | Pintu pengaman akses berdasarkan status personel dan peran (role) |
| **Requests** | 2 | Form request validasi input data profil dan otentikasi |
| **Automated Tests** | 11 | Unit test & Feature test untuk keandalan fitur sistem |
| **Total Core Files** | **192** | *Jumlah kumulatif file pemrograman utama (tidak termasuk file konfigurasi dasar & storage)* |

## Deskripsi Struktur Utama

### 📁 **[app/](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/app)**
Inti logika bisnis aplikasi, meliputi:
- **Models**: Merepresentasikan relasi database, kini terintegrasi dengan `ServiceCatalog` (katalog jasa), `SiteContent` (CMS), dan `PersonnelUnavailability`.
- **Controllers**: Mengatur alur data. Penambahan fitur mencakup `CmsController` untuk CMS landing page, `ServiceCatalogController` untuk katalog jasa, dan penguatan portal personel dengan `PersonnelProfileController` & `PersonnelUnavailabilityController`.
- **Notifications**: Digunakan untuk mengirimkan pesan dinamis seperti `BookingStatusChanged`.

### 📁 **[database/](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/database)**
Skema database dan query:
- **migrations**: Terdiri dari 33 berkas migrasi database. Pembaruan terbaru meliputi implementasi **Soft Deletes** di tabel utama untuk perlindungan data dari penghapusan tidak sengaja, penambahan bidang profil personel, serta pembuatan stored procedure basis data melalui `create_missing_stored_procedures.php`.
- **sql**: Query replikasi master-slave dan pengaturan DCL privileges.

### 📁 **[resources/views/](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/resources/views)**
Berisi total **69 berkas tampilan (Blade)**:
- **admin**: Penambahan modul **CMS** (`admin/cms/index.blade.php`), pengelolaan **Katalog Jasa** (`admin/catalogs/`), dan template pencetakan **Laporan Finansial PDF** (`admin/financials/pdf.blade.php`).
- **personnel**: Halaman mandiri manajemen profil personel dan pelaporan rekapitulasi keuangan insentif penari/kru.
- **klien**: Halaman transaksi pemesanan (booking) dan pengunggahan bukti pembayaran.

### 📁 **[routes/](file:///d:/ART-HUB_Sanggar%20Seni/laravel-app-2/routes)**
Mendefinisikan rute URL sistem:
- `web.php`: Rute utama aplikasi yang mengelompokkan otorisasi multi-role (Admin, Personnel, Klien) secara ketat menggunakan middleware `RoleMiddleware`.

---
*Dokumen ini diperbarui secara otomatis berdasarkan analisis repositori terbaru pada tanggal 22 Mei 2026.*
