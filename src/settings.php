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
            "app_id" => "634642500001239",
            "app_secret" => "bb932950d2bf8ad84de3b115161fb138",
            "default_graph_version" => "v2.5"
        ],
        
        "facebook-permissions" => [
            'email',
            'user_likes'
        ]
    ]
    
];
