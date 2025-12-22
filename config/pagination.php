<?php

return [
    // Number of pages to show at the beginning (kept for backward compatibility)
    'leading' => env('PAGINATION_LEADING', 10),

    // Number of pages to show at the end
    'trailing' => env('PAGINATION_TRAILING', 2),

    // Window size used for forward-looking pagination: show current page and the next N-1 pages
    'window_size' => env('PAGINATION_WINDOW', 10),
];
