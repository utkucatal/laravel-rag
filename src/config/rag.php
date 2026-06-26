<?php
return [
    'embed_model' => 'voyage-3',
    'embed_dim'   => 1024,
    'gen_model'   => 'claude-sonnet-4-6',
    'top_k'       => 5,
    'min_sim'     => 0.3,
    'shop_base'   => env('SHOP_BASE_URL'),
];
