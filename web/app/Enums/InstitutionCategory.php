<?php

namespace App\Enums;

enum InstitutionCategory: string
{
    case YAYASAN = 'YAYASAN';
    case PONDOK = 'PONDOK';
    case FORMAL = 'FORMAL';
    case NON_FORMAL = 'NON_FORMAL';
    case SOSIAL = 'SOSIAL';
}
