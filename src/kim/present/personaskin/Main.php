<?php

/**
 *
 *  ____                           _   _  ___
 * |  _ \ _ __ ___  ___  ___ _ __ | |_| |/ (_)_ __ ___
 * | |_) | '__/ _ \/ __|/ _ \ '_ \| __| ' /| | '_ ` _ \
 * |  __/| | |  __/\__ \  __/ | | | |_| . \| | | | | | |
 * |_|   |_|  \___||___/\___|_| |_|\__|_|\_\_|_| |_| |_|
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author  PresentKim
 * @link    https://github.com/PresentKim
 * @license https://www.gnu.org/licenses/lgpl-3.0 LGPL-3.0 License
 *
 *   (\ /)
 *  ( . .) ♥
 *  c(")(")
 */

declare(strict_types=1);

namespace kim\present\personaskin;

use pocketmine\network\mcpe\convert\SkinAdapter;
use pocketmine\network\mcpe\convert\TypeConverter;
use pocketmine\network\mcpe\protocol\ProtocolInfo;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase{

    private ?SkinAdapter $originalAdaptor = null;

    protected function onEnable() : void{
        /**
         * This is a plugin that does not use data folders.
         * Delete the unnecessary data folder of this plugin for users.
         */
        $dataFolder = $this->getDataFolder();
        if(is_dir($dataFolder) && count(scandir($dataFolder)) <= 2){
            rmdir($dataFolder);
        }
        foreach (defined(ProtocolInfo::class . "::ACCEPTED_PROTOCOL") ? ProtocolInfo::ACCEPTED_PROTOCOL : [ProtocolInfo::CURRENT_PROTOCOL] as $protocolId) {
            if (isset($this->states[$protocolId])) {
                continue;
            }
            $typeConverter = TypeConverter::getInstance($protocolId); 
            $this->originalAdaptor = $typeConverter->getSkinAdapter();
            $typeConverter->setSkinAdapter(new PersonaSkinAdapter());
        }
    }

    protected function onDisable() : void{
        if($this->originalAdaptor !== null){
            TypeConverter::getInstance()->setSkinAdapter($this->originalAdaptor);
        }
    }
}
