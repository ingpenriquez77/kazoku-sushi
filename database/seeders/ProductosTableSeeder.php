<?php

namespace Database\Seeders;

use App\Models\Categoria;
use App\Models\Producto;
use Illuminate\Database\Seeder;

class ProductosTableSeeder extends Seeder
{
    public function run(): void
    {
        $productos = [
            // Entradas
            ['categoria' => 'Entradas', 'nombre' => 'Chile Relleno', 'descripcion' => 'Mezcla de tampico con una proteina a elegir (res, pollo, camaron o tocino) envuelto con chile caribe.', 'precio' => 60.00],
            ['categoria' => 'Entradas', 'nombre' => 'Goyo Roll', 'descripcion' => 'Rollo de hoja de arroz empanizado y horneado. Por dentro: Philadelphia, aguacate, tampico, res y pollo. Afuera: bañado en anguila.', 'precio' => 70.00],

            // Naturales
            ['categoria' => 'Naturales', 'nombre' => 'Avocado', 'descripcion' => 'Adentro: Philadelphia, aguacate y camaron. Afuera: Forrado de aguacate, bañado en anguila.', 'precio' => 140.00],
            ['categoria' => 'Naturales', 'nombre' => 'Camaronicto', 'descripcion' => 'Adentro: Philadelphia, aguacate y camaron. Afuera: Topping de camaron, cubos de aguacate bañado en anguila.', 'precio' => 165.00],

            // Empanizados
            ['categoria' => 'Empanizados', 'nombre' => 'Mar y Tierra', 'descripcion' => 'Mar y Tierra', 'precio' => 80.00],
            ['categoria' => 'Empanizados', 'nombre' => 'Cielo, Mar y Tierra', 'descripcion' => 'Cielo, Mar y Tierra', 'precio' => 90.00],
            ['categoria' => 'Empanizados', 'nombre' => 'Cordon Blue', 'descripcion' => 'Adentro: Philadelphia, aguacate, pollo y tocino. Afuera: Gratinado.', 'precio' => 100.00],
            ['categoria' => 'Empanizados', 'nombre' => 'Camaron Blue', 'descripcion' => 'Adentro: Philadelphia, aguacate, camaron y tocino. Afuera: Gratinado.', 'precio' => 100.00],
            ['categoria' => 'Empanizados', 'nombre' => '3 Quesos', 'descripcion' => 'Adentro: Philadelphia, aguacate, surimi y camaron. Afuera: Tampico, Gratinado y Philadelphia.', 'precio' => 90.00],
            ['categoria' => 'Empanizados', 'nombre' => 'Guamuchilito', 'descripcion' => 'Adentro: Philadelphia, aguacate, surimi y camaron. Afuera: Tampico, Gratinado y Camaron bañado en anguila.', 'precio' => 110.00],
            ['categoria' => 'Empanizados', 'nombre' => 'El de Papa', 'descripcion' => 'Adentro: Philadelphia, aguacate, res, camaron y tocino. Afuera: Coronado de tampico, aguacate y camaron bañado en anguila.', 'precio' => 150.00],
            ['categoria' => 'Empanizados', 'nombre' => 'Ranchero', 'descripcion' => 'Adentro: Philadelphia, aguacate, res y pollo. Afuera: gratinado con serrano y trozos de tocino.', 'precio' => 110.00],
            ['categoria' => 'Empanizados', 'nombre' => 'Bomba', 'descripcion' => 'Revoltura de tampico con proteina a elegir (res, pollo, camaron o tocino), forrado en arroz.', 'precio' => 90.00],
            ['categoria' => 'Empanizados', 'nombre' => 'Bomba Especial', 'descripcion' => 'Revoltura de tampico con 2 proteinas a elegir (res, pollo, camaron o tocino), forrado en arroz y gratinado con trozos de serrano, tocino y aguacate.', 'precio' => 120.00],
            ['categoria' => 'Empanizados', 'nombre' => 'Vane Roll', 'descripcion' => 'Adentro: Philadelphia, aguacate, res y pollo. Afuera: gratinado con trozos de tocino coronado con tampico.', 'precio' => 90.00],
            ['categoria' => 'Empanizados', 'nombre' => '31 Roll', 'descripcion' => 'Adentro: Philadelphia, aguacate, tocino, res y camaron. Afuera: bañado de aderezo spicy de la casa con trozos de pollo.', 'precio' => 170.00],

            // Especialidades
            ['categoria' => 'Especialidades', 'nombre' => 'El ahogado', 'descripcion' => 'Adentro: Philadelphia, aguacate, surimi empanizado y pollo. Afuera: Bañado de gratinado spicy con trozos de tocino por encima.', 'precio' => 150.00],
            ['categoria' => 'Especialidades', 'nombre' => '3 Carnes', 'descripcion' => 'Adentro: Philadelphia, aguacate, tocino, res y pollo. Afuera: Gratinado con res, trocitos de tocino y bañado en anguila y sriracha con cebollin.', 'precio' => 180.00],
            ['categoria' => 'Especialidades', 'nombre' => 'RockaRoll', 'descripcion' => 'Adentro: Philadelphia, aguacate, res y camaron. Afuera: Gratinado con tocino y camarones empanizados.', 'precio' => 180.00],
            ['categoria' => 'Especialidades', 'nombre' => 'Bombastic', 'descripcion' => 'Adentro: Revoltura de arroz con las 4 proteinas, forrada de arroz, por fuera gratinado spicy con trozos de pollo y tocino, aguacate y salsa de anguila.', 'precio' => 150.00],
            ['categoria' => 'Especialidades', 'nombre' => 'Kroll', 'descripcion' => 'Adentro: Philadelphia, aguacate, pollo y camaron. Afuera: Gratinado con trozos de tocino coronado con tampico spicy con trozos de camaron y bañado con anguila.', 'precio' => 230.00],

            // Arroces
            ['categoria' => 'Arroces', 'nombre' => 'Gohan Especial', 'descripcion' => 'Tazon de arroz blanco, coronado con philadelphia, tampico, pollo y cebolla capeada y salsa de anguila.', 'precio' => 120.00],
            ['categoria' => 'Arroces', 'nombre' => 'Gohan Mixto', 'descripcion' => 'Tazon de arroz blanco, coronado con philadelphia, tampico, pollo, res y camarones fritos, decorado con aguacate y salsa de anguila.', 'precio' => 140.00],

            // Promociones
            ['categoria' => 'Promociones', 'nombre' => 'Promo de Mar y Tierra', 'descripcion' => 'Promo de Mar y Tierra', 'precio' => 150.00],
            ['categoria' => 'Promociones', 'nombre' => 'Promo de 3 Quesos', 'descripcion' => 'Promo de 3 Quesos', 'precio' => 170.00],
            ['categoria' => 'Promociones', 'nombre' => 'Promo de Guamuchilito', 'descripcion' => 'Promo de Guamuchilito', 'precio' => 190.00],

            // Charolas
            ['categoria' => 'Charolas', 'nombre' => 'Sencilla', 'descripcion' => '2 - Mar y Tierra, 2 - Cielo, Mar y Tierra, 1 - 3 Quesos', 'precio' => 400.00],
            ['categoria' => 'Charolas', 'nombre' => 'Charola XL', 'descripcion' => '1 - Guamuchilito, 1 - Caribeño, 1 - El de papa, 1 - Ranchero, 1 - Mar y Tierra', 'precio' => 550.00],

            // Bebidas
            ['categoria' => 'Bebidas', 'nombre' => 'Te 1/2 Litro', 'descripcion' => 'Te 1/2 Litro', 'precio' => 25.00],
            ['categoria' => 'Bebidas', 'nombre' => 'Te 1 Litro', 'descripcion' => 'Te 1 Litro', 'precio' => 35.00],

            // Extras
            ['categoria' => 'Extras', 'nombre' => 'Tampico', 'descripcion' => 'Tampico', 'precio' => 25.00],
            ['categoria' => 'Extras', 'nombre' => 'Anguila', 'descripcion' => 'Anguila', 'precio' => 10.00],
            ['categoria' => 'Extras', 'nombre' => 'Siracha', 'descripcion' => 'Siracha', 'precio' => 10.00],
            ['categoria' => 'Extras', 'nombre' => 'Gratinado', 'descripcion' => 'Gratinado', 'precio' => 25.00],
            ['categoria' => 'Extras', 'nombre' => 'Soya', 'descripcion' => 'Soya', 'precio' => 10.00],
            ['categoria' => 'Extras', 'nombre' => 'Contenedor', 'descripcion' => 'Contenedor', 'precio' => 5.00],
            ['categoria' => 'Extras', 'nombre' => 'Res', 'descripcion' => 'Res', 'precio' => 30.00],
            ['categoria' => 'Extras', 'nombre' => 'Pollo', 'descripcion' => 'Pollo', 'precio' => 25.00],
            ['categoria' => 'Extras', 'nombre' => 'Camaron', 'descripcion' => 'Camaron', 'precio' => 30.00],
            ['categoria' => 'Extras', 'nombre' => 'Tocino', 'descripcion' => 'Tocino', 'precio' => 25.00],
        ];

        foreach ($productos as $item) {
            $categoria = Categoria::where('nombre', $item['categoria'])->first();

            if ($categoria) {
                Producto::updateOrCreate(
                    [
                        'nombre' => $item['nombre'],
                    ],
                    [
                        'categoria_id' => $categoria->_id,
                        'descripcion'  => $item['descripcion'],
                        'precio'       => (float) $item['precio'],
                    ]
                );
            }
        }
    }
}
