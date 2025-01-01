<?php
use Illuminate\Support\Facades\Artisan;

Route::get('/clear-views', function () {
    Artisan::call('view:clear');
    return 'View cache cleared successfully!';
});
?>