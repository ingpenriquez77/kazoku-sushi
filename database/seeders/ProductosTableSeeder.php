<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProductosTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $productos = [
            // Entradas (id=1)
            ['categoria_id' => 1, 'nombre' => 'Chile Relleno', 'descripcion' => 'Mezcla de tampico con una proteina a elegir (res, pollo, camaron o tocino) envuelto con chile caribe.', 'precio' => 60.00],
            ['categoria_id' => 1, 'nombre' => 'Goyo Roll', 'descripcion' => 'Rollo de hoja de arroz empanizado y horneado. Por dentro: Philadelphia, aguacate, tampico, res y pollo. Afuera: bañado en anguila.', 'precio' => 70.00],

            // Naturales (id=2)
            ['categoria_id' => 2, 'nombre' => 'Avocado', 'descripcion' => 'Adentro: Philadelphia, aguacate y camaron. Afuera: Forrado de aguacate, bañado en anguila.', 'precio' => 140.00],
            ['categoria_id' => 2, 'nombre' => 'Camaronicto', 'descripcion' => 'Adentro: Philadelphia, aguacate y camaron. Afuera: Topping de camaron, cubos de aguacate bañado en anguila.', 'precio' => 165.00],

            // Empanizados (id=3)
            ['categoria_id' => 3, 'nombre' => 'Mar y Tierra', 'descripcion' => 'Mar y Tierra', 'precio' => 80.00],
            ['categoria_id' => 3, 'nombre' => 'Cielo, Mar y Tierra', 'descripcion' => 'Cielo, Mar y Tierra', 'precio' => 90.00],
            ['categoria_id' => 3, 'nombre' => 'Cordon Blue', 'descripcion' => 'Adentro: Philadelphia, aguacate, pollo y tocino. Afuera: Gratinado.', 'precio' => 100.00],
            ['categoria_id' => 3, 'nombre' => 'Camaron Blue', 'descripcion' => 'Adentro: Philadelphia, aguacate, camaron y tocino. Afuera: Gratinado.', 'precio' => 100.00],
            ['categoria_id' => 3, 'nombre' => '3 Quesos', 'descripcion' => 'Adentro: Philadelphia, aguacate, surimi y camaron. Afuera: Tampico, Gratinado y Philadelphia.', 'precio' => 90.00],
            ['categoria_id' => 3, 'nombre' => 'Guamuchilito', 'descripcion' => 'Adentro: Philadelphia, aguacate, surimi y camaron. Afuera: Tampico, Gratinado y Camaron bañado en anguila.', 'precio' => 110.00],
            ['categoria_id' => 3, 'nombre' => 'El de Papa', 'descripcion' => 'Adentro: Philadelphia, aguacate, res, camaron y tocino. Afuera: Coronado de tampico, aguacate y camaron bañado en anguila.', 'precio' => 150.00],
            ['categoria_id' => 3, 'nombre' => 'Ranchero', 'descripcion' => 'Adentro: Philadelphia, aguacate, res y pollo. Afuera: gratinado con serrano y trozos de tocino.', 'precio' => 110.00],
            ['categoria_id' => 3, 'nombre' => 'Bomba', 'descripcion' => 'Revoltura de tampico con proteina a elegir (res, pollo, camaron o tocino), forrado en arroz.', 'precio' => 90.00],
            ['categoria_id' => 3, 'nombre' => 'Bomba Especial', 'descripcion' => 'Revoltura de tampico con 2 proteinas a elegir (res, pollo, camaron o tocino), forrado en arroz y gratinado con trozos de serrano, tocino y aguacate.', 'precio' => 120.00],
            ['categoria_id' => 3, 'nombre' => 'Vane Roll', 'descripcion' => 'Adentro: Philadelphia, aguacate, res y pollo. Afuera: gratinado con trozos de tocino coronado con tampico.', 'precio' => 90.00],
            ['categoria_id' => 3, 'nombre' => '31 Roll', 'descripcion' => 'Adentro: Philadelphia, aguacate, tocino, res y camaron. Afuera: bañado de aderezo spicy de la casa con trozos de pollo.', 'precio' => 170.00],

            // Especialidades (id=4)
            ['categoria_id' => 4, 'nombre' => 'El ahogado', 'descripcion' => 'Adentro: Philadelphia, aguacate, surimi empanizado y pollo. Afuera: Bañado de gratinado spicy con trozos de tocino por encima.', 'precio' => 150.00],
            ['categoria_id' => 4, 'nombre' => '3 Carnes', 'descripcion' => 'Adentro: Philadelphia, aguacate, tocino, res y pollo. Afuera: Gratinado con res, trocitos de tocino y bañado en anguila y sriracha con cebollin.', 'precio' => 180.00],
            ['categoria_id' => 4, 'nombre' => 'RockaRoll', 'descripcion' => 'Adentro: Philadelphia, aguacate, res y camaron. Afuera: Gratinado con tocino y camarones empanizados.', 'precio' => 180.00],
            ['categoria_id' => 4, 'nombre' => 'Bombastic', 'descripcion' => 'Adentro: Revoltura de arroz con las 4 proteinas, forrada de arroz, por fuera gratinado spicy con trozos de pollo y tocino, aguacate y salsa de anguila.', 'precio' => 150.00],
            ['categoria_id' => 4, 'nombre' => 'Kroll', 'descripcion' => 'Adentro: Philadelphia, aguacate, pollo y camaron. Afuera: Gratinado con trozos de tocino coronado con tampico spicy con trozos de camaron y bañado con anguila.', 'precio' => 230.00],

            // Arroces (id=5)
            ['categoria_id' => 5, 'nombre' => 'Gohan Especial', 'descripcion' => 'Tazon de arroz blanco, coronado con philadelphia, tampico, pollo y cebolla capeada y salsa de anguila.', 'precio' => 120.00],
            ['categoria_id' => 5, 'nombre' => 'Gohan Mixto', 'descripcion' => 'Tazon de arroz blanco, coronado con philadelphia, tampico, pollo, res y camarones fritos, decorado con aguacate y salsa de anguila.', 'precio' => 140.00],

            // Promociones (id=6)
            ['categoria_id' => 6, 'nombre' => 'Promo de Mar y Tierra', 'descripcion' => 'Promo de Mar y Tierra', 'precio' => 150.00],
            ['categoria_id' => 6, 'nombre' => 'Promo de 3 Quesos', 'descripcion' => 'Promo de 3 Quesos', 'precio' => 170.00],
            ['categoria_id' => 6, 'nombre' => 'Promo de Guamuchilito', 'descripcion' => 'Promo de Guamuchilito', 'precio' => 190.00],

            // Charolas (id=7)
            ['categoria_id' => 7, 'nombre' => 'Sencilla', 'descripcion' => '2 - Mar y Tierra, 2 - Cielo, Mar y Tierra, 1 - 3 Quesos', 'precio' => 400.00],
            ['categoria_id' => 7, 'nombre' => 'Charola XL', 'descripcion' => '1 - Guamuchilito, 1 - Caribeño, 1 - El de papa, 1 - Ranchero, 1 - Mar y Tierra', 'precio' => 550.00],

            // Bebidas (id=8)
            ['categoria_id' => 8, 'nombre' => 'Te 1/2 Litro', 'descripcion' => 'Te 1/2 Litro', 'precio' => 25.00],
            ['categoria_id' => 8, 'nombre' => 'Te 1 Litro', 'descripcion' => 'Te 1 Litro', 'precio' => 35.00],

            // Extras (id=9)
            ['categoria_id' => 9, 'nombre' => 'Tampico', 'descripcion' => 'Tampico', 'precio' => 25.00],
            ['categoria_id' => 9, 'nombre' => 'Anguila', 'descripcion' => 'Anguila', 'precio' => 10.00],
            ['categoria_id' => 9, 'nombre' => 'Siracha', 'descripcion' => 'Siracha', 'precio' => 10.00],
            ['categoria_id' => 9, 'nombre' => 'Gratinado', 'descripcion' => 'Gratinado', 'precio' => 25.00],
            ['categoria_id' => 9, 'nombre' => 'Soya', 'descripcion' => 'Soya', 'precio' => 10.00],
            ['categoria_id' => 9, 'nombre' => 'Contenedor', 'descripcion' => 'Contenedor', 'precio' => 5.00],
            ['categoria_id' => 9, 'nombre' => 'Res', 'descripcion' => 'Res', 'precio' => 30.00],
            ['categoria_id' => 9, 'nombre' => 'Pollo', 'descripcion' => 'Pollo', 'precio' => 25.00],
            ['categoria_id' => 9, 'nombre' => 'Camaron', 'descripcion' => 'Camaron', 'precio' => 30.00],
            ['categoria_id' => 9, 'nombre' => 'Tocino', 'descripcion' => 'Tocino', 'precio' => 25.00],
        ];

        foreach ($productos as $producto) {
            DB::table('productos')->updateOrInsert(
                [
                    'categoria_id' => $producto['categoria_id'],
                    'nombre'       => $producto['nombre']
                ],
                [
                    'descripcion' => $producto['descripcion'],
                    'precio'      => $producto['precio'],
                    'created_at'  => now(),
                    'updated_at'  => now(),
                ]
            );
        }
    }
}
