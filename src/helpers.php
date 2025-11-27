<?php
// src/helpers.php

function e(string $value): string
{
    return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
}

function clean_string(?string $value): string
{
    return trim((string)filter_var($value ?? '', FILTER_SANITIZE_SPECIAL_CHARS));
}

function clean_int(?string $value): int
{
    return (int)filter_var($value ?? 0, FILTER_SANITIZE_NUMBER_INT);
}

function clean_float(?string $value): float
{
    return (float)filter_var($value ?? 0, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
}
