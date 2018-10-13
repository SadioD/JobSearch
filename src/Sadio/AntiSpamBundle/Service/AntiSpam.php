<?php

namespace Sadio\AntiSpamBundle\Service;

// Ce Service est configurable depuis app/service.yml
class AntiSpam 
{
    // cette méthode permet de vérifier si l'argument fourni est un SPAM --------------------------------------------------------------
    public function isSpam($messages) {
        for($i=0; $i < count($messages); $i++) {
            if (strlen($messages[$i]) < 50) return true;
        }
        return false;
    }// --------------------------------------------------------------------------------------------------------------------------------
}