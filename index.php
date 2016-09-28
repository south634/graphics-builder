<?php
require_once 'config.php';
require_once UTILS . '/Files.php';

$files = new Files();
?>
<!DOCTYPE html>
<html>
    <head>
        <title>Graphics Builder</title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>

        <div class="container">

            <h1>Graphics Builder</h1>

            <div id="builderContainer">
                <div id="builderBox">
                </div>
            </div>

            <div id="settingsContainer">

                <div id="buttonsContainer">
                    <div id="controlsBox">Controls:
                        <ul id="controls" class="buttons">
                            <li id="exportFile">Export File</li>
                        </ul>
                    </div> 

                    <div id="foldersBox">Folders:
                        <ul id="folders">
                            <?php
                            // Get all folder names
                            $folders = $files->getFolderNames(IMAGES);
                            
                            // Set default folder name as current key in array
                            $currentFolder = key($folders);

                            // Output all folder names in title case
                            foreach ($folders as $folder => $folderName) {
                                // If current folder, set class to 'selected'
                                $class = ($folder == $currentFolder) ? 'selected' : 'unselected';
                                echo "<li class='$class' data-folder='$folder'>$folderName</li>";
                            }
                            ?>
                        </ul>
                    </div>                
                </div>

                <div id="imagesContainer">
                    <div id="filesBox">Files:
                        <ul id="files" data-folder="<?php echo $currentFolder; ?>">
                            <?php
                            // Get all images in current folder
                            $thumbnails = $files->getThumbnails($currentFolder, false);

                            // Output all files in current folder
                            foreach ($thumbnails as $thumbnail) {
                                echo "<li data-file='$thumbnail'>
                                <img class='unselected' src='images/$currentFolder/thumbs/$thumbnail'>
                                </li>";
                            }
                            ?>
                        </ul>
                    </div>

                    <div id="layersBox">Layers: <span id="clearLayers">Clear Layers</span>
                        <ul id="layers">
                        </ul>
                    </div> 
                </div>

            </div>

        </div>

        <script src="js/libs/jquery/jquery.min.js"></script>
        <script src="js/libs/jquery-ui/jquery-ui.min.js"></script>
        <script src="js/builder.js"></script>
    </body>
</html>
