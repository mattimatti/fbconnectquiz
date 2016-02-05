<?php
return [
    'settings' => [
        'displayErrorDetails' => true,
        
        'baseDomain' => 'http://localhost:8081',
        
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
            "app_id" => "636075736524582",
            "app_secret" => "a07f5d230dfb0b0f3b2198262e8b94c6",
            "default_graph_version" => "v2.5"
        ],
        
        "facebook-permissions" => [
            'email',
            'public_profile',
            'user_about_me',
            'publish_actions',
            'user_friends'
        ],
        
        "database" => [
            'host' => 'localhost',
            'dbname' => 'quiz',
            'username' => 'root',
            'password' => '1234'
        ]
    ]
];