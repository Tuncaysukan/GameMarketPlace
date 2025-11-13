<?php

namespace Veles\Http\Controllers;

use Exception;
use Veles\Install\App;
use Veles\Install\Store;
use Illuminate\Http\Response;
use Veles\Install\Database;
use Veles\Install\Permission;
use Illuminate\Http\JsonResponse;
use Veles\Install\Requirement;
use Illuminate\Routing\Controller;
use Illuminate\Contracts\View\View;
use Veles\Install\AdminAccount;
use Illuminate\Contracts\View\Factory;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Veles\Http\Requests\InstallRequest;
use Jackiedo\DotenvEditor\Facades\DotenvEditor;
use Illuminate\Contracts\Foundation\Application;
use Veles\Http\Middleware\RedirectIfInstalled;

class InstallController extends Controller
{
    public function __construct()
    {
        $this->middleware(RedirectIfInstalled::class);
    }


    public function installation(Requirement $requirement, Permission $permission): Factory|View|Application
    {
        return view('install.install', compact('requirement', 'permission'));
    }


    public function install(
        InstallRequest $request,
        Database       $database,
        AdminAccount   $admin,
        Store          $store,
        App            $app
    ): JsonResponse
    {
        @set_time_limit(0);

        try {
            Artisan::call('optimize:clear');

            $database->setup($request);
            $admin->setup($request);
            $store->setup($request);
            $app->setup();

            DotenvEditor::setKey('APP_INSTALLED', 'true')->save();

            Artisan::call('key:generate', ['--force' => true]);

            $success = true;
            $message = "Congratulations! Veles installed successfully";
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();

            try {
                if (Schema::hasTable('migrations')) {
                    Artisan::call('migrate:rollback', ['--force' => true]);
                }
            } catch (Exception $e) {
                $message .= '<br><br>' . $e->getMessage();
            }
        } finally {
            return response()->json(
                [
                    'success' => $success,
                    'message' => $message,
                ],
                $success ? Response::HTTP_OK : Response::HTTP_INTERNAL_SERVER_ERROR
            );
        }
    }
}
