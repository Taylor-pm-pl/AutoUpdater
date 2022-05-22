<?php

namespace davidglitch04\AutoUpdater\command;

use davidglitch04\AutoUpdater\AutoUpdater;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\Plugin;
use pocketmine\plugin\PluginOwned;
use pocketmine\utils\TextFormat;

class MainCommand extends Command implements PluginOwned
{
    protected AutoUpdater $autoUpdater;

    public function __construct(AutoUpdater $autoUpdater)
    {
        $this->autoUpdater = $autoUpdater;
        parent::__construct("autoupdater");
        $this->setAliases(["au"]);
        $this->setDescription("AutoUpdate commands");
        $this->setPermission("autoupdater.allow.command");
    }

    public function getOwningPlugin(): Plugin
    {
        return $this->autoUpdater;
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if (!isset($args[0])){
            $sender->sendMessage(TextFormat::colorize("&9+ &aAvailable Commands &9+"));
    		$sender->sendMessage(TextFormat::colorize("&e/au reload &9-&e Reload the config"));
        } elseif ($args[0] == "reload"){
            $this->autoUpdater->reloadConfig();
    		$sender->sendMessage(TextFormat::colorize($this->autoUpdater->getPrefix() . "&aConfiguration Reloaded."));
        }
    }
}