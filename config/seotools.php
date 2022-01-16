<?php

return [
    'meta' => [
        'defaults'       => [
            'title'        => 'Leaf - Halo Infinite Stats',
            'titleBefore'  => false,
            'description'  => '',
            'separator'    => ' - ',
            'keywords'     => [],
            'canonical'    => false,
            'robots'       => 'all',
        ],
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
            'norton'    => null,
        ],

        'add_notranslate_class' => false,
    ],
    'opengraph' => [
        'defaults' => [
            'title'       => '',
            'description' => '',
            'url'         => false,
            'type'        => false,
            'site_name'   => false,
            'images'      => [],
        ],
    ],
    'twitter' => [
        'defaults' => [
        ],
    ],
    'json-ld' => [
        'defaults' => [
            'title'       => '',
            'description' => '',
            'url'         => false,
            'type'        => 'WebPage',
            'images'      => [],
        ],
    ],
];
