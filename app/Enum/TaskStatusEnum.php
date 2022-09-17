<?php

namespace App\Enum;

enum TaskStatusEnum:string {
    case New = 'new';
    case Incomplete = 'incomplete';
    case Complete = 'complete';
}
