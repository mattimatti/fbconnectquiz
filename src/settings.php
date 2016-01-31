<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        
        'baseDomain' => 'http://localhost:9091',
        
        // Renderer settings
        'view' => [
            'template_path' => __DIR__ . '/../templates/',
            'twig' => [
                'cache' => __DIR__ . '/../cache/twig',
                'debug' => true,
                'auto_reload' => true
            ]
        ],
        
        // Monolog settings
        'logger' => [
            'name' => 'slim-app',
            'path' => __DIR__ . '/../logs/app.log'
        ],
        
        // Facebook Api settings
        'facebook' => [
            "app_id" => "126680937488146",
            "app_secret" => "47011911ec9b48a02d3619611d788dbe",
            "default_graph_version" => "v2.5",
            "tab_url" => "https://www.facebook.com/pages/Loophole/306971553786?sk=app_126680937488146",
            "host" => "slim-ar-facebook.taevas.com",
            "permissions" => [
                'email',
                'user_likes'
            ]
        ]
    ]
];
