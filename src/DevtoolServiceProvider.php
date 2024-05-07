<?php

namespace Sokeio\Devtool;

use Illuminate\Support\ServiceProvider;
use Sokeio\Laravel\ServicePackage;
use Sokeio\Concerns\WithServiceProvider;
use Sokeio\Facades\Menu;
use Sokeio\Facades\Platform;
use Sokeio\Menu\MenuBuilder;

class DevtoolServiceProvider extends ServiceProvider
{
    use WithServiceProvider;

    public function configurePackage(ServicePackage $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         */
        $package
            ->name('devtool')
            ->hasConfigFile()
            ->hasViews()
            ->hasHelpers()
            ->hasAssets()
            ->hasTranslations()
            ->runsMigrations();
    }
    public function packageRegistered()
    {
        // packageRegistered
        Platform::ready(function () {
            if (sokeioIsAdmin()) {
                Menu::Register(function () {
                    menuAdmin()
                        ->subMenu(
                            __('Devtool'),
                            '<i class="ti ti-subtask fs-2"></i>',
                            function (MenuBuilder $menu) {
                                $menu->setTargetId('devtool_menu');
                                $menu->route([
                                    'name' => 'admin.devtool.crud',
                                    'params' => []
                                ], __('CRUD'), '', [], 'admin.devtool.crud');
                            },
                            10
                        );
                });
            }
        });
    }
    private function bootGate()
    {
        if (!$this->app->runningInConsole()) {
            addFilter(PLATFORM_PERMISSION_CUSTOME, function ($prev) {
                return [
                    ...$prev
                ];
            });
        }
    }
    public function packageBooted()
    {
        $this->bootGate();
        Menu:
    }
}
