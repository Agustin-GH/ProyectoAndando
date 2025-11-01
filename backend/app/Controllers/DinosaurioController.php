<?php
require_once APP_PATH . '/Repositories/DinoModels.php';

class DinosaurioController {
    private $tipos = [
        "Azul"    => 'imgs/minis/Azul.png',
        "Cyan"    => 'imgs/minis/Cyan.png',
        "Naranja" => 'imgs/minis/Naranja.png',
        "Rojo"    => 'imgs/minis/Rojo.png',
        "Rosado"  => 'imgs/minis/Rosa.png',
        "Verde"   => 'imgs/minis/Verde.png',
    ];
    public function asignacion() {
        $bandeja = [];
        $id = 1;

        foreach ($this->tipos as $tipo => $rutaRelativa) {
            $imagen = asset($rutaRelativa); 
            for ($i = 0; $i < 8; $i++) {
                $bandeja[] = new Dinosaurio($id, $tipo, $imagen);
                $id++;
            }
        }

        return $bandeja;
    }
}