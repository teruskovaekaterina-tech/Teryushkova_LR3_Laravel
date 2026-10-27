<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Laureate;

class LaureateSeeder extends Seeder
{
    public function run(): void
    {
        dump('SEEDER ЗАПУЩЕН');
        $laureates = [
            [
                'name' => 'Абдулразак Гурна',
                'birth_date' => '1948-12-20',
                'citizenship' => 'Танзания, Великобритания',
                'award_year' => 2021,
                'image_path' => 'laureates/gurnah.jpg',
                'works' => [
                    'Рай — 1994, роман',
                    'У моря — 2001, роман',
                    'Дезертирство — 2005, роман'
                ]
            ],
            [
                'name' => 'Анни Эрно',
                'birth_date' => '1940-09-01',
                'citizenship' => 'Франция',
                'award_year' => 2022,
                'image_path' => 'laureates/ernaux.png',
                'works' => [
                    'Своё место — 1983, роман',
                    'Обыкновенная страсть — 1991, роман',
                    'Годы — 2008, роман'
                ]
            ],
            [
                'name' => 'Юн Фоссе',
                'birth_date' => '1959-09-29',
                'citizenship' => 'Норвегия',
                'award_year' => 2023,
                'image_path' => 'laureates/fosse.jpg',
                'works' => [
                    'Красное, чёрное — 1983, роман',
                    'И мы никогда не расстанемся — 1994, пьеса',
                    'Меланхолия I — 1995, роман'
                ]
            ],
            [
                'name' => 'Хан Ган',
                'birth_date' => '1970-11-27',
                'citizenship' => 'Республика Корея',
                'award_year' => 2024,
                'image_path' => 'laureates/hankang.png',
                'works' => [
                    'Вегетарианка — 2007, роман',
                    'Человеческие поступки — 2014, роман',
                    'Я не прощаюсь — 2021, роман'
                ]
            ],
            [
                'name' => 'Ласло Краснахоркаи',
                'birth_date' => '1954-01-05',
                'citizenship' => 'Венгрия',
                'award_year' => 2025,
                'image_path' => 'laureates/krasznahorkai.png',
                'works' => [
                    'Сатанинское танго — 1985, роман',
                    'Меланхолия сопротивления — 1989, роман',
                    'Война и война — 1999, роман'
                ]
            ]
        ];

        foreach ($laureates as $data) {
            Laureate::updateOrCreate(
                ['name' => $data['name']],
                $data
            );
        }
        dump('КОНЕЦ SEEDER');
        dump('Количество записей: ' . Laureate::count());
    }

}
