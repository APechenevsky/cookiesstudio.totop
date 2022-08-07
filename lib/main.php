<?php

namespace Cookiesstudio\ToTop;

use Bitrix\Main\Config\Option,
    Bitrix\Main\Page\Asset;

/**
 * Class Main
 *
 * @package Cookiesstudio\ToTop
 */
class Main
{
    /**
     * @return void
     */
    public static function appendScriptsToPage()
    {
        // Если не в административном меню
        if((!defined("ADMIN_SECTION") || ADMIN_SECTION!==true))
        {
            $module_id = pathinfo(dirname(__DIR__))["basename"];

            // Подключение строки в секцию <head>
            Asset::getInstance()->addString(
                "<script id=\"".str_replace(".", "__", $module_id)."_params\" data-params='".json_encode(
                    array(
                        "switch_on" => Option::get($module_id, "switch_on", "Y"),
                        "width" => Option::get($module_id, "width", "50"),
                        "height" => Option::get($module_id, "height", "50"),
                        "radius" => Option::get($module_id, "radius", "50"),
                        "color" => Option::get($module_id, "color", "#bf3030"),
                        "side" => Option::get($module_id, "side", "left"),
                        "indent_bottom" => Option::get($module_id, "indent_bottom", "10"),
                        "indent_side" => Option::get($module_id, "indent_side", "10")
                    )
                )."'></script>",
                true
            );

            // Подключение скриптов и стилей в секцию <head>
            Asset::getInstance()->addJs("/bitrix/js/".$module_id."/script.min.js");
            Asset::getInstance()->addCss("/bitrix/css/".$module_id."/style.min.css");
        }
    }
}