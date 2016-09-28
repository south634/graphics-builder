# Graphics Builder
A simple web app for layering transparent images over each other, and exporting them as a single file. I coded this as a proof of concept for a designer selling custom graphics for creating YouTube video thumbnails. I was asked if it would be possible to code an application which customers could access online, that would allow them to layer transparent images from various category folders for export as a PNG file.

This is what I came up with, and as the code never went live, I have decided to share it here for anyone who might find it useful.

## Settings
In config.php you can set your image folder location ('IMAGES'), as well as the output image filename ('OUTPUT_FILENAME'), height, and width. The 'ALLOWED_FILES' array is where you can set which file extensions you want to scan for in your image folders.

The thumbnail for each graphic should be stored in the /thumbs folder in that graphic's folder, and have the same file name.

For example, if the main graphic is here:

/images/circle/blue.png

Then its corresponding thumbnail image should be here:

/images/circle/thumbs/blue.png

I've included some basic transparent images in the /images folder for example purposes.

## How it works
After accessing /index.php, the app retrieves every folder in the 'IMAGES' folder set in /config.php, and creates a "Folders" list section in alphabetical order on the page. The first folder in the list is selected by default. The currently selected folder you're in will be highlighted red.

When a folder is selected, the images in that folder will have their thumbnails loaded in the "Files" list on the page. Clicking on any thumbnail in the "Files" list adds that image to the "Layers" list, and the full size image will appear in the builder box to the right. Each selected file in the "Files" list will be highlighted red.

You can re-order layers by clicking on a thumbnail in the "Layers" list, and dragging it above or below the other layers in the list.

To remove a layer, you can either double-click on that layer in the "Layers" list, or click on its corresponding red highlighted image in the "Files" list. To remove all layers, just click the "Clear Layers" button at the top of the "Layers" list area.

When you're ready to download the final image, just click "Export File" in the "Controls" area. Note that the folder where this app is running must be writable by the server, as it needs to create a temp file there for the download.