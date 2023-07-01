<?php

declare(strict_types=1);

namespace AndreasHGK\SellAll;

use pocketmine\item\Item;
use pocketmine\utils\Config;
use pocketmine\player\Player;
use pocketmine\command\Command;
use pocketmine\utils\TextFormat;
use pocketmine\plugin\PluginBase;
use onebone\economyapi\EconomyAPI;
use pocketmine\command\CommandSender;
use pocketmine\scheduler\Task;
use jojoe77777\FormAPI\{SimpleForm, CustomForm};
use pocketmine\item\StringToItemParser;
use pocketmine\block\VanillaBlocks;
use pocketmine\item\VanillaItems;
use pocketmine\inventory\BaseInventory;

class SellAll extends PluginBase
{
    
    public $sell;
    public $plugin;
    
    private static SellAll $instance;

    public Config $messageConfig;
    public Config $settingConfig;

    public array $configValues;
    public array $messageValues;
    public array $settingValues;

    public function onLoad(): void
    {
        self::$instance = $this;
    }

    public function onEnable(): void
    {
        @mkdir($this->getDataFolder());
        $this->saveDefaultConfig();
        $this->configValues = $this->getConfig()->getAll();
        $this->saveResource("sell.yml");
        $this->sell = new Config($this->getDataFolder() . "sell.yml", Config::YAML, []);
        date_default_timezone_set('Asia/Ho_Chi_Minh');
    }

    public function onCommand(CommandSender $sender, Command $command, String $label, array $args): bool
    {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextFormat::colorize("&cPlease execute this command in-game"));
            return true;
        }

        switch ($command->getName()) {
            case "sell":
                if (isset($args[0])) {
                    switch (strtolower($args[0])) {
                        case "hand":
                            $item = $sender->getInventory()->getItemInHand();
                            $tong = 0;
                            if($item instanceof \pocketmine\item\ItemBlock){
                                if($this->sell->getNested((string)$item->getBlock()->getTypeId()) !== null && $this->sell->getNested((string)$item->getBlock()->getTypeId()) > 0){
                                    $price = $this->sell->getNested((string)$item->getBlock()->getTypeId()) * $item->getCount();
                                    EconomyAPI::getInstance()->addMoney($sender, $price);
                                    $tong += $price;
                                    $sender->getInventory()->remove($item);
                                }
                            }else{
                                if($this->sell->getNested((string)$item->getTypeId()) !== null && $this->sell->getNested((string)$item->getTypeId()) > 0){
                                    $price = $this->sell->getNested((string)$item->getTypeId()) * $item->getCount();
                                    EconomyAPI::getInstance()->addMoney($sender, $price);
                                    $tong += $price;
                                    $sender->getInventory()->remove($item);
                                }
                            }
                        $sender->sendMessage("§l§c•§e You Sold Items In Your Hand And Received ". $tong ." Money");
                        break;
                        case "all":
                           $items = $sender->getInventory()->getContents();
                            $tong = 0;
                            foreach($items as $item){
                            if($item instanceof \pocketmine\item\ItemBlock){
                                if($this->sell->getNested((string)$item->getBlock()->getTypeId()) !== null && $this->sell->getNested((string)$item->getBlock()->getTypeId()) > 0){
                                    $price = $this->sell->getNested((string)$item->getBlock()->getTypeId()) * $item->getCount();
                                    EconomyAPI::getInstance()->addMoney($sender, $price);
                                    $tong += $price;
                                    $sender->getInventory()->remove($item);
                                }
                            }else{
                                if($this->sell->getNested((string)$item->getTypeId()) !== null && $this->sell->getNested((string)$item->getTypeId()) > 0){
                                    $price = $this->sell->getNested((string)$item->getTypeId()) * $item->getCount();
                                    EconomyAPI::getInstance()->addMoney($sender, $price);
                                    $tong += $price;
                                    $sender->getInventory()->remove($item);
                                }
                            }
                        }
                        $sender->sendMessage("§l§c•§e You Successfully Sold All Items And Received ". $tong ." Money");
                            break;
                        }
                    }
                }
            return true;
            }
        }