<?php

return [
    'meta'      => [
        /*
         * The default configurations to be used by the meta generator.
         */
        'defaults'       => [
            'title'       => "buéno - Premium Sandwich & Bakery Café", // set false to total remove
            'description' => 'Order at www.bueno.cafe and get gourmet food delivered in Gurgaon crafted in our own kitchens by 5 Star Chef Teams. Now serving multiple cuisines including American, Mexican, Italian, European, Middle Eastern, Indian & Pan-Asian. Call +91 11 39586767"', 
            'separator'   => ' | ',
            'keywords'    => [],
        ],

        /*
         * Webmaster tags are always added.
         */
        'webmaster_tags' => [
            'google'    => null,
            'bing'      => null,
            'alexa'     => null,
            'pinterest' => null,
            'yandex'    => null,
        ],
    ],
    'opengraph' => [
        /*
         * The default configurations to be used by the opengraph generator.
         */
        'defaults' => [
            'title'       => "buéno - Premium Sandwich & Bakery Café", // set false to total remove
            'description' => 'Order at www.bueno.cafe and get gourmet food delivered in Gurgaon crafted in our own kitchens by 5 Star Chef Teams. Now serving multiple cuisines including American, Mexican, Italian, European, Middle Eastern, Indian & Pan-Asian. Call +91 11 39586767"', // set false to total remove
            'url'         => false,
            'type'        => false,
            'site_name'   => "Bueno - Premium Sandwich & Bakery Café",
            'images'      => [],
        ],
    ],
    'twitter' => [
        /*
         * The default values to be used by the twitter cards generator.
         */
        'defaults' => [
          'card'        => "buéno - Premium Sandwich & Bakery Café",
          'site'        => '',
        ],
    ],
];
