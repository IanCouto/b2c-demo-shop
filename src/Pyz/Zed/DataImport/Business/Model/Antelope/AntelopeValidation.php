<?php

namespace Pyz\Zed\DataImport\Business\Model\Antelope;

use Pyz\Zed\Antelope\Dependency\AntelopeEvents;
use Spryker\Zed\DataImport\Business\Model\DataImportStep\PublishAwareStep;
use Orm\Zed\Antelope\Persistence\PyzAntelope;

class AntelopeValidation extends AntelopeWriterStep
{
    public function validate(PyzAntelope $antelopeEntity)
    {   
        $color = $antelopeEntity->getColor();
        $name = $antelopeEntity->getName();
        $id = $antelopeEntity->getIdAntelope();
        $isNameCorrect = false;
        $isCollorCorrect = false;
        
        //checking color
        if($this->validadeAtributte($color, $antelopeEntity, "COLOR"))
            $isCollorCorrect = true;
        $str = $str." - color=".$antelopeEntity->getColor();

        //checking name
        if($this->validadeAtributte($name, $antelopeEntity, "NAME"))
            $isNameCorrect = true;
        $str = $str." - name=".$antelopeEntity->getName();

        return true;
    }

    private function validadeAtributte($atributte, $antelopeEntity, $type){
        if (empty(trim($atributte)) || preg_match("/[^A-zÀ-ſ ]/",$atributte)){
            echo "\nThe following object has a invalid ".$type.":\n".$antelopeEntity."\n";
            return false;
        }
        return true;
    }

    public static function create()
    {
       return new AntelopeValidation;
    }
}