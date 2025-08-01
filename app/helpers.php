<?php

if (!function_exists('format_vnd')) {
    /**
     * Format number to Vietnamese currency format
     * 
     * @param float|int $amount
     * @return string
     */
    function format_vnd($amount) {
        return number_format($amount, 0, ',', '.');
    }
}
