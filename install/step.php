<?php

use Bitrix\Main\Localization\Loc;

// Подключение языкового файла
Loc::loadMessages(__FILE__);

// Проверка id сесии
if(!check_bitrix_sessid())
{
    return;
}

// Проверка на ошибки
if($errorException = $APPLICATION->GetException())
{
    // При возниконовении ошибки выводится информация о ней
    echo(CAdminMessage::ShowMessage($errorException->GetString()));
}
else
{
    // При отсутсвии ошибки выводится информация об успешной установке
    echo(CAdminMessage::ShowNote(Loc::getMessage("COOKIESSTUDIO_TOTOP_STEP_BEFORE")." ".Loc::getMessage("COOKIESSTUDIO_TOTOP_STEP_AFTER")));
}

//Создание формы, которая возвращает на список модулей
?>

<form action="<?php echo($APPLICATION->GetCurPage()); ?>">
    <input type="hidden" name="lang" value="<?php echo(LANG); ?>" />
    <input type="submit" value="<?php echo(Loc::getMessage("COOKIESSTUDIO_TOTOP_STEP_SUBMIT_BACK")); ?>">
</form>