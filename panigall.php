<?php

const ICONFILE = '<svg width="96" height="96"><rect width="64" height="80" x="16" y="8" style="fill:none;stroke-width:4;stroke:#72a7cf" /></svg>';
const ICONFOLDER = '<svg xmlns="http://www.w3.org/2000/svg" version="1.1" viewBox="0 0 256 256" width="96" height="96">
<path fill="#72a7cf" d="m7.2929 34.894c-3.87 0-6.9999 3.134-6.9999 6.9999v50.333c-0.10991-0.0115-0.17477-0.0141-0.29296-0.0273v124.4c0.001959 0.04 0.003959 0.07 0.005959 0.11 0.002 0.0901 0.006 0.18164 0.0137 0.27147 0.005 0.0601 0.0118 0.11983 0.0195 0.17969 0.01 0.0803 0.0213 0.16056 0.0352 0.24023 0.0138 0.0737 0.0294 0.14582 0.0469 0.21875 0.017 0.0738 0.0359 0.14786 0.0566 0.22071 0.0198 0.0705 0.0413 0.14145 0.0644 0.21092 0.0254 0.074 0.0528 0.14614 0.082 0.21875 0.0259 0.064 0.0532 0.12676 0.082 0.18946 0.032 0.0709 0.0659 0.14182 0.10157 0.21093 0.033 0.0641 0.0675 0.12697 0.10351 0.18945 0.0422 0.0746 0.0865 0.14865 0.13281 0.2207 0.0341 0.0507 0.0693 0.10111 0.10547 0.15039 0.0502 0.0681 0.10228 0.13404 0.15625 0.19922 0.0417 0.0543 0.0847 0.10781 0.1289 0.16017 0.0543 0.0615 0.11037 0.12122 0.16797 0.17966 0.0471 0.0477 0.0953 0.095 0.14453 0.14063 0.0628 0.0585 0.12732 0.11511 0.19336 0.16992 0.0487 0.0377 0.0982 0.0738 0.14843 0.10938 0.0663 0.0519 0.13403 0.1023 0.20312 0.15039 0.0608 0.0415 0.12274 0.0806 0.18555 0.11914 0.0648 0.0383 0.13058 0.0763 0.19726 0.11133 0.0643 0.0349 0.12941 0.0679 0.19531 0.0996 0.0702 0.0318 0.14116 0.0617 0.21289 0.0899 0.0613 0.0247 0.12318 0.0483 0.18555 0.0703 0.0824 0.0325 0.16579 0.0623 0.24999 0.0898 0.0634 0.0181 0.12725 0.0355 0.19141 0.0508 0.0744 0.0186 0.14933 0.0341 0.2246 0.0488 0.0746 0.0119 0.1495 0.0231 0.22461 0.0312 0.0785 0.0121 0.15731 0.0214 0.23633 0.0293 0.0825 0.009 0.16517 0.0151 0.24804 0.0195 0.052 0.004 0.10413 0.008 0.15625 0.01h209.62 1.6308c1.988-0.00063 3.7405-1.3045 4.3105-3.209l35.75-119.18c0.86486-2.8867-1.297-5.7922-4.3105-5.793h-32.607v-34.923c0-0.72487-0.11013-1.4244-0.31446-2.082-0.00018-0.00059 0.00019-0.001 0-0.002-0.16117-1.7446-1.3018-3.1952-2.8808-3.789-1.0953-0.71072-2.4002-1.1269-3.8046-1.1269h-119.56v-9.1073c0-3.866-3.13-6.9999-6.9999-6.9999h-6.0409-65.958-6.0409zm6.9999 14.002h6.041 51.958 6.041v2.6816 13.566h14v-0.14062h112.56v27.918h-82.579c-1.2274 0.00043-2.4014 0.50185-3.25 1.3887l-23.857 24.914h-57.39c-1.8997-0.0005-3.5964 1.1905-4.2382 2.9785l-19.283 53.773v-127.08z"/>
</svg>';

// Thumbnails size and quality
Const W_THUMBS = 160;
Const H_THUMBS = 128;
Const Q_THUMBS = 32;

// Default home folder
Const HOME = '.';
// If you want to start in 'myGalleries' folder for example :
// Const HOME ='?d=/myGalleries';

define('DIR', (isset($_GET["d"])) ? $_GET["d"] : '');

/**
 * View tree
 *
 * @param string $dir name
 * @return string Html
 */
function viewTree($dir) {
    viewNav($dir);
    echo '<div id="explorer">';
    foreach (getContentTree($dir) as $item) {
            view($item);
    }
    echo '</div>';
    viewFooter();
}

/**
 * Get dir tree
 *
 * @param string $dir name
 * @return array
 */
function getContentTree($dir){
    $path = getcwd().$dir;
    $exclude_list = array(".", "..");
    $items = array_diff(scandir($path), $exclude_list);
    // exclude hidden files
    $items = array_filter($items, create_function('$a','return ($a[0]!=".");'));
    return ($items);
}

/**
* View item
*
* @param string $item filename
* @return string Html
*/
function view($item) {
    $linkItem = DIR.'/'.$item;
    $pathItem = getcwd().$linkItem;

    if (is_dir($pathItem)) {
        echo '<a class="item" href="?d='.$linkItem.'"/>'.ICONFOLDER.'<span>'.$item.'</span></a>';
    } else {
        $link = './'.$linkItem;
        $thumb = false;
        // if image view thumb
        if(is_array(getimagesize($pathItem))) {
            $thumb = getThumb($pathItem, $item);
        }
        $icon = ($thumb==false) ? ICONFILE : $thumb;
        echo '<a class="item" href="'.$link.'" target="_blank" />'.$icon.'<span>'.$item.'</span></a>';
    }
}

/**
* getThumb
*
* @param string $filePath filePath
* @param string $fileName fileName
* @return string Html
*/
function getThumb($filePath, $fileName) {
    // exif_thumbnail is bugged :/
    $imageThumb = @exif_thumbnail($filePath, $width, $height, $type);
    if ($imageThumb!== false) {
        return "<img src='data:image/gif;base64,".base64_encode($imageThumb)."'>";
    } else {
        $fileCachePath = './cache/'.$fileName;
        if (!file_exists($fileCachePath)) {
            generateThumb($filePath, $fileName);
        }
        return "<img src='".$fileCachePath."'>";
        
    }
}

/**
 * generateThumb
 *
 * @param string $filePath filePath
 * @param string $fileName fileName
 * @return jpeg thumb
 */

function generateThumb($filePath, $fileName) {
    // @ as exif_read_data is bugged :/
    $exif = @exif_read_data($filePath);
    // Image to process
    if (strpos(strtolower($filePath),".png")) { 
        $image = imagecreatefrompng($filePath); // PNG
    } else {
        $image = imagecreatefromjpeg($filePath); // JPG
    }
    $image = imgManageRotation($image, $exif);
    $imgSizes = imgManageSize($image);
    // Create empty image then fill it
    $img = imagecreatetruecolor($imgSizes['widthTh'], $imgSizes['heightTh']);
    imagecopyresampled($img, $image, 0, 0, 0, 0, $imgSizes['widthTh'], $imgSizes['heightTh'], $imgSizes['width'], $imgSizes['height']);
    // destroy old image 
    imagedestroy($image);
    $imgCachePath = 'cache/'.$fileName;

    // jpeg output
    return imagejpeg($img, $imgCachePath, Q_THUMBS);
}

/**
 * imgManageRotation
 *
 * @param $image
 * @param $exif exif infos
 * @return $image
 */

function imgManageRotation($image, $exif) {
    if (!empty($exif['Orientation'])) {
        switch ($exif['Orientation']) {
            case 3:
                $image = imagerotate($image, 180, 0);
            break;
            case 6:
                $image = imagerotate($image, -90, 0);
            break;
            case 8:
                $image = imagerotate($image, 90, 0);
            break;
        }
    }
    return $image;
}

/**
 * imgManageRotation
 *
 * @param $image
 * @param int $widthTh thumb width
 * @param int $heightTh thumb height
 * @return array image sizes
 */

function imgManageSize($image, $widthTh = W_THUMBS, $heightTh= H_THUMBS) {
    $width = imagesx($image);
    $height = imagesy($image);
    $ratio = $width/$height;
    if ($widthTh/$heightTh > $ratio) {
        $widthTh = $heightTh*$ratio;
    } else {
        $heightTh = $widthTh/$ratio;
    }
    return ['width'     => $width,
            'height'    => $height,
            'widthTh'   => (int)$widthTh,
            'heightTh'  => (int)$heightTh];
}

/**
 * Navigation
 *
 * @param string $dirPathRel name
 * @return string Html
 */
function viewNav($dirPathRel) {
    $navDirs = controlNav($dirPathRel);
    echo '<nav id="nav">';
    foreach ($navDirs as $navDir) {
       viewNavItem($navDir);
    }
    echo '</nav><hr>';
}

/**
 * Manage breadcrumb
 *
 * @param string $dirPathRel name
 * @return  array $dirs crumbs
 */
function controlNav($dirPathRel) {
    $ariane[]=$dirPathRel;
    if ($dirPathRel!=='') {
        $treeElmnts = explode('/', $dirPathRel);
        $tmpToPop = $treeElmnts;
        $i=1;
        while( $i < count($treeElmnts)) {
            array_pop($tmpToPop);
            $parentdir = implode('/', $tmpToPop);
            $ariane[]=$parentdir;
            $i++;
        }
        # breadcrumb reorder
        $ariane = array_reverse($ariane);
    }
    return $ariane;
}

/**
 * Display navigation item
 *
 * @param string directory name
 * @return string Html
 */
function viewNavItem($item) {
    if ($item!==''){
        $path = explode('/', $item);
        echo '<a href="?d='.$item.'"/> / '.end($path).'</a>';
    } else {
        echo '<a href="'.HOME.'"/>home</a>';
    }
}

function viewFooter(){
    echo '<footer><hr>2018 . source code <a href="https://github.com/dvdn/panigall" target="_blank"/>dvdn/panigall</a></footer>';
}

/*execution*/
viewTree(DIR);
