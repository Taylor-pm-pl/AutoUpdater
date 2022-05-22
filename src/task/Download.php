<?php

namespace davidglitch04\AutoUpdater\task;

use pocketmine\scheduler\AsyncTask;
use pocketmine\utils\Internet;

class Download extends AsyncTask
{
	private string $url;
    
    private string $dest;

    public function __construct($url, $dest){
        $this->url = $url;
        $this->dest = $dest;
    }

    public function onRun() : void
    {
        $raw = Internet::getURL($this->url)->getBody();
		file_put_contents($this->dest, $raw);
    }
}