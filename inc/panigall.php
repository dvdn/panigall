<?php
include_once('config.php');

if (!isset(get_defined_constants()["HOMEDIR"]) OR scandir(HOMEDIR) == false) {
    echo('<p style="color:red;">error : HOMEDIR config is required or invalid</p>');
    return;
}

// Homepage display, desired path or root redirection
if (!isset($_GET["d"])) {
    define('DIR', '/'.get_defined_constants()["HOMEDIR"]);
} elseif (scandir('.'.$_GET["d"]) == false) {
    echo('<p style="color:red;">error : '.$_GET["d"].' directory doesn\'t exist</p>');
    header("Refresh:2; url=.");
    return;
} elseif ($_GET["d"] == "/"){
    header("Refresh:0; url=.");
} else {
    define('DIR', $_GET["d"]);
}

/**
 * View tree
 *
 * @param string $dir name
 * @return string Html
 */
function viewTree($dir)
{
    $dirForNav = str_replace('/'.get_defined_constants()["HOMEDIR"], "", $dir); // Don't display HOMEDIR

    viewNav($dirForNav);
    echo '<div id="explorer">';
    foreach (getContentTree($dir) as $item) {
        view($item);
    }
    echo '</div>';
}

/**
 * Get dir tree
 *
 * @param string $dir name
 * @return array
 */
function getContentTree($dir)
{
    $path = getcwd().$dir;
    $exclude_list = array(".", "..");
    $items = array_diff(scandir($path), $exclude_list);
    // exclude hidden files
    //$items = array_filter($items, create_function('$a', 'return $a[0]!=".";')); deprecated
    $items = array_filter($items, function($a){return $a[0] !== ".";});
    return $items;
}

/**
* View item
*
* @param string $item filename
* @return string Html
*/
function view($item)
{
    $linkItem = DIR.'/'.$item;
    $pathItem = getcwd().$linkItem;

    if (is_dir($pathItem)) {
        echo '<a class="item" href="?d='.$linkItem.'" title="open folder"/>'.ICON_FOLDER.'<span>'.$item.'</span></a>';
    } else {
        $link = '.'.$linkItem;
        $thumb = false;
        // if image view thumb
        if (is_array(getimagesize($pathItem))) {
            $thumb = getThumb($pathItem, $item);
        }
        $icon = ($thumb==false) ? ICON_BASICFILE : $thumb;
        $modalClass = ($thumb==false) ? '' : 'js-modal-item';
        echo '<a class="item '.$modalClass.'" href="'.$link.'" target="_blank" title="open image"/>'.$icon.'<span>'.$item.'</span></a>';
    }
}

/**
* getThumb
*
* @param string $filePath filePath
* @param string $fileName fileName
* @return string Html
*/
function getThumb($filePath, $fileName)
{
    // exif_thumbnail is bugged :/
    $imageThumb = @exif_thumbnail($filePath, $width, $height, $type);
    if ($imageThumb !== false) {
        return "<img src='data:image/gif;base64,".base64_encode($imageThumb)."'>";
    } else {
        $fileCachePath = './cache'.DIR.'/'.$fileName;
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
function generateThumb($filePath, $fileName)
{
    // @ as exif_read_data is bugged :/
    $exif = @exif_read_data($filePath);
    // Image to process
    $ext = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
    switch($ext) {
        case 'jpg':
        case 'jpeg':
            $image = imagecreatefromjpeg($filePath);
            break;
        default:
            if (in_array($ext, ALLOWED_TYPES)) {
                $imgFunctionName = 'imagecreatefrom' . $ext;
                $image = $imgFunctionName($filePath);
            } else {
                $image = imagecreate(24, 24);
            }
    }

    $image = imgManageRotation($image, $exif);
    $imgSizes = imgManageSize($image);
    // Create empty image then fill it
    $img = imagecreatetruecolor($imgSizes['widthTh'], $imgSizes['heightTh']);
    imagecopyresampled($img, $image, 0, 0, 0, 0, $imgSizes['widthTh'], $imgSizes['heightTh'], $imgSizes['width'], $imgSizes['height']);
    // destroy old image
    imagedestroy($image);

    $cacheDir = 'cache'.DIR.'/';
    if (!file_exists($cacheDir)) {
        mkdir($cacheDir, 0777, true);
    }
    $imgCachePath = $cacheDir.$fileName;

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
function imgManageRotation($image, $exif)
{
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
function imgManageSize($image, $widthTh = W_THUMBS, $heightTh = H_THUMBS)
{
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
function viewNav($dirPathRel)
{
    $navDirs = controlNav($dirPathRel);
    echo '<nav id="nav">';
    foreach ($navDirs as $navDir) {
        echo viewNavItem($navDir);
    }
    echo '</nav><hr>';
}

/**
 * Manage breadcrumb
 *
 * @param string $dirPathRel name
 * @return  array $dirs crumbs
 */
function controlNav($dirPathRel)
{
    $ariane[]=$dirPathRel;
    if ($dirPathRel !== '') {
        $treeElmnts = explode('/', $dirPathRel);
        $tmpToPop = $treeElmnts;
        $i=1;
        while ($i < count($treeElmnts)) {
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
    if ($item !== ''){
        $path = explode('/', $item);
        echo '<a href="?d='.$item.'" title="to folder '.end($path).'"/> / '.end($path).'</a>';
    } else {
        echo '<a href="." title="back home"/><img src='.ICON_HOME.'></a>';
    }
}

/*execution*/
viewTree(DIR);
