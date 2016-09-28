$(document).ready(function () {

    var $folders = $('#folders');
    var $files = $('#files');
    var $filesBox = $('#filesBox');
    var $layers = $('#layers');
    var $builderBox = $('#builderBox');
    var $exportFile = $('#exportFile');
    var $clearLayers = $('#clearLayers');

    // Sortable UI settings
    $layers.sortable({
        opacity: 0.5,
        update: updateLayers
    });

    // Clicked Folder
    $folders.on('click', 'li', function () {
        // Get folder name
        var folder = $(this).attr('data-folder');
        // Get small files from this folder for thumbnails
        getFiles(folder, 'sm');
        // Update currently selected folder for files list
        $files.attr('data-folder', folder);
        // Un-highlight previous selected
        setUnselected($(this).closest('ul').find('li.selected'));
        // Highlight border
        setSelected($(this));
    });

    // Clicked File
    $files.on('click', 'li', function () {
        // Get current folderName on ul class
        var folder = $files.attr('data-folder');
        // Get currently clicked file name
        var file = $(this).attr('data-file');
        // Get image in li
        var $img = $(this).find('img');

        var isSelected = $img.hasClass('selected');

        if (!isSelected) {
            // If image not yet clicked, add layer
            addLayer(folder, file);
            // Highlight border
            setSelected($img);
        }
        else {
            // If image already clicked
            removeLayer(folder, file);
            // Un-highlight border
            setUnselected($img);
        }
    });

    // Double Clicked Layer
    $layers.on('dblclick', 'li', function () {
        var folder = $(this).attr('data-folder');
        var file = $(this).attr('data-file');
        // Remove layer
        removeLayer(folder, file);
        // Un-highlight border for corresponding selected file thumbnail
        setUnselected($filesBox.find('ul[data-folder="' + folder + '"] li[data-file="' + file + '"] img'));
    });

    // Clear layers
    $clearLayers.on('click', function () {
        if ($layers.find('li').length) {
            // Remove all layers from layers list
            $layers.find('li').remove();
            // Remove all image layers from builder box
            $builderBox.find('img').remove();
            // Set all selected files images to unselected
            $files.find('li img.selected').each(function() {
                setUnselected($(this));
            });
        }
        else {
            alert('There are no layers to clear');
        }
    });

    // Export file
    $exportFile.on('click', function () {

        var filePaths = [];

        $layers.find('li').each(function (i) {
            var folder = $(this).attr('data-folder');
            var file = $(this).attr('data-file');
            var filePath = folder + '/' + file;

            filePaths[i] = filePath;
        });

        if (filePaths.length) {
            // Create filePath parameters
            var urlParams = $.param({'filePaths': filePaths});
            // Download file
            window.location = 'createPng.php?' + urlParams;
        }
        else {
            alert('You must have layers selected to export a file');
        }
    });

    // Highlight border
    function setSelected($element) {
        $element.removeClass('unselected').addClass('selected');
    }

    // Un-highlight border
    function setUnselected($element) {
        $element.removeClass('selected').addClass('unselected');
    }

    /**
     * Add layer
     * 
     * Adds file to layers and updates image layers in builder box.
     * 
     * @param {string} folder
     * @param {string} file
     * @returns {undefined}
     */
    function addLayer(folder, file) {
        // Check that file is not already in Layers
        if (notInLayers(folder, file)) {
            // Prepend new layer li to layers
            $layers.prepend('<li data-folder="' + folder + '" data-file="' + file + '"><img class="unselected" src="images/' + folder + '/thumbs/' + file + '" /></li>');
            // Update layers
            updateLayers();
        }
    }

    /**
     * Remove layer
     * 
     * Removes layer with specified folder & file data.
     * Updates image layers in builder box if layer removed.
     * 
     * @param {string} folder
     * @param {string} file
     * @returns {undefined}
     */
    function removeLayer(folder, file) {
        // Find layer to be removed
        var $layer = $layers.find('li[data-folder="' + folder + '"][data-file="' + file + '"]');
        // If layer was found
        if ($layer.length) {
            // Remove layer
            $layer.remove();
            // Update image layers in builder box
            updateLayers();
        }
    }

    /**
     * Update layers
     * 
     * Updates image layers in builder box using z-index.
     * 
     * @returns {undefined}
     */
    function updateLayers() {
        // Get total layer count
        var totalLayers = $layers.find('li').length;
        // Create html var to append html <img>'s to for display in builder box
        var html = '';
        $layers.find('li').each(function (i) {
            // Create new image layer for display in builder box
            html += '<img src="images/' + $(this).attr('data-folder') + '/' + $(this).attr('data-file') + '" style="z-index: ' + (totalLayers - i) + ';" />';
        });
        // Update html in builderBox
        $builderBox.html(html);
    }

    /**
     * Update thumbnails
     * 
     * Updates thumbnails for files present in specified folder. Files list is
     * populated with new thumbnails.
     * 
     * @param {string} folder
     * @param {array} files
     * @returns {undefined}
     */
    function updateThumbs(folder, files) {
        // Build list of files to HTML
        var html = '';
        $.each(files, function (i, file) {
            html += '<li data-file="' + file + '">';
            // Check if file present in layers array to set class as "selected"
            html += '<img class="' + (notInLayers(folder, file) ? "unselected" : "selected") + '" src="images/' + folder + '/thumbs/' + file + '" />';
            html += '</li>';
        });
        // Update files list
        $files.html(html);
    }

    /**
     * Checks if file with specified folder and filename is present in layers
     * 
     * @param {string} folder
     * @param {sting} file
     * @returns {Boolean}
     */
    function notInLayers(folder, file) {

        var notInLayers = true;

        /*
         * Loop through each layer and check if folder & file name are found. 
         * If found, set notInLayers to 'false' and break out of loop.
         */
        $layers.find('li').each(function () {
            if ($(this).attr('data-folder') === folder && $(this).attr('data-file') === file) {
                // File already in Layers. Set to false.
                notInLayers = false;
                // Break .each loop
                return false;
            }
        });

        return notInLayers;
    }

    /**
     * Loads all the thumbnail images from selected folder
     * 
     * @param {string} folder
     * @returns {undefined}
     */
    function getFiles(folder) {

        $.ajax({
            type: 'GET',
            url: 'getFiles.php?folder=' + folder,
            success: function (files) {
                if (files.error) {
                    console.log(files.error);
                }
                else {
                    updateThumbs(folder, files);
                }
            }
        });

    }

});
