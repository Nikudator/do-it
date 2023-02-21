<?php
        
return 
[
   'class' => 'yii\authclient\Collection',
            'clients' => [
                'vkontakte' => [
                    'class' => 'yii\authclient\clients\VKontakte',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
                'yandex' => [
                    'class' => 'yii\authclient\clients\Yandex',
                    'clientId' => '',
                    'clientSecret' => '',
                ],
            ],
    ];
