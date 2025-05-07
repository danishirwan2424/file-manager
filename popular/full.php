<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.0.10/css/froala_editor.pkgd.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.1/css/froala_editor.pkgd.min.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.1/css/froala_style.min.css">
  <link href="full.css" rel="stylesheet">
  <title>Text Editor</title>
  <link rel="icon" type="image/x-icon" href="../icon/froala.ico">
  <style>
    /* Hamburger Menu Styles */
    .hamburger-menu {
      position: absolute;
      top: 15px;
      left: 20px;
    }

    .hamburger-menu-toggle {
      background: #007bff;
      border: none;
      border-radius: 5px;
      color: white;
      font-size: 24px;
      cursor: pointer;
      padding: 10px;
      transition: background-color 0.3s;
    }

    .hamburger-menu-toggle:hover {
      background-color: #0056b3;
    }

    .hamburger-menu-content {
      display: none;
      position: absolute;
      top: 50px;
      left: 0;
      background: #ffffff;
      border: 1px solid #ddd;
      border-radius: 5px;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
      z-index: 1000;
      min-width: 200px;
    }

    .hamburger-menu-content button {
      display: block;
      width: 100%;
      padding: 10px;
      border: none;
      background: #f8f9fa;
      color: #333;
      text-align: left;
      cursor: pointer;
      font-size: 16px;
      transition: background-color 0.3s;
      font-family: monospace;
    }

    .hamburger-menu-content button:hover {
      background: #e2e6ea;
    }

    /* Custom CSS for the resizable div */
    .resizable-div {
      display: inline-block;
      border: 1px solid #000;
      width: 200px;
      height: auto;
      padding: 5px;
      resize: both;
      overflow: auto;
      cursor: se-resize;
      vertical-align: middle;
      position: relative;
      box-sizing: border-box;
    }

    /* Additional styles for content outside the div */
    .resizable-div::after {
      content: "";
      display: block;
      height: 1px;
      background: transparent;
    }

    .folder-icon {
      width: 100%;
      height: 100px;
      background: url('fileManager/imageIcons/folder.png') no-repeat center center;
      background-size: contain;
      text-align: center;
      display: flex;
      align-items: center;
      justify-content: center;
      text-decoration: none;
    }

    .dark-mode-toggle {
      background-color: transparent;
      border: none;
      cursor: pointer;
      margin-left: 10px;
      font-size: 18px;
    }

    .dark-mode {
      background-color: #121212;
      color: #ffffff;
    }
  </style>
</head>

<body>
  <!-- Hamburger Menu -->
  <div id="hamburgerMenu" class="hamburger-menu">
    <button id="menuToggle" class="hamburger-menu-toggle">
      <i class="fa fa-bars"></i>
    </button>
    <div id="menuContent" class="hamburger-menu-content">
      <button id="textEditorButton"><i class="fa fa-edit"></i> Text Editor</button>
      <button id="fileManagerButton"><i class="fa fa-folder"></i> File Manager</button>
    </div>
  </div>

  <div class="body-2">
    <div id="editor">
      <div id="edit" class="edit-container"></div>
      <div class="overlay"></div>
    </div>
    <div id="imageGallery" class="image-gallery" style="display: none;">
      <button id="closeImageGallery">Close</button>
      <input type="text" id="searchBar" placeholder="Search images..." class="search-bar">


      <!-- drop down menu sort file and folder by size, name, types -->
      <div class="dropdown">
        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
          <img src="fileManager/imageIcons/sort.png" alt="sort" class="sortImg" id="sortIcon"> Sort
        </button>
        <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <a class="dropdown-item" id="size" href="#">Size</a>
          <a class="dropdown-item" id="name" href="#">Name</a>
          <a class="dropdown-item" id="type" href="#">Type</a>
        </div>
      </div>


      <!-- Preview Modal -->
      <div id="previewModal" class="modal">
        <div class="modal-content">
          <span class="close">&times;</span>
          <div id="modalContent">

          </div>
        </div>
      </div>

      <!-- Folder and image items will be dynamically inserted here -->
      <div id="imageGalleryContent" class="image-gallery-content">
        <!-- Folder and image items will be dynamically inserted here -->
      </div>

    </div>
  </div>

  <!-- Image Upload Input -->
  <input type="file" id="imageUploadInput" style="display: none;" multiple>
  <input type="file" id="documentFolderUploadInput" style="display: none;" webkitdirectory directory multiple>


  <script defer src="script.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.0.10/js/froala_editor.pkgd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script> <!-- Include Font Awesome JS -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/4.0.14/js/froala_editor.pkgd.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.18.5/xlsx.full.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/froala-editor/3.2.1/js/froala_editor.pkgd.min.js"></script>
  <script>
    const imageUploadInput = document.getElementById('imageUploadInput');
    const documentFolderUploadInput = document.getElementById('documentFolderUploadInput');
    const imageGallery = document.getElementById('imageGallery');
    const closeImageGalleryButton = document.getElementById('closeImageGallery');

    // Uploading Image
    imageUploadInput.addEventListener('change', function() {
      const files = this.files;
      const formData = new FormData();
      for (let i = 0; i < files.length; i++) {
        formData.append('fileInput[]', files[i]);
      }
      fetch('upload.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('File uploaded successfully!');
            data.urls.forEach(url => {
              const editor = FroalaEditor.INSTANCES[0];
              editor.image.insert(url, false, null, editor.image.get());
            });
          } else {
            console.error('File upload failed:', data.message);
          }
        })
        .catch(error => console.error('Error:', error));
    });


    // Upload Folder
    documentFolderUploadInput.addEventListener('change', () => {
      const files = documentFolderUploadInput.files;
      if (files.length === 0) {
        return;
      }
      const formData = new FormData();
      const firstFilePath = files[0].webkitRelativePath;
      const folderName = firstFilePath.split('/')[0];
      formData.append('folderName', folderName);

      for (let i = 0; i < files.length; i++) {
        formData.append('files[]', files[i], files[i].webkitRelativePath);
      }
      fetch('uploadFolder.php', {
          method: 'POST',
          body: formData
        })
        .then(response => response.json())
        .then(data => {
          if (data.success) {
            alert('Folder uploaded successfully!');
            updateImageGallery(); // Refresh the gallery
          } else {
            alert('Failed to upload folder.');
          }
        })
        .catch(error => {
          console.error('Error uploading folder:', error);
          alert('An error occurred while uploading the folder.');
        });
    });

    // Froala 
    new FroalaEditor('#edit', {
      toolbarButtons: [
        'fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript',
        'superscript', 'fontFamily', 'fontSize', 'colors', 'inlineStyle', 'paragraphStyle',
        'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote',
        'insertHR', 'insertLink', 'customInsertImage', 'insertDiv', 'insertTable', 'emoticons',
        'specialCharacters', 'insertFile', 'insertVideo', 'clearFormatting', 'print', 'html',
        'undo', 'redo'
      ],
      imageInsertButtons: ['imageBack', '|', 'customInsertImage'],
      heightMin: 300,
      widthMin: 900,
      placeholderText: "What's on your mind...",
      pluginsEnabled: [
        'align', 'charCounter', 'codeBeautifier', 'codeMirror', 'codeView', 'colors', 'draggable',
        'embedly', 'emoticons', 'entities', 'file', 'fontAwesome', 'fontFamily', 'fontSize',
        'fullscreen', 'image', 'imageManager', 'imageTUI', 'inlineClass', 'inlineStyle',
        'lineBreaker', 'lineHeight', 'link', 'lists', 'paragraphFormat', 'paragraphStyle',
        'print', 'quickInsert', 'quote', 'save', 'specialCharacters', 'table', 'url',
        'video', 'wordPaste'
      ],
      colors: [
        '#000000', '#FF0000', '#00FF00', '#0000FF',
        '#FFFF00', '#FF00FF', '#00FFFF', '#FFFFFF',
        '#FFA500', '#800080', '#008000', '#000080',
        '#FFC0CB', '#FFD700', '#8B0000', '#FF4500',
        '#A52A2A', '#5F9EA0', '#D2691E', '#4682B4',
        '#B22222', '#FF69B4', '#C71585', '#7FFF00',
        '#7CFC00', '#32CD32', '#98FB98', '#ADFF2F'
      ]
    });


    // Insert Table Div
    FroalaEditor.DefineIcon('insertDiv', {
      NAME: 'table',
      SVG_KEY: 'add'
    });
    // Register the 'Insert Table' command
    FroalaEditor.RegisterCommand('insertDiv', {
      title: 'Insert Div',
      icon: 'insertDiv',
      focus: true,
      undo: true,
      refreshAfterCallback: true,
      callback: function() {
        // Define the HTML for the custom table
        var tableHTML = `
      <table class="fr-dashed-borders fr-alternate-rows" style="width:100%;">
        <tbody>
          <tr>
            <td style="text-align:center;">
              <br><span style="font-size:24px;"><a href="https://froala.com/wysiwyg-editor/docs/" title="Documentation" style="text-decoration: none; color: #0098f7;"><strong>Documentation</strong></a>&nbsp;</span></td>
            <td style="text-align: center;">
              <br><span style="font-size:24px;"><a href="https://froala.com/wysiwyg-editor/docs/" title="Report" style="text-decoration: none; color: #0098f7;"><strong>Report</strong></a>&nbsp;</span></td>
            <td style="text-align: center;">
              <br><span style="font-size:24px;"><a href="https://froala.com/wysiwyg-editor/docs/" title="Analysis" style="text-decoration: none; color: #0098f7;"><strong>Analysis</strong></a>&nbsp;</span></td>
          </tr>
        </tbody>
      </table>
    `;
        // Insert the table into the editor at the current cursor position
        this.html.insert(tableHTML);
        // Make each cell adjustable independently
        var $cells = this.$el.find('td').slice(-3);
        // Function to update cell width
        function updateCellWidth($cell) {
          $cell.css('width', 'auto');
        }
        // Attach resize event listener to each cell
        $cells.each(function() {
          var $cell = $(this);
          var observer = new ResizeObserver(function() {
            updateCellWidth($cell);
          });
          observer.observe(this);
        });
      }
    });


    // Insert Image, File, Folder, Browse
    FroalaEditor.DefineIcon('customInsertImage', {
      NAME: 'image',
      SVG_KEY: 'insertImage'
    });
    // Register the 'Insert Image' command
    FroalaEditor.RegisterCommand('customInsertImage', {
      title: 'Insert Image',
      type: 'dropdown',
      focus: true,
      undo: true,
      refreshAfterCallback: true,
      options: {
        'imageUpload': 'Upload File',
        'folderUpload': 'Upload Folder',
        'imageBrowser': 'Browse Item',
      },
      callback: function(cmd, val) {
        if (val === 'imageUpload') {
          imageUploadInput.click();
        } else if (val === 'imageBrowser') {
          console.log('Image browser selected');
          updateImageGallery();
        } else if (val === 'folderUpload') {
          document.getElementById('documentFolderUploadInput').click();
        }
      }
    });

    // search bar
    document.addEventListener('DOMContentLoaded', () => {
      const searchBar = document.getElementById('searchBar');
      const imageGalleryContent = document.getElementById('imageGalleryContent');
      const closeButton = document.getElementById('closeImageGallery');

      // Change the placeholder text
      searchBar.placeholder = 'Search items...';

      // Example data for testing
      const items = [{
          type: 'folder',
          name: 'Folder 1',
          url: 'fileManager/imageIcons/folder.png'
        },
        {
          type: 'image',
          name: 'Image 1',
          url: 'path/to/image1.jpg'
        },
        // Add more items as needed
      ];

      // Function to render the gallery items
      function renderGallery(items) {
        const imageGalleryContent = document.getElementById('imageGalleryContent');
        imageGalleryContent.innerHTML = '';

        items.forEach(item => {
          const container = document.createElement('div');
          container.className = 'image-container';

          if (item.type === 'folder') {
            const folderIcon = document.createElement('div');
            folderIcon.className = 'folder-icon';
            container.appendChild(folderIcon);
          } else {
            const img = document.createElement('img');
            img.src = item.url;
            img.className = 'gallery-image';
            container.appendChild(img);
          }
          const fileName = document.createElement('div');
          fileName.className = 'file-name';
          // Truncate text if it's too long
          const maxLength = 20;
          let displayName = item.name;
          if (displayName.length > maxLength) {
            displayName = displayName.substring(0, maxLength) + '...';
          }
          fileName.textContent = displayName;
          container.appendChild(fileName);
          imageGalleryContent.appendChild(container);
        });
      }

      function searchFiles() {
        const query = searchBar.value.toLowerCase();
        Array.from(imageGalleryContent.children).forEach(item => {
          const name = item.querySelector('.file-name').textContent.toLowerCase();
          const isVisible = name.includes(query);
          item.style.display = isVisible ? 'block' : 'none';
        });
      }
      searchBar.addEventListener('input', searchFiles);
      closeButton.addEventListener('click', () => {
        document.getElementById('imageGallery').style.display = 'none';
      });
      // Initial rendering of gallery items
      renderGallery(items);
    });


    // Function to insert a file link or image into the editor
    function insertLink(url, fileType) {
      const editor = FroalaEditor.INSTANCES[0];
      // Define allowed image file extensions
      const imageTypes = ['png', 'jpeg', 'jpg', 'gif'];
      const fileExtension = url.split('.').pop().toLowerCase();
      if (imageTypes.includes(fileExtension)) {
        // Insert image into editor
        editor.image.insert(url, false, null, editor.image.get());
      } else {
        // Insert other file types as link
        editor.html.insert(`<a href="${url}" target="_blank">${url}</a>`);
      }
    }


    // Function sort file and folder
    document.addEventListener('DOMContentLoaded', function() {
      const sortOptions = document.querySelectorAll('.dropdown-item');
      const galleryItems = document.querySelectorAll('.gallery-item');

      sortOptions.forEach(option => {
        option.addEventListener('click', function() {
          const sortBy = this.id;
          updateImageGallery(sortBy);
        });
      });

      function sortGallery(files, sortBy) {
        return files.sort((a, b) => {
          let aValue, bValue;
          switch (sortBy) {
            case 'size':
              aValue = a.size;
              bValue = b.size;
              break;
            case 'name':
              aValue = a.name.toLowerCase();
              bValue = b.name.toLowerCase();
              break;
            case 'type':
              aValue = a.type.toLowerCase();
              bValue = b.type.toLowerCase();
              break;
          }
          if (aValue < bValue) return -1;
          if (aValue > bValue) return 1;
          return aValue.localeCompare(bValue);
        });
      }
    });

    document.getElementById('imageGalleryButton').addEventListener('click', function() {
      document.getElementById('imageGallery').style.display = 'block';
    });
    document.getElementById('closeImageGallery').addEventListener('click', function() {
      document.getElementById('imageGallery').style.display = 'none';
    });


    function removeItem(path, type) {
      const confirmation = confirm('Are you sure you want to delete this item?');
      if (!confirmation) return;

      fetch('remove_item.php', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
          },
          body: JSON.stringify({
            filePaths: [path],
          }),
        })
        .then(response => response.json())
        .then(result => {
          if (result.success) {
            // Remove the item from the gallery immediately
            const itemToRemove = document.querySelector(`.file-container[data-path="${path}"]`);
            if (itemToRemove) {
              itemToRemove.remove();
            }

            // Prompt the user to reload the page if needed
            const reloadConfirmation = alert('Item removed successfully.');
            if (reloadConfirmation) {
              updateImageGallery('name', result.folderId || currentFolderId);
            }
          } else {
            alert(`Failed to remove item: ${result.message}`);
          }
        })
        .catch(error => {
          console.error('Error removing item:', error);
          alert('Error removing item. Please try again.');
        });
    }

    // Current fix error
    // Function to open the modal with preview content
    function updateImageGallery(sortBy, folderId) {
      const url = `list_images.php?folderId=${encodeURIComponent(folderId)}`;
      fetch(url)
        .then(response => response.json())
        .then(files => {
          console.log('Files loaded:', files);
          const galleryContent = document.getElementById('imageGalleryContent');
          galleryContent.innerHTML = '';

          // Sort files based on the sortBy parameter
          files.sort((a, b) => {
            let aValue, bValue;
            switch (sortBy) {
              case 'size':
                aValue = a.size;
                bValue = b.size;
                return aValue - bValue;
              case 'name':
                aValue = a.name.toLowerCase();
                bValue = b.name.toLowerCase();
                return aValue.localeCompare(bValue);
              case 'type':
                aValue = a.type.toLowerCase();
                bValue = b.type.toLowerCase();
                return aValue.localeCompare(bValue);
              default:
                aValue = a.name.toLowerCase();
                bValue = b.name.toLowerCase();
                return aValue.localeCompare(bValue);
            }
          });

          files.forEach(file => {
            let element;
            const fileExtension = file.name.split('.').pop().toLowerCase();

            // Create the element based on file type
            if (file.type === 'application/pdf') {
              element = document.createElement('div');
              element.className = 'pdf-icon';
            } else if (file.type === 'folder') {
              element = document.createElement('div');
              element.className = 'folder-icon';
            } else if (fileExtension === 'docx') {
              element = document.createElement('div');
              element.className = 'docx-icon';
            } else if (fileExtension === 'xlsx') {
              element = document.createElement('div');
              element.className = 'xlsx-icon';
            } else if (fileExtension === 'mp3') {
              element = document.createElement('div');
              element.className = 'mp3-icon';
            } else if (fileExtension === 'txt') {
              element = document.createElement('div');
              element.className = 'txt-icon';
            } else if (fileExtension === 'mp4') {
              element = document.createElement('div');
              element.className = 'mp4-icon';
            } else if (['jpeg', 'jpg', 'png', 'gif'].includes(fileExtension)) {
              element = document.createElement('img');
              element.src = file.url;
              element.alt = file.name;
              element.className = 'gallery-image';
            } else {
              element = document.createElement('div');
              element.className = 'file-icon';
            }

            // Create and style name element
            const maxLength = 20;
            let displayName = file.name;
            if (displayName.length > maxLength) {
              const visiblePart = displayName.substring(0, maxLength - 3);
              displayName = visiblePart + '...';
            }

            const nameElement = document.createElement('p');
            nameElement.className = 'file-name';
            nameElement.textContent = displayName;

            // Create buttons
            const insertButton = document.createElement('button');
            insertButton.innerHTML = '<i class="fas fa-plus"></i>';
            insertButton.className = 'icon-button insert-button';
            insertButton.addEventListener('click', () => insertLink(file.url));

            const removeButton = document.createElement('button');
            removeButton.innerHTML = '<i class="fas fa-trash"></i>';
            removeButton.className = 'icon-button remove-button';
            removeButton.addEventListener('click', () => removeItem(file.path, file.type));

            const previewButton = document.createElement('button');
            previewButton.innerHTML = '<i class="fas fa-eye"></i>';
            previewButton.className = 'icon-button preview-button';
            previewButton.addEventListener('click', () => {
              if (file.type === 'folder') {
                loadFolderContents(file.url);
              } else {
                previewItem(file.url, file.type, file.name);
              }
            });

            // Append elements to container
            const buttonContainer = document.createElement('div');
            buttonContainer.className = 'icon-buttons';
            buttonContainer.appendChild(insertButton);
            buttonContainer.appendChild(removeButton);
            buttonContainer.appendChild(previewButton);

            const container = document.createElement('div');
            container.className = 'file-container';
            container.setAttribute('data-path', file.path); // Set data-path attribute
            container.appendChild(element);
            container.appendChild(nameElement);
            container.appendChild(buttonContainer);

            // Append container to gallery
            galleryContent.appendChild(container);
          });


          const imageGallery = document.getElementById('imageGallery');
          imageGallery.style.display = 'grid';
        })
        .catch(error => {
          console.error('Error fetching image data:', error);
          const galleryContent = document.getElementById('imageGalleryContent');
          galleryContent.innerHTML = '<p>Error loading gallery. Please try again later.</p>';
        });
    }

    function previewItem(fileUrl, fileType, fileName) {
      const modalContent = document.getElementById('modalContent');
      modalContent.innerHTML = '';

      const fileExtension = fileUrl.split('.').pop().toLowerCase();

      if (['jpg', 'jpeg', 'png', 'gif'].includes(fileExtension)) {
        const img = document.createElement('img');
        img.src = fileUrl;
        img.alt = 'Preview';
        img.style.maxWidth = '80%';
        img.style.maxHeight = '80%';
        modalContent.appendChild(img);

      } else if (fileExtension === 'pdf' || fileExtension === 'docx') {
        const iframe = document.createElement('iframe');
        iframe.src = fileUrl;
        iframe.style.width = '90%';
        iframe.style.height = '90%';
        modalContent.appendChild(iframe);

      } else if (fileExtension === 'txt') {
        fetch(fileUrl)
          .then(response => response.text())
          .then(text => {
            const pre = document.createElement('pre');
            pre.textContent = text;
            pre.style.width = '100%';
            pre.style.height = '95%';
            pre.style.marginTop = '50px';
            pre.style.overflow = 'auto';
            modalContent.appendChild(pre);
          })
          .catch(error => {
            console.error('Failed to load text file', error);
            const message = document.createElement('p');
            message.textContent = 'Failed to load text file.';
            modalContent.appendChild(message);
          });

      } else if (fileExtension === 'xlsx') {
        fetch(fileUrl)
          .then(response => response.arrayBuffer())
          .then(data => {
            const workbook = XLSX.read(data, {
              type: 'array'
            });
            const sheetName = workbook.SheetNames[0];
            const worksheet = workbook.Sheets[sheetName];
            const html = XLSX.utils.sheet_to_html(worksheet);

            const div = document.createElement('div');
            div.innerHTML = html;

            // Add styles to the table and container
            const style = document.createElement('style');
            style.textContent = `
          .table-container {
            height: 450px; /* Set the height of the scrollable area */
            width: 1000px;
            overflow-y: auto; /* Add vertical scrollbar */
            overflow-x: auto; /* Add vertical scrollbar */
            border: 1px solid #ddd; /* Optional: border around the container */
            margin-top: 10px; /* Optional: space between modal content and table */
          }
          table {
            border-collapse: collapse;
            width: 100%;
          }
          th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
          }
          th {
            background-color: #f2f2f2;
          }
        `;
            div.appendChild(style);

            // Create and append the container div
            const container = document.createElement('div');
            container.className = 'table-container';
            container.appendChild(div);

            modalContent.appendChild(container);
          })
          .catch(error => {
            console.error('Failed to load Excel file', error);
            const message = document.createElement('p');
            message.textContent = 'Failed to load Excel file.';
            modalContent.appendChild(message);
          });
      } else if (fileExtension === 'mp3') {
        const audio = document.createElement('audio');
        audio.src = fileUrl;
        audio.controls = true;
        audio.style.width = '80%';
        modalContent.appendChild(audio);

      } else if (fileExtension === 'mp4') {
        const video = document.createElement('video');
        video.src = fileUrl;
        video.controls = true;
        video.style.width = '80%';
        modalContent.appendChild(video);

      }

      const modal = document.getElementById('previewModal');
      modal.style.display = 'block';
      modal.style.backgroundColor = 'rgba(0, 0, 0, 0.84)'; // Example: dark semi-transparent background
      modal.style.color = 'white';

      const closeButton = document.querySelector('.close');
      closeButton.onclick = () => {
        modal.style.display = 'none';
      };

      window.onclick = (event) => {
        if (event.target === modal) {
          modal.style.display = 'none';
        }
      };
    }
    async function loadFolderContents(folderUrl) {
      console.log('Loading folder contents from:', folderUrl);
      const galleryContent = document.getElementById('imageGalleryContent');

      try {
        const response = await fetch(`list_images.php?folderPath=${encodeURIComponent(folderUrl)}`);

        if (!response.ok) {
          throw new Error('Network response was not ok');
        }

        const files = await response.json();
        console.log('Files loaded:', files);
        galleryContent.innerHTML = '';

        files.forEach(file => {
          let element;
          const fileExtension = file.name.split('.').pop().toLowerCase(); // Get the file extension

          switch (fileExtension) {
            case 'pdf':
              element = document.createElement('div');
              element.className = 'pdf-icon';
              break;
            case 'folder':
              element = document.createElement('img');
              element.href = '#';
              element.className = 'folder-icon';
              element.onclick = () => loadFolderContents(file.url);
              break;
            case 'jpeg':
            case 'jpg':
            case 'png':
            case 'gif':
              element = document.createElement('img');
              element.src = file.url;
              element.alt = file.name;
              element.className = 'gallery-image';
              break;
            case 'docx':
              element = document.createElement('div');
              element.className = 'docx-icon';
            case 'txt':
              element = document.createElement('div');
              element.className = 'txt-icon';
              break;
            case 'xlsx':
              element = document.createElement('div');
              element.className = 'xlsx-icon';
              break;
            case 'mp3':
              element = document.createElement('div');
              element.className = 'mp3-icon';
              break;
            case 'mp4':
              element = document.createElement('div');
              element.className = 'mp4-icon';
              break;
            default:
              element = document.createElement('div');
              element.className = 'file-icon';
              break;
          }
          const maxLength = 20;
          let displayName = file.name;

          if (displayName.length > maxLength) {
            const visiblePart = displayName.substring(0, maxLength - 3);
            displayName = visiblePart + '...';
          }
          const nameElement = document.createElement('p');
          nameElement.className = 'file-name';
          nameElement.textContent = displayName;

          const insertButton = document.createElement('button');
          insertButton.innerHTML = '<i class="fas fa-plus"></i>';
          insertButton.className = 'icon-button insert-button';
          insertButton.addEventListener('click', () => insertLink(file.url));

          const removeButton = document.createElement('button');
          removeButton.innerHTML = '<i class="fas fa-trash"></i>';
          removeButton.className = 'icon-button remove-button';
          removeButton.addEventListener('click', () => removeItem(file.path, file.type));

          const previewButton = document.createElement('button');
          previewButton.innerHTML = '<i class="fas fa-eye"></i>';
          previewButton.className = 'icon-button preview-button';
          previewButton.addEventListener('click', () => {
            if (file.type === 'folder') {
              loadFolderContents(file.url);
            } else {
              previewItem(file.url, file.type, file.name);
            }
          });
          const buttonContainer = document.createElement('div');
          buttonContainer.className = 'icon-buttons';
          buttonContainer.appendChild(insertButton);
          buttonContainer.appendChild(removeButton);
          buttonContainer.appendChild(previewButton);

          const container = document.createElement('div');
          container.className = 'file-container';
          container.appendChild(element);
          container.appendChild(nameElement);
          container.appendChild(buttonContainer);
          galleryContent.appendChild(container);
        });

        const backButton = document.createElement('button');
        backButton.innerHTML = '<img src="fileManager/imageIcons/back.png" alt="Back" class="back-icon" style="width: 34px; height: 34px;">';
        backButton.className = 'back-button';
        backButton.addEventListener('click', () => {
          const parentFolder = folderUrl.substring(0, folderUrl.lastIndexOf('/'));
          updateImageGallery(null, parentFolder || '');
        });
        galleryContent.insertBefore(backButton, galleryContent.firstChild);
      } catch (error) {
        console.error('Error loading folder contents:', error);
        galleryContent.innerHTML = '<p>Error loading folder contents. Please try again later.</p>';
      }
    }

    // Insert the event listener script here
    document.addEventListener('DOMContentLoaded', function() {
      const sortOptions = document.querySelectorAll('.dropdown-item');

      sortOptions.forEach(option => {
        option.addEventListener('click', function() {
          const sortBy = this.id;
          updateImageGallery(sortBy);
        });
      });
      // Initial gallery update with default sorting by name
      updateImageGallery('name');
    });
    closeImageGalleryButton.addEventListener('click', () => {
      imageGallery.style.display = 'none';
    });


    function toggleEditOptions(element) {
      const editOptions = element.nextElementSibling;
      editOptions.style.display = editOptions.style.display === 'block' ? 'none' : 'block';
    }

    document.addEventListener('DOMContentLoaded', () => {
      const menuToggle = document.getElementById('menuToggle');
      const menuContent = document.getElementById('menuContent');

      menuToggle.addEventListener('click', () => {
        menuContent.style.display = menuContent.style.display === 'block' ? 'none' : 'block';
      });

      document.getElementById('textEditorButton').addEventListener('click', () => {
        window.location.href = 'full.php'; // Path to Text Editor
      });

      document.getElementById('fileManagerButton').addEventListener('click', () => {
        window.location.href = 'fileManager/fileManager.php'; // Path to File Manager
      });
    });
  </script>
</body>

</html>