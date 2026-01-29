<?php

namespace App\Interfaces;

use App\Models\User;
use App\Models\Vehicle;
use App\Types\DOE;
use Carbon\Carbon;

interface IBookingInterface
{

    public function can_book_vehicle(Vehicle $vehicle, ?User $auth_user, Carbon $from, Carbon $to, ?array $options): DOE;

    public function calculate_amount(Vehicle $vehicle, Carbon $from, Carbon $to, ?array $options): float;

    public function book_vehicle(Vehicle $vehicle, ?User $auth_user, Carbon $from, Carbon $to, ?array $options): DOE;
}
