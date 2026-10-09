<?php

return [
    'credentials' => env('FIREBASE_CREDENTIALS', storage_path('app/firebase/service-account.json')),
    'database_url' => env('FIREBASE_DATABASE_URL', 'https://menarmed-708d2-default-rtdb.asia-southeast1.firebasedatabase.app'),
];
