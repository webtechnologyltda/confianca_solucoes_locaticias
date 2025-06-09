<?php

function cpfFormat(?string $cpf): ?string
{
    if (strlen($cpf) != 11) {
        return $cpf;
    }

    return substr($cpf, 0, 3).'.'.substr($cpf, 3, 3).'.'.substr($cpf, 6, 3).'-'.substr($cpf, 9, 2);
}

function cnpjFormat(?string $cnpj): ?string
{
    if (strlen($cnpj) != 14) {
        return $cnpj;
    }

    return substr($cnpj, 0, 2).'.'.substr($cnpj, 2, 3).'.'.substr($cnpj, 5, 3).'/'.substr($cnpj, 8, 4).'-'.substr($cnpj, 12, 2);
}

function phoneFormatAndCellPhone(?string $phone): ?string
{
    if (strlen($phone) == 10) {
        return '('.substr($phone, 0, 2).') '.substr($phone, 2, 4).'-'.substr($phone, 6, 4);
    }
    if (strlen($phone) != 11) {
        return $phone;
    }

    return '('.substr($phone, 0, 2).') '.substr($phone, 2, 5).'-'.substr($phone, 7, 4);
}

function brazilianMoneyFormat(?string $value): ?string
{
    return 'R$ '.number_format($value, 2, ',', '.');

}
