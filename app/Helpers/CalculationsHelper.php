<?php

function calculateRentalAnalysisMonth(?string $tax, ?string $other_tax, ?string $house_rental_value): ?string
{
    $value_house_rental = intval(str_replace(['.', ','], '', $house_rental_value));
    $value_tax = intval(str_replace(['.', ','], '', $tax));
    $value_other_tax = intval(str_replace(['.', ','], '', $other_tax));

    $value_total_rental = $value_house_rental + $value_other_tax;

    $totalTax = (($value_total_rental) * ($value_tax)) / 10000;
    $totalValue = ($value_total_rental + $totalTax) - $value_total_rental;


    return brazilianMoneyFormat($totalValue / 100);
}

function calculateRentalAnalysisYear(?string $tax, ?string $other_tax, ?string $house_rental_value): ?string
{
    $value_house_rental = intval(str_replace(['.', ','], '', $house_rental_value));
    $value_tax = intval(str_replace(['.', ','], '', $tax));
    $value_other_tax = intval(str_replace(['.', ','], '', $other_tax));

    $value_total_rental = $value_house_rental + $value_other_tax;

    $totalTax = (($value_total_rental) * ($value_tax)) / 10000;
    $totalValue = (($value_total_rental + $totalTax) - $value_total_rental) * 12;


    return brazilianMoneyFormat($totalValue / 100);
}

