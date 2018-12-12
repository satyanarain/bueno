<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
    ],

    'mandrill' => [
        'secret' => env('MANDRILL_SECRET'),
    ],

    'ses' => [
        'key'    => env('SES_KEY'),
        'secret' => env('SES_SECRET'),
        'region' => 'us-east-1',
    ],

    // localhost:8000
   // 'facebook' => [
   //     'client_id'     => '904142363027319',
   //     'client_secret' => 'd60b248207ef917651b6a65d2a7aa890',
   //     'redirect'      => 'http://localhost:8000/login/facebook',
   // ],

   // 'google'  => [
   //   'client_id'     => '778174670309-n4t4s9dh2c5cbhf82g819ppj4l8artto.apps.googleusercontent.com',
   //   'client_secret' => 'pjsMh3wLVPhT7AKeQuG8_s4z',
   //   'redirect'      => 'http://localhost:8000/login/google'
   // ],
 
   //testing.bueno.kitchen
     /*'facebook' => [
         'client_id'     => '1831414300410470',
         'client_secret' => 'b3d7be5c173c2a63aeb31939503d29c9',
         'redirect'      => 'http://bueno.ennerzyy.com/login/facebook',
     ],

     'google'  => [
         'client_id'     => '573587886802-ivnnhq5b82lcjo1qqh8s7umnc23i1iq7.apps.googleusercontent.com',
         'client_secret' => 'dIG1YxkHCF2elLUzFW3n7tJ2',
         'redirect'      => 'http://bueno.ennerzyy.com/login/google'
     ]*/

    // staging.bueno.kitchen
    'facebook' => [
        'client_id'     => '595128497300586',
        'client_secret' => '92dd3218ab6038d26a5f4102e18c26cb',
        'redirect'      => 'http://www.bueno.kitchen/login/facebook',
    ],

    'google'  => [
        'client_id'     => '819498303767-bdtftco7vj6kdhj5b8scabtnpadmnm3p.apps.googleusercontent.com',
        'client_secret' => 'aJ5BccAioJ6Q3_O6kLXa5p_T',
        'redirect'      => 'http://www.bueno.kitchen/login/google'
    ]

];
