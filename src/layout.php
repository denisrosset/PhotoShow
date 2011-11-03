<?php
/*
    This file is part of PhotoShow.

    PhotoShow is free software: you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation, either version 3 of the License, or
    (at your option) any later version.

    PhotoShow is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with PhotoShow.  If not, see <http://www.gnu.org/licenses/>.
*/

require_once 'src/settings.php';
require_once 'src/listings.php';
require_once 'src/images.php';

/**
 * Creates the main menu
 * 
 * \param string $dir
 * 		Main directory for the photos.
 * \param string $selected_dir
 *		Currently selected dir in the interface
 * \param string $selected_subdir
 *		Currently selected subdir in the interface
 */
function menu($selected_dir=".",$selected_subdir="."){
	$settings=get_settings();
	$dirlist = list_dirs($settings['photos_dir'],true);
	foreach ( $dirlist as $dir )
	{
		// Adding the 'selected' class to selected dir
		$class="menu_dir";
		$is_selected=true;
		if(same_path($dir,$selected_dir))
			$class = $class . " selected";
		else
			$is_selected=false;
		
		// Creating the item
		echo 	"<div class='menu_item'>\n";
		echo 	"<div class='$class'>";
		echo 	"<a href='?f=";
		echo 	relative_path($dir,$settings['photos_dir']);
		echo 	"'>";
		echo 	basename($dir);
		echo 	"</a></div>\n";
		
		// Listing directories contained in the item
		$subdirlist = list_dirs($dir,true);
		
		echo "<div class='$subdirclass'";
		if(!$is_selected)
			echo " style='display:none'; ";
		echo ">\n";
		
		foreach ( $subdirlist as $subdir ) 
		{
			// Adding the 'selected' class to selected subdir
			$class="menu_subdir";
			if(same_path($subdir,$selected_subdir))
				$class = $class . " selected";
			
			// Creating the item
			echo "<div class='$class'>";
			echo "<a href='?f=";
			echo relative_path($subdir,$settings['photos_dir']);
			echo "'>";
			echo basename($subdir);
			echo "</a></div>\n";	
		}
		echo "</div>\n";
		echo "</div>\n";
	}
}

/**
 * Creates a board, where thumbs are displayed
 * 
 * \param string $dir
 * 		Directory where to look
 */
function board($dir){
	$filelist	=	list_files($dir,true);
	$dirlist	=	list_dirs($dir,true);
	$settings	=	get_settings();
	
	echo 	"<div class='board'>\n";
	echo 	"<div class='board_title'>";
	echo 	basename($dir);
	echo 	"</div>\n";
	
	// First, we display the thumbs
	foreach ( $filelist as $file ){
	//	$thumb = get_thumb($file);
		echo 	"<div class='board_item'>";
		echo 	"<a href='?f=";
		echo 	relative_path($file,$settings['photos_dir']);
		echo 	"'>";
		echo 	"<img src='src/getfile.php?t=thumb&file=";
		echo 	relative_path($file,$settings['photos_dir']);
		echo 	"'>";
		echo 	"</a></div>\n";
	}

	// Then, we display the sub-boards
	foreach ( $dirlist as $subdir ){
		echo 	"<div class='sub_board'>";
		echo 	"<a href='?f=";
		echo 	relative_path($subdir,$settings['photos_dir']);
		echo 	"'>";
		echo 	basename($subdir);
		echo 	"</a></div>\n";
	}
	
	echo 	"</div>\n";
}

?>