<?php

namespace App\Core\Enums;

enum PropertyTypeEnum: int{
    case ONE_BEDROOM_APARTMENT = 1;
    case MINI_FLAT = 2;
    case TWO_BEDROOM_FLAT = 3;
    case THREE_BEDROOM_FLAT = 4;
    case FOUR_BEDROOM_FLAT = 5;
    case DUPLEX = 6;
    case TERRACE = 7;
    case others = 8;
}
