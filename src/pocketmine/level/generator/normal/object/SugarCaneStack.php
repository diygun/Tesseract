<?php

/*
 *
 *    _______                                _
 *   |__   __|                              | |
 *      | | ___  ___ ___  ___ _ __ __ _  ___| |_
 *      | |/ _ \/ __/ __|/ _ \  __/ _` |/ __| __|
 *      | |  __/\__ \__ \  __/ | | (_| | (__| |_
 *      |_|\___||___/___/\___|_|  \__,_|\___|\__|
 *
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Lesser General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * @author Tessetact Team
 * @link http://www.github.com/TesseractTeam/Tesseract
 * 
 *
 */

 
namespace pocketmine\level\generator\normal\object;

use pocketmine\block\Block;
use pocketmine\level\ChunkManager;
use pocketmine\level\generator\object\Object;
use pocketmine\utils\Random;

class SugarCaneStack extends Object{
	
	/** @var Random */
	private $random;
	private $baseHeight = 2;
	private $randomHeight = 4;
	private $totalHeight;
	
	public function __construct(Random $random){
		$this->random = $random;
		$this->randomize();
	}
	
	public function randomize(){
		$this->totalHeight = $this->baseHeight + $this->random->nextBoundedInt($this->randomHeight);
	}
	
	private function isWater(int $id) : bool{
		if($id == Block::WATER or $id == Block::STILL_WATER){
			return true;
		}
		return false;
	}
	
	public function canPlaceObject(ChunkManager $level, int $x, int $y, int $z) : bool{
		$below = $level->getBlockIdAt($x, $y - 1, $z);
		if($level->getBlockIdAt($x, $y, $z) == Block::AIR and
			($below == Block::DIRT or $below == Block::GRASS or $below == Block::SAND) and (
				$this->isWater($level->getBlockIdAt($x - 1, $y - 1 , $z)) or
				$this->isWater($level->getBlockIdAt($x + 1, $y - 1, $z)) or
				$this->isWater($level->getBlockIdAt($x, $y - 1, $z - 1)) or
				$this->isWater($level->getBlockIdAt($x, $y - 1, $z + 1))
			)
		){
			return true;
		}
		return false;
	}
	
	public function placeObject(ChunkManager $level, int $x, int $y, int $z){
		for($yy = 0; $yy < $this->totalHeight; $yy++){
			if($level->getBlockIdAt($x, $y + $yy, $z) != Block::AIR){
				return;
			}
			$level->setBlockIdAt($x, $y + $yy, $z, Block::SUGARCANE_BLOCK);
		}
	}
}