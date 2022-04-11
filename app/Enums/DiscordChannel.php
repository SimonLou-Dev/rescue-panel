<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class DiscordChannel extends Enum
{
    const Errors =   "errors";
    const Bugs =   "bugs";
    const Service = "service";
    const MedicAnnonce = "MedicAnnonce";
    const FireAnnonce = "FireAnnonce";
    const BC = "BC";
    const RI = "RI";
    const Facture = "Facture";
    const Vols = "vols";
    const MedicInfos = "MedicInfos";
    const FireInfos = "FireInfos";
    const MedicRemboursement = "MedicRemboursement";
    const FireRemboursement = "FireRemboursement";
    const Staff = "staff";
    const MedicLogistique = "MedicLogistique";
    const FireLogistique = "FireLogistique";
    const MedicSanctions = "MedicSanctions";
    const FireSanctions = "FireSanctions";
    const Poudre = "poudre";
    const Absences = 'Absences';
    const FireReport = 'FireReport';
}
