<?php

namespace emeraldmc;

use pocketmine\{Player, Server};
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;
use pocketmine\item\Item;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\event\Listener;
use pocketmine\command\{Command,CommandSender, CommandExecutor, ConsoleCommandSender};
use jojoe77777\FormAPI;
use onebone\economyapi\EconomyAPI;

class Main extends PluginBase implements Listener{
  
  const COMMAND_NAME = "enchantmerchant";
  const FORM_API = "FormAPI";
  const EconomyAPI = "EconomyAPI";
 public $prices = [
    "EXIT" => [0],
    "PROTECTION" => [1],
    "FIRE PROTECTION" => [2],
    "FEATHER FALLING" => [3],
    "BLAST PROTECTION" => [4],
	"PROJECTILE PROTECTION" => [5],
	"THORNS" => [6],
	"RESPIRATION" => [7],
	"DEPTH STRIDER " => [8],
    "AQUA AFFINITY" => [9],
	"SHARPNESS" => [10],
    "SMITE" => [],
    "BANE OF ARTHROPODS" => [11],
    "KNOCKBACK" => [],
	"FIRE_ASPECT" => [12],
	"LOOTING" => [13],
    "EFFICIENCY" => [14],
	"SILK TOUCH" => [15],
    "UNBREAKING" => [16],
    "FORTUNE" => [17],
    "POWER" => [18],
	"PUNCH" => [19],
	"FLAME" => [20],
	"INFINITY" => [21],
	"LUCK_OF_THE_SEA" => [22],
    "LURE" => [23],
	"FROST_WALKER" => [24],
    "MENDING" => [25]
  ];
  
  public $idss = [
    1 => ["PROTECTION"],
    2 => ["FIRE PROTECTION"],
    3 => ["FEATHER FALLING"],
    4 => ["BLAST PROTECTION"],
	5 => ["PROJECTILE PROTECTION"],
	6 => ["THORNS"],
	7 => ["RESPIRATION"],
	8 => ["DEPTH STRIDER"],
    9 => ["AQUA AFFINITY"],
	10 => ["SHARPNESS"],
    11 => ["SMITE"],
    12 => ["BANE OF ARTHROPODS"],
    13 => ["KNOCKBACK"],
	14 => ["FIRE_ASPECT"],
	15 => ["LOOTING"],
    16 => ["EFFICIENCY"],
	17 => ["SILK TOUCH"],
    18 => ["UNBREAKING"],
    19 => ["FORTUNE"],
    20 => ["POWER"],
	21 => ["PUNCH"],
	22 => ["FLAME"],
	23 => ["INFINITY"],
	24 => ["LUCK_OF_THE_SEA"],
    25 => ["LURE"],
	26 => ["FROST_WALKER"],
    27 => ["MENDING"]
  ];
  public function onEnable(){
        $this->getLogger()->info("[Merchant] Enabled Enchanter!");
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
   public function onCommand(CommandSender $sender, Command $cmd, string $label, array $args): bool{
        if(strtolower($cmd->getName()) === self::COMMAND_NAME){
	if(!$sender instanceof Player){
		$sender->sendMessage("Please use this command in game!");
		return true;
	}
	$this->EnchantForm($sender);
        return true;
    }
   }
  public function EnchantForm($player){
        $plugin = $this->getServer()->getPluginManager();
	$economyapi = $plugin->getPlugin(self::EconomyAPI);
        $formapi = $plugin->getPlugin(self::FORM_API);
        $form = $formapi->createSimpleForm(function (Player $player, $data){
            $result = $data;
            if($result === null){
				$this->ShopForm($player, $result);
            }
        });
	  foreach($this->prices as $name => $price){
         $form->addButton($name);
        $form->sendToPlayer($player);
  }
  }
  public function ShopForm($player, $id){
	  $array = $this->idss;
	  $eapi = $this->getServer()->getPluginManager()->getPlugin(self::EconomyAPI);
	  $api = $this->getServer()->getPluginManager()->getPlugin(self::FORM_API);
        $form = $api->createCustomForm(function (Player $player, $data) use ($id, $array){
			  $item = $player->getInventory()->getItemInHand();
                EconomyAPI::getInstance()->reduceMoney($player->getName(), $price, true);
	        $player->sendMessage("§3(§b!§3) §7You have been charged §a$price §7and got a enchant!");
                $ench = Enchantment::getEnchantmentByName(strtolower($array[$id][0]));
                $item->addEnchantment(new EnchantmentInstance($ench, (int) $data[0]));
		$player->getInventory()->setItemInHand($item);
	        $player->sendMessage("§3(§b!§3) §7Enchanted Succesfully!");
	       
         });
       $form->setTitle("§l§3ENCHANT MERCHANT");
       $form->addSlider("Level", 1, 10, 1, -1);
       $form->sendToPlayer($player);
	  
  }
}
