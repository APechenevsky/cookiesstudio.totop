<?php

use Bitrix\Main\Config\Option,
    Bitrix\Main\Loader,
    Bitrix\Main\Localization\Loc,
    Bitrix\Main\HttpApplication;

/**
 * Страница настроек модуля
 */

// Подключение языкового файла
Loc::loadMessages(__FILE__);

// Получение id моудля
$request = HttpApplication::getInstance()->GetContext()->getRequest();
$module_id = htmlSpecialcharsbx($request["mid"] != "" ? $request["mid"] : $request["id"]);

// Подулючение модуля
Loader::includeModule($module_id);

// Массив настроек модуля
$aTabs = array(
    array(
        "DIV" => "edit",
        "TAB" => Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_NAME"), // Заголовок таба
        "TITLE" => Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_NAME"), // Заголовок внутри таба
        "OPTIONS" => array(

            // Заголовок внутри настроек
            Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_COMMON"),

            // Включение/отключение модуля
            array(
                "switch_on",
                Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_SWITCH_ON"),
                "Y",
                array("checkbox")
            ),

            // Заголовок внутри настроек
            Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_APPEARANCE"),

            // Ширина подложки
            array(
                "width",
                Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_WIDTH"),
                "50",
                array("text", 5)
            ),

            // Ширина подложки
            array(
                "height",
                Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_HEIGHT"),
                "50",
                array("text", 5)
            ),

            // Радиус подложки
            array(
                "radius",
                Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_RADIUS"),
                "50",
                array("text", 5)
            ),

            // Цвет подложки
            array(
                "color",
                Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_COLOR"),
                "#cf3030",
                array("text", 5)
            ),

            // Положение (слева, справа)
            array(
                "side",
                Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_SIDE"),
                "left",
                array(
                    "selectbox",
                    array(
                        "left" => Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_SIDE_LEFT"),
                        "right" => Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_SIDE_RIGHT")
                    )
                )
            ),

            // Отступ снизу
            array(
                "indent_bottom",
                Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_INDENT_BOTTOM"),
                "10",
                array("text", 5)
            ),

            // Отступ сбоку
            array(
                "indent_side",
                Loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_TAB_INDENT_SIDE"),
                "10",
                array("text", 5)
            )
        )
    )
);

// Сохранение настроек
if($request->isPost() && check_bitrix_sessid())
{
    // Перебор массива настроек
    foreach($aTabs as $aTab)
    {
        // Перебор опций в настройках
        foreach($aTab["OPTIONS"] as $arOption)
        {
            // Выход, если опции не массив
            if(!is_array($arOption))
            {
                continue;
            }

            // Выход, если есть примечание
            if($arOption["note"])
            {
                continue;
            }

            // Сохранение новых настроек
            if($request["apply"])
            {
                $optionValue = $request->getPost($arOption[0]);

                if($arOption[0] == "switch_on")
                {
                    if($optionValue == "")
                    {
                        $optionValue = "N";
                    }
                }

                Option::set($module_id, $arOption[0], is_array($optionValue) ? implode(",", $optionValue) : $optionValue);
            }
            // Настройки по умолчанию
            elseif($request["default"])
            {
                Option::set($module_id, $arOption[0], $arOption[2]);
            }
        }
    }

    // Обновление страницы
    LocalRedirect($APPLICATION->GetCurPage() . "?mid=" . $module_id . "&lang=" . LANG);
}

//Создание формы настроек, с возможностью сохранять изменения и выставлять параметры по умолчанию
$tabControl = new CAdminTabControl("tabControl", $aTabs);
$tabControl->Begin();
?>

<form action="<?php echo($APPLICATION->getCurPage()); ?>?mid=<?php echo($module_id); ?>&lang=<?php echo(LANG); ?>" method="post">
    <?php
    // Отрисовка полученных настроек из массива
    foreach($aTabs as $aTab)
    {
        if($aTab["OPTIONS"])
        {
            $tabControl->BeginNextTab();
            __AdmSettingsDrawList($module_id, $aTab["OPTIONS"]);
        }
    }

    // Отрисовка кновок формы
    $tabControl->Buttons();
    ?>

    <input type="submit" name="apply" value="<?php echo(loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_INPUT_APPLY")); ?>" class="adm-btn-save" />
    <input type="submit" name="default" value="<?php echo(loc::getMessage("COOKIESSTUDIO_TOTOP_OPTIONS_INPUT_DEFAULT")); ?>" />

    <?php echo(bitrix_sessid_post());?>
</form>

<?php
// Конец отрисовки формы
$tabControl->End();