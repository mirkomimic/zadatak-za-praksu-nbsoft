<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInitbad8e2b188657bf5fabb1cd75d5a24e2
{
    public static $prefixLengthsPsr4 = array (
        'A' => 
        array (
            'App\\' => 4,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'App\\' => 
        array (
            0 => __DIR__ . '/../..' . '/app',
        ),
    );

    public static $classMap = array (
        'App\\Controllers\\Auth\\SessionController' => __DIR__ . '/../..' . '/app/Controllers/Auth/SessionController.php',
        'App\\Controllers\\OrderController' => __DIR__ . '/../..' . '/app/Controllers/OrderController.php',
        'App\\Controllers\\OrderItemController' => __DIR__ . '/../..' . '/app/Controllers/OrderItemController.php',
        'App\\Controllers\\ProductController' => __DIR__ . '/../..' . '/app/Controllers/ProductController.php',
        'App\\Controllers\\UserController' => __DIR__ . '/../..' . '/app/Controllers/UserController.php',
        'App\\Database\\Db' => __DIR__ . '/../..' . '/app/Database/Db.php',
        'App\\Http\\Response' => __DIR__ . '/../..' . '/app/Http/Response.php',
        'App\\Models\\Order' => __DIR__ . '/../..' . '/app/Models/Order.php',
        'App\\Models\\OrderItem' => __DIR__ . '/../..' . '/app/Models/OrderItem.php',
        'App\\Models\\Product' => __DIR__ . '/../..' . '/app/Models/Product.php',
        'App\\Models\\User' => __DIR__ . '/../..' . '/app/Models/User.php',
        'App\\Resources\\OrderResource' => __DIR__ . '/../..' . '/app/Resources/OrderResource.php',
        'App\\Utilities\\Paginator' => __DIR__ . '/../..' . '/app/Utilities/Paginator.php',
        'App\\Utilities\\Query' => __DIR__ . '/../..' . '/app/Utilities/Query.php',
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInitbad8e2b188657bf5fabb1cd75d5a24e2::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInitbad8e2b188657bf5fabb1cd75d5a24e2::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInitbad8e2b188657bf5fabb1cd75d5a24e2::$classMap;

        }, null, ClassLoader::class);
    }
}
