<?php
namespace App\Traits;

trait CodeGenerator
{
    /**
     * Generate a unique code for a table/model.
     *
     * @param string $prefix  Prefix for the code, e.g., 'EV', 'USR', 'VEN'
     * @param string $column  Column name that stores the code, e.g., 'EventCode'
     * @param string $model   Fully qualified model class, e.g., \App\Models\Event::class
     * @return string
     */
    public function generateCode(string $prefix, string $orderBy, string $column, string $model)
    {
        $last = $model::orderBy($orderBy, 'desc')->first();

        if (!$last) {
            return $prefix . '0001';
        }

        $lastCode = $last->{$column};
        $number = (int) substr($lastCode, strlen($prefix));
        $number++;

        return $prefix . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}