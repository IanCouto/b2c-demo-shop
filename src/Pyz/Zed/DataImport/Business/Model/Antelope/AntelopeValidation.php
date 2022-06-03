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
        
        //checking name
        if(!$this->validadeAtributte($name, $antelopeEntity, "NAME"))
            return false;

        //checking color
        if(!$this->validadeAtributte($color, $antelopeEntity, "COLOR"))
            return false;
        
        return true;
    }

    private function validadeAtributte($atributte, $antelopeEntity, $type){
        //checking for atributes that are empty, with only white spaces or with spacial chars
        if (empty(trim($atributte)) || preg_match("/[^a-zA-ZÀ-ÿ ]/",$atributte)){ 
            echo "\033[01;31mERROR!!!\033[0m";//send e red error alert
            echo "\nThe following object has a invalid \033[01;31m".$type."\033[0m:\n".$antelopeEntity."\n";
            return false;
            
        }
        return true;
    }

    public static function create()
    {
       return new AntelopeValidation;
    }
}