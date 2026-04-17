<?php
/***
 * Config generated using LaraAdmin
 * Help: https://laraadmin.com
 * LaraAdmin is open-sourced software licensed under the MIT license.
 * Developed by: Dwij IT Solutions
 * Developer Website: https://dwijitsolutions.com
 */

return [

    /*
    |--------------------------------------------------------------------------
    | General Configuration
    |--------------------------------------------------------------------------
    */

    'adminRoute' => 'admin',
    'blogRoute' => 'blog',
    'ajaxload' => 'data-pjax', // Ajax Page load via Pjax Library. Keep blank to disable

    /*
    |--------------------------------------------------------------------------
    | Uploads Configuration
    |--------------------------------------------------------------------------
    |
    | private_uploads: Uploaded file remains private and can be seen by respective owners + Super Admin only
    | default_public: Will make default uploads public / private
    | allow_filename_change: allows user to modify filenames after upload. Changes will be only in Database not on actual files.
    |
    */

    'uploads' => [
        'private_uploads' => false,
        'default_public' => false,
        'allow_filename_change' => true
    ],

    /*
    |--------------------------------------------------------------------------
    | Log Types
    |--------------------------------------------------------------------------
    */

    'log' => [

        // Users
        'Users' => [
            'USER_CREATED' => ['name' => 'User Created', 'is_notif' => 'false', 'code' => 'USER_CREATED', 'module' => 'Users'],
            'USER_UPDATED' => ['name' => 'User Updated', 'is_notif' => 'false', 'code' => 'USER_UPDATED', 'module' => 'Users'],
            'USER_DELETED' => ['name' => 'User Deleted', 'is_notif' => 'false', 'code' => 'USER_DELETED', 'module' => 'Users'],

            'USER_LOGIN' => ['name' => 'User Login', 'is_notif' => 'false', 'code' => 'USER_LOGIN', 'module' => 'Users'],
            'USER_LOGOUT' => ['name' => 'User Logout', 'is_notif' => 'false', 'code' => 'USER_LOGOUT', 'module' => 'Users'],
        ],

        // Employees
        'Employees' => [
            'EMPLOYEE_CREATED' => ['name' => 'Employee Created', 'is_notif' => 'false', 'code' => 'EMPLOYEE_CREATED', 'module' => 'Employees'],
            'EMPLOYEE_UPDATED' => ['name' => 'Employee Updated', 'is_notif' => 'false', 'code' => 'EMPLOYEE_UPDATED', 'module' => 'Employees'],
            'EMPLOYEE_DELETED' => ['name' => 'Employee Deleted', 'is_notif' => 'false', 'code' => 'EMPLOYEE_DELETED', 'module' => 'Employees'],
        ],

        // Customers
        'Customers' => [
            'CUSTOMER_CREATED' => ['name' => 'Customer Created', 'is_notif' => 'false', 'code' => 'CUSTOMER_CREATED', 'module' => 'Customers'],
            'CUSTOMER_UPDATED' => ['name' => 'Customer Updated', 'is_notif' => 'false', 'code' => 'CUSTOMER_UPDATED', 'module' => 'Customers'],
            'CUSTOMER_DELETED' => ['name' => 'Customer Deleted', 'is_notif' => 'false', 'code' => 'CUSTOMER_DELETED', 'module' => 'Customers'],
        ],

        // Uploads
        'Uploads' => [
            'UPLOAD_CREATED' => ['name' => 'Upload Created', 'is_notif' => 'false', 'code' => 'UPLOAD_CREATED', 'module' => 'Uploads'],
            'UPLOAD_UPDATED' => ['name' => 'Upload Updated', 'is_notif' => 'false', 'code' => 'UPLOAD_UPDATED', 'module' => 'Uploads'],
            'UPLOAD_DELETED' => ['name' => 'Upload Deleted', 'is_notif' => 'false', 'code' => 'UPLOAD_DELETED', 'module' => 'Uploads'],
        ],

        // Backups
        'Backups' => [
            'BACKUP_CREATED' => ['name' => 'Backup Created', 'is_notif' => 'false', 'code' => 'BACKUP_CREATED', 'module' => 'Backups'],
            'BACKUP_DELETED' => ['name' => 'Backup Deleted', 'is_notif' => 'false', 'code' => 'BACKUP_DELETED', 'module' => 'Backups'],
        ],

        // Departments
        'Departments' => [
            'DEPARTMENT_CREATED' => ['name' => 'Department Created', 'is_notif' => 'false', 'code' => 'DEPARTMENT_CREATED', 'module' => 'Departments'],
            'DEPARTMENT_UPDATED' => ['name' => 'Department Updated', 'is_notif' => 'false', 'code' => 'DEPARTMENT_UPDATED', 'module' => 'Departments'],
            'DEPARTMENT_DELETED' => ['name' => 'Department Deleted', 'is_notif' => 'false', 'code' => 'DEPARTMENT_DELETED', 'module' => 'Departments'],
        ],

        // Roles
        'Roles' => [
            'ROLE_CREATED' => ['name' => 'Role Created', 'is_notif' => 'false', 'code' => 'ROLE_CREATED', 'module' => 'Roles'],
            'ROLE_UPDATED' => ['name' => 'Role Updated', 'is_notif' => 'false', 'code' => 'ROLE_UPDATED', 'module' => 'Roles'],
            'ROLE_DELETED' => ['name' => 'Role Deleted', 'is_notif' => 'false', 'code' => 'ROLE_DELETED', 'module' => 'Roles'],
            'MENU_ROLE_ATTACHED' => ['name' => 'Menu role Attached', 'is_notif' => 'false', 'code' => 'MENU_ROLE_ATTACHED', 'module' => 'Roles'],
            'MENU_ROLE_DETACHED' => ['name' => 'Menu role Detached', 'is_notif' => 'false', 'code' => 'MENU_ROLE_DETACHED', 'module' => 'Roles'],
        ],

        // Permissions
        'Permissions' => [
            'PERMISSION_CREATED' => ['name' => 'Permission Created', 'is_notif' => 'false', 'code' => 'PERMISSION_CREATED', 'module' => 'Permissions'],
            'PERMISSION_UPDATED' => ['name' => 'Permission Updated', 'is_notif' => 'false', 'code' => 'PERMISSION_UPDATED', 'module' => 'Permissions'],
            'PERMISSION_DELETED' => ['name' => 'Permission Deleted', 'is_notif' => 'false', 'code' => 'PERMISSION_DELETED', 'module' => 'Permissions'],
        ],

        // Blog_categories
        'Blog_categories' => [
            'BLOG_CATEGORY_CREATED' => ['name' => 'Blog category Created', 'is_notif' => 'false', 'code' => 'BLOG_CATEGORY_CREATED', 'module' => 'Blog_categories'],
            'BLOG_CATEGORY_UPDATED' => ['name' => 'Blog category Updated', 'is_notif' => 'false', 'code' => 'BLOG_CATEGORY_UPDATED', 'module' => 'Blog_categories'],
            'BLOG_CATEGORY_DELETED' => ['name' => 'Blog category Deleted', 'is_notif' => 'false', 'code' => 'BLOG_CATEGORY_DELETED', 'module' => 'Blog_categories'],
        ],

        // Blog_posts
        'Blog_posts' => [
            'BLOG_POST_CREATED' => ['name' => 'Blog post Created', 'is_notif' => 'false', 'code' => 'BLOG_POST_CREATED', 'module' => 'Blog_posts'],
            'BLOG_POST_UPDATED' => ['name' => 'Blog post Updated', 'is_notif' => 'false', 'code' => 'BLOG_POST_UPDATED', 'module' => 'Blog_posts'],
            'BLOG_POST_DELETED' => ['name' => 'Blog post Deleted', 'is_notif' => 'false', 'code' => 'BLOG_POST_DELETED', 'module' => 'Blog_posts'],
        ],

        // LA_logs
        'LA_logs' => [
            'LA_LOG_CREATED' => ['name' => 'LA log Created', 'is_notif' => 'false', 'code' => 'LA_LOG_CREATED', 'module' => 'LA_logs'],
            'LA_LOG_UPDATED' => ['name' => 'LA log Updated', 'is_notif' => 'false', 'code' => 'LA_LOG_UPDATED', 'module' => 'LA_logs'],
            'LA_LOG_DELETED' => ['name' => 'LA log Deleted', 'is_notif' => 'false', 'code' => 'LA_LOG_DELETED', 'module' => 'LA_logs'],
        ],

        // Authores
        'Authores' => [
            'AUTHORE_CREATED' => ["name" => "Authore Created", "is_notif" => "false", "code" => "AUTHORE_CREATED", "module" => "Authores"],
            'AUTHORE_UPDATED' => ["name" => "Authore Updated", "is_notif" => "false", "code" => "AUTHORE_UPDATED", "module" => "Authores"],
            'AUTHORE_DELETED' => ["name" => "Authore Deleted", "is_notif" => "false", "code" => "AUTHORE_DELETED", "module" => "Authores"],
        ],

        // Paginas
        'Paginas' => [
            'PAGINA_CREATED' => ["name" => "Pagina Created", "is_notif" => "false", "code" => "PAGINA_CREATED", "module" => "Paginas"],
            'PAGINA_UPDATED' => ["name" => "Pagina Updated", "is_notif" => "false", "code" => "PAGINA_UPDATED", "module" => "Paginas"],
            'PAGINA_DELETED' => ["name" => "Pagina Deleted", "is_notif" => "false", "code" => "PAGINA_DELETED", "module" => "Paginas"],
        ],

        // Specialties
        'Specialties' => [
            'SPECIALTY_CREATED' => ["name" => "Specialty Created", "is_notif" => "false", "code" => "SPECIALTY_CREATED", "module" => "Specialties"],
            'SPECIALTY_UPDATED' => ["name" => "Specialty Updated", "is_notif" => "false", "code" => "SPECIALTY_UPDATED", "module" => "Specialties"],
            'SPECIALTY_DELETED' => ["name" => "Specialty Deleted", "is_notif" => "false", "code" => "SPECIALTY_DELETED", "module" => "Specialties"],
        ],

        // Chapters
        'Chapters' => [
            'CHAPTER_CREATED' => ["name" => "Chapter Created", "is_notif" => "false", "code" => "CHAPTER_CREATED", "module" => "Chapters"],
            'CHAPTER_UPDATED' => ["name" => "Chapter Updated", "is_notif" => "false", "code" => "CHAPTER_UPDATED", "module" => "Chapters"],
            'CHAPTER_DELETED' => ["name" => "Chapter Deleted", "is_notif" => "false", "code" => "CHAPTER_DELETED", "module" => "Chapters"],
        ],

        // Subchapters
        'Subchapters' => [
            'SUBCHAPTER_CREATED' => ["name" => "Subchapter Created", "is_notif" => "false", "code" => "SUBCHAPTER_CREATED", "module" => "Subchapters"],
            'SUBCHAPTER_UPDATED' => ["name" => "Subchapter Updated", "is_notif" => "false", "code" => "SUBCHAPTER_UPDATED", "module" => "Subchapters"],
            'SUBCHAPTER_DELETED' => ["name" => "Subchapter Deleted", "is_notif" => "false", "code" => "SUBCHAPTER_DELETED", "module" => "Subchapters"],
        ],

        // Lectores
        'Lectores' => [
            'LECTORE_CREATED' => ["name" => "Lectore Created", "is_notif" => "false", "code" => "LECTORE_CREATED", "module" => "Lectores"],
            'LECTORE_UPDATED' => ["name" => "Lectore Updated", "is_notif" => "false", "code" => "LECTORE_UPDATED", "module" => "Lectores"],
            'LECTORE_DELETED' => ["name" => "Lectore Deleted", "is_notif" => "false", "code" => "LECTORE_DELETED", "module" => "Lectores"],
        ],

        // Codigos
        'Codigos' => [
            'CODIGO_CREATED' => ["name" => "Codigo Created", "is_notif" => "false", "code" => "CODIGO_CREATED", "module" => "Codigos"],
            'CODIGO_UPDATED' => ["name" => "Codigo Updated", "is_notif" => "false", "code" => "CODIGO_UPDATED", "module" => "Codigos"],
            'CODIGO_DELETED' => ["name" => "Codigo Deleted", "is_notif" => "false", "code" => "CODIGO_DELETED", "module" => "Codigos"],
        ],

        // More LALogs Configurations - Do not edit this line
    ]
];
