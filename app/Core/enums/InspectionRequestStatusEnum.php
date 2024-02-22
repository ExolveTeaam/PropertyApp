<?php
namespace App\Core\Enums;
 enum InspectionRequestStatusEnum: int{
    case PENDING = 1;
    case APPROVED = 2;
    case REJECTED = 3;
    case COMPLETED = 4;
 }
