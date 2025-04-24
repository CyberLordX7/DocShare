<?php

namespace App\Enum;

enum PermissionsEnum: string
{

    case UploadFiles = 'upload_files';
    case DownloadFiles = 'download_files';
    case DeleteFiles = 'delete_files';
    case ViewFileStats = 'view_file_stats';


    case CreateShareLinks = 'create_share_links';
    case ManageShareSettings = 'manage_share_settings';


    case ManageExpiredFiles = 'manage_expired_files';
    case ViewAllFiles = 'view_all_files';
    case ManageSystemSettings = 'manage_system_settings';


    case ManageUsers = 'manage_users';
    case ManageRoles = 'manage_roles';
    case ViewUserFiles = 'view_user_files';
}
