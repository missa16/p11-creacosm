<?php

namespace App\Service;

class ColorGenerator
{
    public function generatePastelColors() {
        $colors = array();
        for ($i = 0; $i < 51; $i++) {
            $red = mt_rand(150, 255);
            $green = mt_rand(150, 255);
            $blue = mt_rand(150, 255);
            $colors[] = "rgb($red, $green, $blue)";
        }
        return $colors;
    }

}