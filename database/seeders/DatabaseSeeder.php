<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        User::updateOrCreate(['email' => 'admin@aulaviva.test'], ['name' => 'Administración', 'password' => Hash::make('password'), 'is_admin' => true]);
        User::updateOrCreate(['email' => 'cliente@aulaviva.test'], ['name' => 'Cliente Demo', 'password' => Hash::make('password'), 'is_admin' => false]);
        $data = [
            ['Fundamentos de fotografía', 'fotografia', 'Aprende a mirar y capturar mejores imágenes.', 'Domina composición, luz y exposición con ejercicios prácticos.', 149.00, ['Introducción a la mirada fotográfica', 'Composición que funciona', 'Luz y exposición']],
            ['Excel para tu negocio', 'excel-negocio', 'Organiza tus finanzas y decisiones con Excel.', 'Desde fórmulas esenciales hasta tableros claros para tomar decisiones.', 99.00, ['Fórmulas esenciales', 'Tablas dinámicas', 'Tu primer tablero']],
            ['Marketing digital desde cero', 'marketing-digital', 'Construye una presencia digital que atraiga clientes.', 'Una ruta clara para crear contenido, medir resultados y vender en internet.', 179.00, ['Estrategia y audiencia', 'Contenido que conecta', 'Mide y mejora']],
            ['Cocina saludable en casa', 'cocina-saludable', 'Recetas sencillas, nutritivas y llenas de sabor.', 'Planifica tu semana y prepara platos equilibrados sin complicarte.', 129.00, ['Despensa inteligente', 'Técnicas base', 'Menú semanal']],
        ];
        foreach ($data as [$title,$slug,$short,$desc,$price,$lessons]) {
            $course = Course::updateOrCreate(['slug' => $slug], ['title' => $title, 'short_description' => $short, 'description' => $desc, 'price' => $price, 'is_published' => true]);
            foreach ($lessons as $i => $lesson) {
                $course->lessons()->updateOrCreate([
                    'sort_order' => $i,
                ], [
                    'title' => $lesson,
                    'description' => 'Lección práctica pendiente de cargar.',
                    'video_path' => null,
                    'is_preview' => $i === 0,
                ]);
            }
        }
    }
}
