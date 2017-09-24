<?php

// Dummy exercise generator is in .php file as it seems that
// yml doesn't support random number of entities to be generated
// e.g. construction
// AppBundle\Entity\Exercise:
//     exercise{1..numberBetween(300, 400)}
// didn't work in .yml file

$min = 300;
$max = 400;
$total = mt_rand($min, $max);

return [
    \AppBundle\Entity\Exercise::class => [
        'exercise{1..' . $total . '}' => [
            'description' => '<exercise()>',
            'repetitions' => '<numberBetween(5, 15)>',
            'weight' => '<numberBetween(20, 200)>',
            'date' => '<dateTimeBetween("-30 days", "now")>',
            'time' => '<dateTimeBetween("08:00", "23:00")>',
        ],
    ],
];
