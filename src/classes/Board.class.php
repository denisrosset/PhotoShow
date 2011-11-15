<?php
/**
 * This file implements the class Board.
 * 
 *
 * PHP versions 4 and 5
 *
 * LICENSE:
 * 
 * This file is part of PhotoShow.
 *
 * PhotoShow is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * PhotoShow is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.	 See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with PhotoShow.  If not, see <http://www.gnu.org/licenses/>.
 *
 * @package	   PhotoShow
 * @author	   Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright  2011 Thibaud Rohmer
 * @license	   http://www.gnu.org/licenses/
 * @link	   http://github.com/thibaud-rohmer/PhotoShow-v2
 */

/**
 * Board
 *
 * Lists the content of a directory and displays
 * it on a grid.
 * It implements a grid generating algorithm, and
 * outputs its content in a div of class board
 * via the toHTML() function.
 *
 * @package	   PhotoShow
 * @author	   Thibaud Rohmer <thibaud.rohmer@gmail.com>
 * @copyright  Thibaud Rohmer
 * @license	   http://www.gnu.org/licenses/
 * @link	   http://github.com/thibaud-rohmer/PhotoShow-v2
 */
class Board
{
	/// Board title : name of the directory listed
	private $title;
	
	/// Path to listed directory
	private $path;
	
	/// Paths to the files in the directory
	private $files;
	
	/// Paths to the directories in the directory
	private $dirs;
	
	/// Board header, containing the title and some buttons
	private $header;
	
	/// Array of each line of the grid
	private $boardlines=array();


	/**
	 * Board constructor
	 *
	 * @param string $path 
	 * @author Thibaud Rohmer
	 */
	public function __construct($path){
		$this->analyzed=array();
		$this->path=$path;

		// If $path is a file, list directory containing the file
		if(is_file($path)){
			$this->path		=	dirname($path);
		}
		
		$this->title	=	basename($this->path);
		$this->header	=	new BoardHeader($this->title);
		$this->files	=	Menu::list_files($this->path);
		$this->dirs		=	Menu::list_dirs($this->path);
		
		// Generate the grid
		$this->grid();
	}
	
	/**
	 * Display board on website
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	public function toHTML(){
		echo "<div class='board'>";
		
		// Output header
		$this->header->toHTML();
		
		// Output grid
		foreach($this->boardlines as $boardline)
			$boardline->toHTML();

		echo "</div>";
	}
	
	/**
	 * Generate the grid, line by line
	 *
	 * @return void
	 * @author Thibaud Rohmer
	 */
	private function grid(){
		// Create line
		$bl =	new BoardLine();
		
		foreach($this->files as $file){
			// Check rights
			if(!(Judge::view($file)))
				continue;

			// Calculate file ratio
			$ratio	=	$this->ratio($file);
			
			// Create new line when sum 
			// of ratios reaches 11
			if($bl->ratio + $ratio > 11){
				$bl->end_line();
				$this->boardlines[] = $bl;
				$bl =	new BoardLine();
			}
			
			// Add item to the line
			$bl->add_item($file,$ratio);
		}
	}

	/**
	 * Calculate item ratio.
	 * - Image -> floor(width/height) + 1
	 * - Autre -> 2
	 *
	 * @param string $file 
	 * @return void
	 * @author Thibaud Rohmer
	 */
	private function ratio($file){
		// Non-image file : ratio = 2
		if(File::type($file) != "Image")
			return 2;
		
		// Calculate ratio
		list($x,$y) = getimagesize($file);
		
		return floor($x/$y)+1;

	}
}