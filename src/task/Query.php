<?php

namespace davidglitch04\AutoUpdater\task;

use davidglitch04\AutoUpdater\AutoUpdater;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\utils\TextFormat;

class Query extends Task
{
    protected AutoUpdater $autoUpdater;
    
    public function __construct(AutoUpdater $autoUpdater)
    {
        $this->autoUpdater = $autoUpdater;
    }

    public function onRun(): void
    {
        $currentAPI = Server::getInstance()->getPocketMineVersion();
        $data = $this->autoUpdater->getData();
        $logger = Server::getInstance()->getLogger();
        $prefix = $this->autoUpdater->getPrefix();
        if (isset($data["name"])){
            $lastAPI = $this->autoUpdater->getLastAPI();
            if (version_compare($lastAPI, $currentAPI, '>')){
                $phar = $this->autoUpdater->getPharName();
                $link = "https://github.com/pmmp/PocketMine-MP/releases/download/".$lastAPI."/PocketMine-MP.phar";
                $logger->info(TextFormat::colorize($prefix . "&aA new PocketMine update is available!"));
                $logger->info(TextFormat::colorize($prefix . "&eDetails: PocketMine-MP API " . $lastAPI . " was released on " . $data["created_at"]));
                $logger->info(TextFormat::colorize($prefix . "&eDownload URL: " . $link));
                if ($this->autoUpdater->isAutoUpdate()){
                    $logger->info(TextFormat::colorize($prefix . "&aUpdating PocketMine..."));
                    Server::getInstance()->getAsyncPool()->submitTask(new Download($link, $this->autoUpdater->getDataFolder() . "/" . $phar));
                    sleep($this->autoUpdater->getTimeout());
                    if (file_exists($this->autoUpdater->getDataFolder() . "/" . $phar)){
                        $logger->info(TextFormat::colorize($prefix . "&aPocketMine updated. Restarting server now..."));
                        $this->autoUpdater->getServer()->forceShutdown();
                        sleep(1);
                        copy($this->autoUpdater->getDataFolder() . "/" . $phar, $this->autoUpdater->getServer()->getDataPath() . "/" . $phar);
						unlink($this->autoUpdater->getDataFolder() . "/" . $phar);
						shell_exec($this->autoUpdater->getStartScript());
                    } else{
                        $logger->info(TextFormat::colorize($prefix . "&cCan't update PocketMine, an error has occurred"));
                    }
                }
            }
        } else{
            $logger->info(TextFormat::colorize($prefix . "&cCan't update PocketMine, an error has occurred"));
        }
    }
}