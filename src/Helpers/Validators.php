<?php


namespace App\Helpers;


class Validators
{

    public static function isEmpty(?string $field): bool
    {
        return empty($field) ?? false;
    }

    public static function is_valid_position (string $position): bool
    {
        $valid_positions = ['goalkeeper', 'defender', 'midfielder', 'forward'];

        return !in_array(strtolower($position), $valid_positions);
    }

}