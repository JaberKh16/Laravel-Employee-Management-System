<?php

namespace App\Http\Controllers\Enums;

// app/Enums/BranchStatus.php
enum BranchStatus: string {
    case Active           = 'active';
    case Inactive         = 'inactive';
    case Closed           = 'closed';
    case UnderMaintenance = 'under_maintenance';
}