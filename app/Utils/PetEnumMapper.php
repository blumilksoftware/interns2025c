<?php

declare(strict_types=1);

namespace App\Utils;

use App\Enums\PetHealthStatus;
use App\Enums\PetSex;
use App\Enums\PetSpecies;

enum PetEnumMapper
{
    public static function mapSpecies(?string $species): PetSpecies
    {
        if (!$species) {
            return PetSpecies::Other;
        }

        $species = mb_strtolower($species);

        return match (true) {
            str_contains($species, "pies"),
            str_contains($species, "dog") => PetSpecies::Dog,

            str_contains($species, "kot"),
            str_contains($species, "cat") => PetSpecies::Cat,

            str_contains($species, "ptak"),
            str_contains($species, "bird") => PetSpecies::Bird,

            str_contains($species, "królik"),
            str_contains($species, "rabbit") => PetSpecies::Rabbit,

            str_contains($species, "gad"),
            str_contains($species, "reptile") => PetSpecies::Reptile,

            default => PetSpecies::Other,
        };
    }

    public static function mapSex(?string $sex): PetSex
    {
        if (!$sex) {
            return PetSex::Unknown;
        }

        $sex = mb_strtolower($sex);

        return match (true) {
            str_contains($sex, "samiec"),
            str_contains($sex, "pies"),
            str_contains($sex, "male") => PetSex::Male,

            str_contains($sex, "suczka"),
            str_contains($sex, "kotka"),
            str_contains($sex, "female") => PetSex::Female,

            default => PetSex::Unknown,
        };
    }

    public static function mapHealthStatus(?string $status): PetHealthStatus
    {
        if (!$status) {
            return PetHealthStatus::Unknown;
        }

        $status = mb_strtolower($status);

        return match (true) {
            str_contains($status, "zdrow"),
            str_contains($status, "healthy") => PetHealthStatus::Healthy,

            str_contains($status, "chory"),
            str_contains($status, "sick") => PetHealthStatus::Sick,

            str_contains($status, "wraca"),
            str_contains($status, "recover") => PetHealthStatus::Recovering,

            str_contains($status, "ciężki"),
            str_contains($status, "critical") => PetHealthStatus::Critical,

            default => PetHealthStatus::Unknown,
        };
    }
}
