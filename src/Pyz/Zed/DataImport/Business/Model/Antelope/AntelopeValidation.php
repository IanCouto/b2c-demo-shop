<?php

namespace Pyz\Zed\DataImport\Business\Model\Antelope;

use Pyz\Zed\Antelope\Dependency\AntelopeEvents;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Orm\Zed\Antelope\Persistence\PyzAntelope;

class AntelopeValidation extends AntelopeWriterStep
{
    public function validate(PyzAntelope $antelopeEntity)
    {   
        $file = fopen("debug/debug.txt","a");
        $iscorrect = true;
        $timezone  = -3; 

        $str = gmdate("[d-m-y H:i:sa]", time() + 3600*($timezone+date("I"))).
            " - Antelope - id=".$antelopeEntity->getIdAntelope();

        $iscorrect = $this->checkNullAtribute($antelopeEntity->getColor(), $file,"color"); //color
        $str = $str." - color=".$antelopeEntity->getColor();
        $iscorrect = $this->checkNullAtribute($antelopeEntity->getName(), $file,"name"); //name
        $str = $str." - name=".$antelopeEntity->getName();

        fwrite($file, $str."\n");
        fclose($file);
        return $iscorrect;
    }

    private function checkNullAtribute($str, $file, $atributte){
        if (empty($str)){
            fwrite($file, "next object has a invalid ".$atributte."\n");
            return false;
        }
        return true;
    }

    private function checkSpecialChar($str, $file, $atributte){
        if (str_contains("(?:(?![a-zA-Z]).)*",$str)){
            fwrite($file, "next object has a invalid ".$atributte."\n");
            return false;
        }
        return true;
    }

    public static function create()
    {
       return new AntelopeValidation;
    }

}