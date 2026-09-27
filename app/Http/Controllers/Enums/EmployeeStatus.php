<?php

namespace App\Http\Controllers\Enums;

// app/Enums/EmployeeStatus.php
enum EmployeeStatus: string {
    case Active     = 'active';
    case Inactive   = 'inactive';
    case OnLeave    = 'on_leave';
    case Suspended  = 'suspended';
    case Resigned   = 'resigned';
    case Terminated = 'terminated';
}