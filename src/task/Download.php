<?php

namespace davidglitch04\AutoUpdater\task;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;

class Download extends AsyncTask
{

    public function __construct(
        protected string $url,
        protected string $dest
    ){}

    public function onRun() : void
    {
        $raw = Internet::getURL($this->url)->getBody();
        $this->setResult($raw);
    }

    public function onCompletion(): void
    {
        $result = $this->getResult();
		file_put_contents($this->dest, $result);
    }
}