<?php

declare(strict_types=1);

return [
    'host' => getenv('HEALTHY_BITE_DB_HOST') ?: '127.0.0.1',
    'port' => getenv('HEALTHY_BITE_DB_PORT') ?: '3306',
    'database' => getenv('HEALTHY_BITE_DB_NAME') ?: 'healthy_bite',
    'username' => getenv('HEALTHY_BITE_DB_USER') ?: 'root',
    'password' => getenv('HEALTHY_BITE_DB_PASSWORD') ?: '',
    'charset' => 'utf8mb4',
];
