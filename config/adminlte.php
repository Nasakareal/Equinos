<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    */
    'title' => 'Equinos y Caninos',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    */
    'use_ico_only' => true,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Google Fonts
    |--------------------------------------------------------------------------
    */
    'google_fonts' => [
        'allowed' => true,
    ],

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Logo
    |--------------------------------------------------------------------------
    */
    'logo' => '<b style="color:white;">Equinos y Caninos</b>',
    'logo_img' => 'guardiacivil.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'Equinos y Caninos',

    /*
    |--------------------------------------------------------------------------
    | Authentication Logo
    |--------------------------------------------------------------------------
    */
    'auth_logo' => [
        'enabled' => false,
        'img' => [
            'path' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
            'alt' => 'Auth Logo',
            'class' => '',
            'width' => 50,
            'height' => 50,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Preloader Animation
    |--------------------------------------------------------------------------
    */
    'preloader' => [
        'enabled' => true,
        'mode' => 'fullscreen',
        'img' => [
            'path' => 'guardiacivil.png',
            'alt' => 'Equinos y Caninos',
            'effect' => 'animation__shake',
            'class' => 'custom-preloader-img',
            'width' => 300,
            'height' => 300,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    */
    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,
    'usermenu_profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    */
    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,
    'layout_dark_mode' => false,

    /*
    |--------------------------------------------------------------------------
    | Authentication Views Classes
    |--------------------------------------------------------------------------
    */
    'classes_auth_card' => 'card-outline card-primary',
    'classes_auth_header' => '',
    'classes_auth_body' => '',
    'classes_auth_footer' => '',
    'classes_auth_icon' => '',
    'classes_auth_btn' => 'btn-flat btn-primary',

    /*
    |--------------------------------------------------------------------------
    | Admin Panel Classes
    |--------------------------------------------------------------------------
    */
    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_wrapper' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    */
    'sidebar_mini' => 'lg',
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    */
    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    */
    'use_route_url' => false,
    'dashboard_url' => 'home',
    'logout_url' => 'logout',
    'login_url' => 'login',
    'register_url' => 'register',
    'password_reset_url' => 'password/reset',
    'password_email_url' => 'password/email',
    'profile_url' => false,
    'disable_darkmode_routes' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Asset Bundling
    |--------------------------------------------------------------------------
    */
    'laravel_asset_bundling' => false,
    'laravel_css_path' => 'css/app.css',
    'laravel_js_path' => 'js/app.js',

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    */
    'menu' => [

        // ===================== NAVBAR =====================

        [
            'type' => 'navbar-search',
            'text' => 'Buscar',
            'topnav_right' => true,
        ],
        [
            'type' => 'link',
            'text' => 'Scan',
            'url' => 'scan',
            'topnav_right' => true,
            'icon' => 'fa-solid fa-qrcode',
        ],
        [
            'type' => 'fullscreen-widget',
            'topnav_right' => true,
        ],
        [
            'text' => 'Perfil',
            'route' => 'profile',
            'icon' => 'fas fa-fw fa-user',
            'topnav_user' => true,
        ],
        [
            'text' => 'Cambiar Contraseña',
            'route' => 'password.change',
            'icon' => 'fas fa-fw fa-lock',
            'topnav_user' => true,
        ],

        // ===================== SIDEBAR =====================

        ['header' => 'OPERACIÓN'],

        [
            'text' => 'Home',
            'icon' => 'fas fa-fw fa-house',
            'url' => 'home',
        ],

        [
            'text' => 'Personal',
            'icon' => 'fa-solid fa-users',
            'classes' => 'bg-primary text-white',
            'can' => 'ver personal',
            'submenu' => [
                [
                    'text' => 'Listado',
                    'icon' => 'fa-solid fa-list',
                    'classes' => 'text-white',
                    'url' => 'personal',
                    'can' => 'ver personal',
                ],
                [
                    'text' => 'Agregar',
                    'icon' => 'fa-solid fa-plus',
                    'classes' => 'text-white',
                    'url' => 'personal/create',
                    'can' => 'crear personal',
                ],
            ],
        ],

        [
            'text' => 'Incidencias',
            'icon' => 'fa-solid fa-triangle-exclamation',
            'classes' => 'bg-warning text-dark',
            'can' => 'ver incidencias',
            'submenu' => [
                [
                    'text' => 'Listado',
                    'icon' => 'fa-solid fa-list',
                    'url' => 'incidencias',
                    'can' => 'ver incidencias',
                ],
                [
                    'text' => 'Registrar',
                    'icon' => 'fa-solid fa-plus',
                    'url' => 'incidencias/create',
                    'can' => 'crear incidencias',
                ],
                ['header' => 'Catálogos'],
                [
                    'text' => 'Tipos de Incidencia',
                    'icon' => 'fa-solid fa-tags',
                    'url' => 'incidencias/tipos',
                    'can' => 'ver incidencias',
                ],
            ],
        ],

        [
            'text' => 'Armamento',
            'icon' => 'fa-solid fa-gun',
            'classes' => 'bg-navy text-white',
            'can' => 'ver armamento',
            'submenu' => [
                [
                    'text' => 'Inventario',
                    'icon' => 'fa-solid fa-boxes-stacked',
                    'classes' => 'text-white',
                    'url' => 'armamento',
                    'can' => 'ver armamento',
                ],
                [
                    'text' => 'Agregar arma',
                    'icon' => 'fa-solid fa-plus',
                    'classes' => 'text-white',
                    'url' => 'armamento/create',
                    'can' => 'crear armamento',
                ],
                [
                    'text' => 'Asignaciones',
                    'icon' => 'fa-solid fa-clipboard-check',
                    'classes' => 'text-white',
                    'url' => 'armamento-asignaciones',
                    'can' => 'ver armamento',
                ],
                [
                    'text' => 'Nueva asignación',
                    'icon' => 'fa-solid fa-plus',
                    'classes' => 'text-white',
                    'url' => 'armamento-asignaciones/create',
                    'can' => 'crear armamento',
                ],
            ],
        ],

        [
            'text' => 'Turnos y servicio',
            'icon' => 'fa-solid fa-calendar-days',
            'classes' => 'bg-secondary text-white',
            'can' => 'ver turnos',
            'submenu' => [
                [
                    'text' => 'Turnos',
                    'icon' => 'fa-solid fa-people-group',
                    'classes' => 'text-white',
                    'url' => 'turnos',
                    'can' => 'ver turnos',
                ],
                [
                    'text' => 'Horarios por turno',
                    'icon' => 'fa-solid fa-clock',
                    'classes' => 'text-white',
                    'url' => 'turnos-horarios',
                    'can' => 'ver turnos',
                ],
                [
                    'text' => 'Patrón 24x24',
                    'icon' => 'fa-solid fa-repeat',
                    'classes' => 'text-white',
                    'url' => 'servicio',
                    'can' => 'ver turnos',
                ],
            ],
        ],

        [
            'text' => 'Reportes diarios',
            'icon' => 'fa-solid fa-file-excel',
            'classes' => 'bg-success text-white',
            'can' => 'ver reportes',
            'submenu' => [
                [
                    'text' => 'Panel de reportes',
                    'icon' => 'fa-solid fa-table-cells',
                    'classes' => 'text-white',
                    'url' => 'reportes-diarios',
                    'can' => 'ver reportes',
                ],
            ],
        ],

        ['header' => 'ADMINISTRACIÓN'],

        [
            'text' => 'Configuraciones',
            'icon' => 'fas fa-fw fa-gear',
            'classes' => 'bg-dark text-white',
            'can' => 'ver configuraciones',
            'submenu' => [
                [
                    'text' => 'Panel',
                    'icon' => 'fas fa-fw fa-gear',
                    'classes' => 'text-white',
                    'url' => 'admin/settings',
                    'can' => 'ver configuraciones',
                ],
                [
                    'text' => 'Usuarios',
                    'icon' => 'fa-solid fa-user',
                    'classes' => 'text-white',
                    'url' => 'admin/settings/users',
                    'can' => 'ver usuarios',
                ],
                [
                    'text' => 'Roles',
                    'icon' => 'fa-regular fa-flag',
                    'classes' => 'text-white',
                    'url' => 'admin/settings/roles',
                    'can' => 'ver roles',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    */
    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    */
    'plugins' => [

        'FontAwesome' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css',
                ],
            ],
        ],

        'Datatables' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.datatables.net/1.13.8/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.datatables.net/1.13.8/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.datatables.net/1.13.8/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],

        'Select2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/select2/4.1.0-rc.0/css/select2.min.css',
                ],
            ],
        ],

        'Chartjs' => [
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdnjs.cloudflare.com/ajax/libs/Chart.js/4.4.1/chart.umd.min.js',
                ],
            ],
        ],

        'Sweetalert2' => [
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => 'https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css',
                ],
            ],
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | IFrame
    |--------------------------------------------------------------------------
    */
    'iframe' => [
        'default_tab' => [
            'url' => null,
            'title' => null,
        ],
        'buttons' => [
            'close' => true,
            'close_all' => true,
            'close_all_other' => true,
            'scroll_left' => true,
            'scroll_right' => true,
            'fullscreen' => true,
        ],
        'options' => [
            'loading_screen' => 1000,
            'auto_show_new_tab' => true,
            'use_navbar_items' => true,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Livewire
    |--------------------------------------------------------------------------
    */
    'livewire' => false,
];
