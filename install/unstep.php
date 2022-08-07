<?php

use Bitrix\Main\Localization\Loc;

// Подключение языкового файла
Loc::loadMessages(__FILE__);

// Проверка id сесии
if(!check_bitrix_sessid())
{
    return;
}

// Информация об успешном удалении модуля
echo(CAdminMessage::ShowNote(Loc::getMessage("COOKIESSTUDIO_TOTOP_UNSTEP_BEFORE") . " " . Loc::getMessage("COOKIESSTUDIO_TOTOP_UNSTEP_AFTER")));

// Создание формы, которая возвращает на список модулей
?>

<form action="<?php echo($APPLICATION->GetCurPage()); ?>">
    <input type="hidden" name="lang" value="<?php echo(LANG); ?>" />
    <input type="submit" value="<?php echo(Loc::getMessage("COOKIESSTUDIO_TOTOP_UNSTEP_SUBMIT_BACK")); ?>">
</form>