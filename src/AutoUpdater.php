<?php

namespace davidglitch04\AutoUpdater;

use davidglitch04\AutoUpdater\command\MainCommand;
use davidglitch04\AutoUpdater\task\Query;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class AutoUpdater extends PluginBase
{

    public static Config $config;

    protected const PREFIX = "&7[&9Auto&aUpdater&7] > ";

    protected function onEnable(): void
    {
        $this->saveDefaultConfig();
        self::$config = $this->getConfig();
        $time = intval(self::$config->get("update-check-time", 10)) * 20;
        $this->getScheduler()->scheduleRepeatingTask(new Query($this), $time*60);
        $this->getServer()->getCommandMap()->register('autoupdater', new MainCommand($this));
    }

    public function getPharName() : string
    {
    	$config = self::$config->getAll();
    	if(strtolower(substr($config["phar-name"], -5)) == ".phar"){
    		return $config["phar-name"];
    	} else{
    		return $config["phar-name"] . ".phar";
    	}
    }

    public function isAutoUpdate() : bool
    {
        return self::$config->get("auto-update", true);
    }

    public function getStartScript() : string
    {
        return self::$config->get("start-script", "");
    }

    public function getTimeout() : int
    {
    	return self::$config->get("timeout", 10);
    }

    public function getPrefix() : string
    {
        return self::PREFIX;
    }
}