<?php

namespace App\Misc;

enum UserRole { case admin; case customer; }
define('UserRoleArray', array_column(UserRole::cases(), 'name'));

enum VehicleAvailability { case available; case unavailable; case maintenance; }
define('VehicleAvailabilityArray', array_column(VehicleAvailability::cases(), 'name'));

enum BookingStatus { case pending; case confirmed; case canceled; case completed; }
define('BookingStatusArray', array_column(BookingStatus::cases(), 'name'));
enum BookingPaymentStatus { case unpaid; case paid; case refunded; }
define('BookingPaymentStatusArray', array_column(BookingPaymentStatus::cases(), 'name'));
enum BookingPaymentMethod { case cash; case credit_card; case other; }
define('BookingPaymentMethodArray', array_column(BookingPaymentMethod::cases(), 'name'));

enum MediaType { case image; case video; case other; }
define('MediaTypeArray', array_column(MediaType::cases(), 'name'));