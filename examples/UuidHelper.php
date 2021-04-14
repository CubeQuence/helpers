<?php

use CQ\Helpers\UuidHelper;

echo json_encode([
    'uuidv4' => UuidHelper::v4(),
    'uuidv5' => UuidHelper::v5(name: 'CubeQuence'),
    'uuidv6' => UuidHelper::v6(),
]);
