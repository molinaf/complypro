<?php
/*Auth::routes();

  GET|HEAD   / .......................................................... generated::gDkH6W6iNXhvl9R7
  GET|HEAD   home ....................................................... home › HomeController@index
  GET|HEAD   login ....................................... login › Auth\LoginController@showLoginForm
  POST       login ......................... generated::lOs3CuGJQLruqKy4 › Auth\LoginController@login
  POST       logout ............................................ logout › Auth\LoginController@logout
  GET|HEAD   password/confirm ..... password.confirm › Auth\ConfirmPasswordController@showConfirmForm
  POST       password/confirm .. generated::fwGgjnPx3a1n1BXo › Auth\ConfirmPasswordController@confirm
  POST       password/email ....... password.email › Auth\ForgotPasswordController@sendResetLinkEmail
  GET|HEAD   password/reset .... password.request › Auth\ForgotPasswordController@showLinkRequestForm
  POST       password/reset .................... password.update › Auth\ResetPasswordController@reset
  GET|HEAD   password/reset/{token} ..... password.reset › Auth\ResetPasswordController@showResetForm
  GET|HEAD   register ....................... register › Auth\RegisterController@showRegistrationForm
  POST       register ................ generated::ZywURaa1txEVVqXG › Auth\RegisterController@register
  GET|HEAD   storage/{path} ........................................................... storage.local
  GET|HEAD   up ......................................................... generated::Z54LNlRe7olBjIeC
*/

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\ModuleController;
use App\Http\Controllers\LicenseController;
use App\Http\Controllers\InductionController;
use App\Http\Controllers\F2FController;
use App\Http\Controllers\AuthorisationController;
use App\Http\Controllers\LinkController;
use App\Http\Controllers\MtceController;
use App\Http\Controllers\PivotController;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\SupervisorController;
use App\Http\Controllers\AdminController;
use App\Models\User;

// Route for loading users dynamically
Route::middleware(['auth'])->get('/company/{company}/users', function ($company) {
    return response()->json(
        User::where('company_id', $company)->get(['id', 'name'])
    );
})->name('company.users');

Auth::routes();
Route::get('/email/verify-notification', function () {
    return view('auth.verify-notification');
})->name('verification.notice');
Route::get('/email/verify/{id}/{hash}', [RegisterController::class, 'verifyEmail'])->name('email.verify');
Route::get('/email/sent', function () {
    return view('auth.email_sent');
})->name('email.sent');
Route::post('/email/resend', [RegisterController::class, 'resendVerification'])->name('verification.resend');
Route::get('/endorse/{id}', [SupervisorController::class, 'endorseUser'])->name('endorse.user');
Route::post('/endorse/{id}', [SupervisorController::class, 'endorseUser'])->name('endorse.user');
Route::get('/supervisor/pending-users', [SupervisorController::class, 'showPendingUsers'])->name('supervisor.pending.users');

Route::get('/dashboard/user', function () {
    return view('dashboard.user');
})->name('dashboard.user');
Route::get('/dashboard/admin', function () {
    return view('dashboard.admin');
})->name('dashboard.admin');
Route::get('/dashboard/supervisor', [SupervisorController::class, 'dashboard'])->name('dashboard.supervisor');
Route::get('/dashboard/admin', [AdminController::class, 'dashboard'])->name('dashboard.admin');
Route::get('/dashboard/global-supervisor', [SupervisorController::class, 'globalDashboard'])->name('dashboard.global.supervisor');
Route::get('/dashboard/manager', [AdminController::class, 'managerDashboard'])->name('dashboard.manager');


Route::get('/admin/manage-roles', [AdminController::class, 'manageRoles'])->name('admin.manage.roles');
Route::get('/admin/manage/roles/showAll', [AdminController::class, 'showAll'])->name('admin.manage.roles.showAll');
Route::post('/admin/update-role/{id}', [AdminController::class, 'updateUserRole'])->name('admin.update.role');
Route::get('/admin/edit-user/{id}', [AdminController::class, 'editUser'])->name('admin.edit.user');
Route::post('/admin/update-user/{id}', [AdminController::class, 'updateUser'])->name('admin.update.user');
Route::get('/dashboard/global-supervisor', [SupervisorController::class, 'globalDashboard'])->name('dashboard.global.supervisor');
Route::get('/dashboard/manager', [AdminController::class, 'managerDashboard'])->name('dashboard.manager');




Route::get('/test-mail', function () {
    Mail::raw('This is a test email from Laravel.', function ($message) {
        $message->to('supervisor1@complypro.com.au')
            ->subject('Test Email');
    });
    return 'Test email sent.';
});

Route::view('/', 'welcome_page');
Route::view('/clear_views', 'clear_views');
Route::view('/index-tails', 'index-tails');
Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::view('/main', 'main');
Route::view('/coming', 'coming');
Route::get('/circuit', [LinkController::class, 'schematic']);
Route::get('/link_table/{relatedTable}', [LinkController::class, 'showTable']);
Route::get('/requisites', [LinkController::class, 'requisites']);
Route::post('/submit-selection', [PivotController::class, 'store'])->name('pivot.store');


Route::get('/relate_table', [LinkController::class, 'listAuthorisations'])->name('authorisations.list');
Route::get('/authorisations/{id}/related_items', [AuthorisationController::class, 'relatedItems'])->name('authorisation.relatedItems');
Route::middleware(['auth'])->group(function () {
    Route::get('/authorisations/create', [AuthorisationController::class, 'create'])->name('authorisations.create');
    Route::post('/authorisations/store', [AuthorisationController::class, 'store'])->name('authorisations.store');
});
Route::middleware(['auth'])->group(function () {
    Route::get('/authorisations/register', [AuthorisationController::class, 'registrationPage'])
        ->name('authorisations.register');
});
Route::post('/authorisations/store', [AuthorisationController::class, 'store'])->name('authorisations.store');
Route::get('/authorisations/view', [AuthorisationController::class, 'viewUserDetails'])->name('authorisations.view');


Route::prefix('{model}')->name('dynamic.')->group(function () {
    Route::get('/', [MtceController::class, 'index'])->name('index');
    Route::get('/create', [MtceController::class, 'create'])->name('create');
    Route::post('/store', [MtceController::class, 'store'])->name('store');
    Route::get('/{id}/edit', [MtceController::class, 'edit'])->name('edit');
    Route::put('/{id}', [MtceController::class, 'update'])->name('update');
    Route::post('/{id}/delete', [MtceController::class, 'destroy'])->name('delete');
    Route::get('/{id}/debug', function ($model, $id) {
        return "Model: $model, ID: $id";
    });
});

/**
 * Resolve the correct controller for the given model.
 *
 * @param string $model
 * @return string
 */
function resolveController($model)
{
    $controllers = [
        'categories' => CategoryController::class,
        'modules' => ModuleController::class,
        'licenses' => LicenseController::class,
        'inductions' => InductionController::class,
        'f2fs' => F2FController::class,
        'authorisations' => AuthorisationController::class,
        'categories' => CategoryController::class,
    ];

    if (!array_key_exists($model, $controllers)) {
        abort(404, "Model not found: $model");
    }

    return $controllers[$model];
}
