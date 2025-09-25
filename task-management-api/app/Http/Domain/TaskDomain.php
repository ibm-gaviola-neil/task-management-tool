<?php

namespace App\Http\Domain;

class TaskDomain {
    public function ordinal(int $number): string
    {
        $suffixes = ['th','st','nd','rd','th','th','th','th','th','th'];
        if (($number % 100) >= 11 && ($number % 100) <= 13) {
            return $number . 'th';
        }
        return $number . $suffixes[$number % 10];
    }
}