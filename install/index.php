<?php

use Bitrix\Main\Localization\Loc,
    Bitrix\Main\ModuleManager,
    Bitrix\Main\Config\Option,
    Bitrix\Main\EventManager,
    Bitrix\Main\Application,
    Bitrix\Main\IO\Directory;

//Подключение языкового файла
Loc::loadMessages(__FILE__);

/**
 * Класс для установки и удаления модуля.
 *
 * Class cookiesstudio_totop
 */
class cookiesstudio_totop extends CModule
{
    /**
     * cookiesstudio_totop constructor
     */
    public function __construct()
    {
        if(file_exists(__DIR__ . "/version.php"))
        {
            $arModuleVersion = array();

            include_once(__DIR__ . "/version.php");

            $this->MODULE_ID = str_replace("_", ".", get_class($this));
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
            $this->MODULE_NAME = Loc::getMessage("COOKIESSTUDIO_TOTOP_NAME");
            $this->MODULE_DESCRIPTION = Loc::getMessage("COOKIESSTUDIO_TOTOP_DESCRIPTION");
            $this->PARTNER_NAME = Loc::getMessage("COOKIESSTUDIO_TOTOP_PARTNER_NAME");
            $this->PARTNER_URI = Loc::getMessage("COOKIESSTUDIO_TOTOP_PARTNER_URI");
        }
    }

    /**
     * Установка модуля
     *
     * @return void
     */
    public function DoInstall()
    {
        global $APPLICATION;

        // Проверка версии системы, убеждаемся в поддержке функционала D7
        if(CheckVersion(ModuleManager::getVersion("main"), "14.00.00"))
        {
            ModuleManager::registerModule($this->MODULE_ID); // Регистрирация модуля в системе

            $this->InstallFiles(); // Добавление файлов в систему
            $this->InstallDB(); // Добавление таблиц в ДБ
            $this->InstallEvents(); // Добавление новых событий
        }
        else
        {
            $APPLICATION->ThrowException(Loc::getMessage("COOKIESSTUDIO_TOTOP_INSTALL_ERROR_VERSION"));
        }

        // Подключение файла step.php
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("COOKIESSTUDIO_TOTOP_INSTALL_TITLE") . " \"" . Loc::getMessage("COOKIESSTUDIO_TOTOP_NAME") . "\"",
            __DIR__ . "/step.php"
        );
    }

    /**
     * Удаление модуля
     *
     * @return void
     */
    public function DoUninstall()
    {
        global $APPLICATION;

        $this->UnInstallFiles(); // Удаление файлов из системы
        $this->UnInstallDB(); // Удаление таблиц и БД
        $this->UninstallEvents(); // Удаление событй

        ModuleManager::unRegisterModule($this->MODULE_ID); // Удаление регистрации модуля из системы

        // Подключение файла unstep.php
        $APPLICATION->IncludeAdminFile(
            Loc::getMessage("COOKIESSTUDIO_TOTOP_UNINSTALL_TITLE") . " \"" . Loc::getMessage("COOKIESSTUDIO_TOTOP_NAME") . "\"",
            __DIR__ . "/unstep.php"
        );
    }

    /**
     * Добавление скриптов и стилей в систему
     *
     * @return void
     */
    public function InstallFiles()
    {
        CopyDirFiles(
            __DIR__ . "/assets/scripts",
            Application::getDocumentRoot() . "/bitrix/js/" . $this->MODULE_ID . "/",
            true,
            true
        );

        CopyDirFiles(
            __DIR__ . "/assets/styles",
            Application::getDocumentRoot() . "/bitrix/css/" . $this->MODULE_ID . "/",
            true,
            true
        );
    }

    /**
     * Удаление скриптов и стилей из системы
     *
     * @return void
     */
    public function UnInstallFiles()
    {
        Directory::deleteDirectory(Application::getDocumentRoot() . "/bitrix/js/" . $this->MODULE_ID);
        Directory::deleteDirectory(Application::getDocumentRoot() . "/bitrix/css/" . $this->MODULE_ID);
    }

    /**
     * Добавление таблиц в БД
     *
     * @return false|void
     */
    public function InstallDB()
    {

    }

    /**
     * Удаление таблиц из БД
     *
     * @return void
     */
    public function UnInstallDB()
    {
        Option::delete($this->MODULE_ID); // удаление настроек модуля
    }

    /**
     * Регистрируем событие OnBeforeEndBufferContent
     *
     * @return void
     */
    public function InstallEvents()
    {
        EventManager::getInstance()->registerEventHandler(
            "main",
            "OnBeforeEndBufferContent",
            $this->MODULE_ID,
            "Cookiesstudio\ToTop\Main",
            "appendScriptsToPage"
        );
    }

    /**
     * Удаление регистрации события OnBeforeEndBufferContent
     *
     * @return void
     */
    public function UnInstallEvents()
    {
        EventManager::getInstance()->unRegisterEventHandler(
            "main",
            "OnBeforeEndBufferContent",
            $this->MODULE_ID,
            "Cookiesstudio\ToTop\Main",
            "appendScriptsToPage"
        );
    }
}