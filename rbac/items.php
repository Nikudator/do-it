<?php

return [
    'login' => [
        'type' => 2,
    ],
    'logout' => [
        'type' => 2,
    ],
    'email-confirm' => [
        'type' => 2,
    ],
    'password-request' => [
        'type' => 2,
    ],
    'password-reset' => [
        'type' => 2,
    ],
    'resend-verification-email' => [
        'type' => 2,
    ],
    'agreement' => [
        'type' => 2,
    ],
    'auth' => [
        'type' => 2,
    ],
    'error' => [
        'type' => 2,
    ],
    'signup' => [
        'type' => 2,
    ],
    'index' => [
        'type' => 2,
    ],
    'view' => [
        'type' => 2,
    ],
    'update' => [
        'type' => 2,
    ],
    'delete' => [
        'type' => 2,
    ],
    'create' => [
        'type' => 2,
    ],
    'about' => [
        'type' => 2,
    ],
    'contact' => [
        'type' => 2,
    ],
    'captcha' => [
        'type' => 2,
    ],
    'guest' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'login',
            'signup',
            'email-confirm',
            'password-request',
            'resend-verification-email',
            'agreement',
            'password-reset',
            'auth',
            'error',
            'index',
            'view',
            'about',
            'contact',
            'captcha',
        ],
    ],
    'user' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'logout',
            'guest',
        ],
    ],
    'redactor' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'create',
            'updateOwnPost',
            'deleteOwnPost',
            'user',
        ],
    ],
    'moderator' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'redactor',
            'update',
        ],
    ],
    'admin' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'delete',
            'moderator',
        ],
    ],
    'root' => [
        'type' => 1,
        'ruleName' => 'userGroup',
        'children' => [
            'admin',
        ],
    ],
    'updateOwnPost' => [
        'type' => 2,
        'ruleName' => 'isPostOwner',
    ],
    'deleteOwnPost' => [
        'type' => 2,
        'ruleName' => 'isPostOwner',
    ],
];
