<?php
namespace App\Utils;

class CategoryDifferenceComputer
{

    /**
     *
     * @param array<Category> $total
     * @param array<Category> $difference
     * @return array<Category>
     * @todo test more thorougly
     */
    public static function computeDifference(array $total, array $remove): array
    {
        $newTotal = array();

        for ($i = 0; $i < count($total); $i ++) {
            for ($j = 0; $j < count($remove); $j ++) {
                if ($total[$i]->getCode() == $remove[$j]->getCode()) {
                    continue 2;
                }
            }
            array_push($newTotal, $total[$i]);
        }
        return $newTotal;
    }
}

