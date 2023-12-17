<?php

namespace App\Enums;

enum CompanyOrderStatusEnum: int {
    case New = 0;
    case PendingApproval = 1;
    case Confirm = 2;
    case IsPrepared = 3;
    case IsReady = 4;
    case Delivered = 5;
    case Reject = 6;


    public function toHumanize() : array
    {
        return match($this) {
            self::New => ['name' => 'Yeni sifariş', 'value' => self::New->value],
            self::PendingApproval => ['name' => 'Təsdiq gözləyir', 'value' => self::PendingApproval->value],
            self::Confirm => ['name' => 'Təsdiq edilib', 'value' => self::Confirm->value],
            self::IsPrepared => ['name' => 'Hazırlanır', 'value' => self::IsPrepared->value],
            self::IsReady => ['name' => 'Hazırdır', 'value' => self::IsReady->value],
            self::Delivered => ['name' => 'Kuriyere verilib', 'value' => self::Delivered->value],
            self::Reject => ['name' => 'İmtina edilib', 'value' => self::Reject->value],
        };
    }
}
