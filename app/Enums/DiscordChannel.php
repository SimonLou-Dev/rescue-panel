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
    const errors =   "errors";
    const bugs =   "bugs";
    const service = "service";
    const annonce = "annonce";
    const BC = "BC";
    const RI = "RI";
    const facture = "facture";
    const vols = "vols";
    const infos = "infos";
    const remboursement = "remboursement";
    const staff = "staff";
    const logistique = "logistique";
    const sanctions = "sanctions";
    const poudre = "poudre";
}
