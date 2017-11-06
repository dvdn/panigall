<?php

const GALLDIRNAME = 'medias';

/**
 * Get gall dirs tree
 * @return      array
 */
function getGallsDirTree(){
    $workdir = getcwd();
    $dir_path = $workdir.'/'.GALLDIRNAME;
    $exclude_list = array(".", "..");
    $directories = array_diff(scandir($dir_path), $exclude_list);
    return $directories;
}


/**
* viewDir
* @param string $dir to view
* @return string Html
*/
function viewDir($dir) {
    echo '<a href="'.GALLDIRNAME.'/'.$dir.'"/>'.$dir.'</a>';
}


/**
* Manage each dir
* @param array $dirs to manage
*/
function manageGallsDir(Array $dirs){
    foreach ($dirs as $dir) {
        viewDir($dir);
    }
}

manageGallsDir(getGallsDirTree());
