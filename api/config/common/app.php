<?php

declare(strict_types=1);

use Api\Http\Action;

return [
    Action\HomeAction::class => function () {
        return new Action\HomeAction();
    },
];