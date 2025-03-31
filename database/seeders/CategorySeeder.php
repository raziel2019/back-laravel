<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Categoría raíz: Frutas
        $frutas = Category::create([
            'code'        => 'FRU',
            'name'        => 'Frutas',
            'description' => 'Frutas variadas y frescas',
            'photo'       => null,
            'parent_id'   => null,
        ]);

        // Hijas de "Frutas"
        Category::create([
            'code'        => 'MAN',
            'name'        => 'Manzanas',
            'description' => 'Manzanas de distintos tipos',
            'photo'       => null,
            'parent_id'   => $frutas->id,
        ]);

        Category::create([
            'code'        => 'NAR',
            'name'        => 'Naranjas',
            'description' => 'Naranjas jugosas y dulces',
            'photo'       => null,
            'parent_id'   => $frutas->id,
        ]);

        // Categoría raíz: Carnes
        $carnes = Category::create([
            'code'        => 'CAR',
            'name'        => 'Carnes',
            'description' => 'Carnes de distintos tipos',
            'photo'       => null,
            'parent_id'   => null,
        ]);

        // Hija de "Carnes"
        Category::create([
            'code'        => 'ROJ',
            'name'        => 'Carne Roja',
            'description' => 'Ternera, buey, cordero...',
            'photo'       => null,
            'parent_id'   => $carnes->id,
        ]);
    }
}
