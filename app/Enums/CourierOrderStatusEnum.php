<?php

namespace App\Enums;

enum CourierOrderStatusEnum: int {
    case OrderReceived = 0;
    case OrderAccepted = 1;
}
