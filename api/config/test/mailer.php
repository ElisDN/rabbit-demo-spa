<?php

declare(strict_types=1);

use Psr\Container\ContainerInterface;

return [
    Swift_Mailer::class => function (ContainerInterface $container) {
        $config = $container->get('config')['mailer'];
        $transport = new \Geekdevs\SwiftMailer\Transport\FileTransport(
            new \Swift_Events_SimpleEventDispatcher(),
            $config['local_path']
        );
        return new Swift_Mailer($transport);
    },

    'config' => [
        'mailer' => [
            'local_path' => 'var/mail',
        ],
    ],
];