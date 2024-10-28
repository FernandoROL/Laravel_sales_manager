<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Components extends Model
{
    use HasFactory;

    public function formatacaoMascaraDinheiroDecimal($valueToFormat)
    {
        $size = strlen($valueToFormat);
        $data = str_replace(',', '.', $valueToFormat);
        if ($size <= 6) {
            $data = str_replace(',', '.', $valueToFormat);
        } else {
            if ($size >= 8 && $size <= 10) {
                $removeComma = str_replace(',', '.',   $valueToFormat);
                $sparateByIndex  = explode('.',  $removeComma);
                $data  =  $sparateByIndex[0] . $sparateByIndex[1];
            }
        }

        return $data;
    }
}