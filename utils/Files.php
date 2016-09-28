<?php

/**
 * Used for quick access to file and folder information
 */
class Files
{

    /**
     * Array of allowed file extenstions
     * 
     * @var array $allowedFiles
     */
    private $allowedFiles;

    function __construct()
    {
        $this->allowedFiles = unserialize(ALLOWED_FILES);
    }

    /**
     * Gets all files and folders in directory, and adds only the folder names 
     * to array to be returned. These names are to used as buttons in app for 
     * selecting which folder to pull images from. Hyphens and underscores are 
     * converted to spaces and folder names are put in title case.
     * 
     * @param string $dir
     * @return array
     */
    public function getFolderNames($dir)
    {
        // Get all files/folders in directory
        $results = array_diff(scandir($dir), array('.', '..'));
        $folders = array();
        // Add only folders to array
        foreach ($results as $result) {
            if (is_dir($dir . '/' . $result)) {
                // Convert hyphens and underscores to space
                $folderName = preg_replace('/(-|_)/', ' ', $result);
                // Convert string to title case
                $folderName = ucwords($folderName);
                // Add to array
                $folders[$result] = $folderName;
            }
        }
        // Sort folders alphabetically by folder name
        asort($folders);

        return $folders;
    }

    /**
     * Scans directory and checks for allowed file extensions in ALLOWED_FILES 
     * array. Any file with allowed extension is added to array to be returned.
     * 
     * @param string $folder
     * @return array
     */
    private function getFileNames($folder)
    {
        $files = scandir($folder);
        $fileNames = array();

        foreach ($files as $file) {
            $f = pathinfo($file);
            if (in_array($f['extension'], $this->allowedFiles)) {
                $fileNames[] = $file;
            }
        }

        return $fileNames;
    }

    /**
     * Requests filenames in /thumbs folder and returns them in an array.
     * 
     * Default is to return results in JSON format. Can set $format to false to 
     * return a normal array instead.
     * 
     * @param string $folder
     * @param string $format
     * @return array
     */
    public function getThumbnails($folder, $format = 'json')
    {
        $dir = IMAGES . '/' . $folder . '/thumbs/';

        $files = $this->getFileNames($dir);

        if ($format == 'json') {
            header('Content-Type: application/json');
            echo json_encode($files);
        }
        else {
            return $files;
        }
    }

}
