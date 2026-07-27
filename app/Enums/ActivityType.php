<?php

declare(strict_types=1);

namespace App\Enums;

use Modules\Base\Traits\Enum\EnumTranslatable;

enum ActivityType: string
{
    use EnumTranslatable;

    // ============ View ============
    case ViewPage = 'view_page';
    case ViewBanner = 'view_banner';

    // ============ Click ============
    case ClickDetail = 'click_detail';
    case ClickProductLink = 'click_product_link';
    case ClickBanner = 'click_banner';
    case ClickLogin = 'click_login';
    case ClickLoginSso = 'click_login_sso';

    // ============ Selection ============
    case SelectPart = 'select_part';
    case SelectScene = 'select_scene';
    case SelectTexture = 'select_texture';
    case SelectSceneCategory = 'select_scene_category';

    // ============ Compare & Download ============
    case CompareActivate = 'compare_activate';
    case Download = 'download';
    case DownloadBlocked = 'download_blocked';

    // ============ Process Request (Upload) ============
    case UploadSuccess = 'upload_success';
    case UploadFailed = 'upload_failed';

    // ============ Auth ============
    case LoginSuccess = 'login_success';
    case LoginFailed = 'login_failed';
    case Logout = 'logout';
}
