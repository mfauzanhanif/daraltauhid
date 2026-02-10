<?php

namespace App\Enums;

enum InstitutionType: string
{
    case YAYASAN = 'YAYASAN';
    case PONDOK = 'PONDOK';
    case TK = 'TK';
    case SD = 'SD';
    case MI = 'MI';
    case SMP = 'SMP';
    case MTS = 'MTS';
    case SMA = 'SMA';
    case MA = 'MA';
    case SMK = 'SMK';
    case SLB = 'SLB';
    case MDTA = 'MDTA';
    case TPQ = 'TPQ';
    case MADRASAH = 'Madrasah';
    case LKSA = 'LKSA';
}
