<?php

namespace App\Enum;

enum RolesEnum: string
{
    case SuperAdmin = 'super_admin';
    case Admin = 'admin';
    case User = 'user';
    case Guest = 'guest'; 

    public static function labels(): array
    {
        return [
            self::SuperAdmin => 'Super Administrator',
            self::Admin => 'Administrator',
            self::User => 'Authenticated User',
            self::Guest => 'Guest User',
        ];
    }

    public function label(): string
    {
        return match($this) {
            self::SuperAdmin => 'Super Administrator',
            self::Admin => 'Administrator',
            self::User => 'Authenticated User',
            self::Guest => 'Guest User',
        };
    }

    public function defaultPermissions(): array
    {
        return match($this) {
            self::SuperAdmin => [
                PermissionsEnum::UploadFiles,
                PermissionsEnum::DownloadFiles,
                PermissionsEnum::DeleteFiles,
                PermissionsEnum::ViewFileStats,
                PermissionsEnum::CreateShareLinks,
                PermissionsEnum::ManageShareSettings,
                PermissionsEnum::ManageExpiredFiles,
                PermissionsEnum::ViewAllFiles,
                PermissionsEnum::ManageSystemSettings,
                PermissionsEnum::ManageUsers,
                PermissionsEnum::ManageRoles,
                PermissionsEnum::ViewUserFiles,
            ],
            self::Admin => [
                PermissionsEnum::UploadFiles,
                PermissionsEnum::DownloadFiles,
                PermissionsEnum::ViewFileStats,
                PermissionsEnum::CreateShareLinks,
                PermissionsEnum::ManageShareSettings,
                PermissionsEnum::ManageExpiredFiles,
                PermissionsEnum::ViewAllFiles,
                PermissionsEnum::ViewUserFiles,
            ],
            self::User => [
                PermissionsEnum::UploadFiles,
                PermissionsEnum::DownloadFiles,
                PermissionsEnum::ViewFileStats,
                PermissionsEnum::CreateShareLinks,
            ],
            self::Guest => [
                PermissionsEnum::UploadFiles,
                PermissionsEnum::DownloadFiles,
            ],
        };
    }
}
