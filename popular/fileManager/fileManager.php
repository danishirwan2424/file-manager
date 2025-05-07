<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.4.0/css/font-awesome.min.css">
  <link href="css/file.css" rel="stylesheet">
  <title>File Manager</title>
  <link rel="icon" type="image/x-icon" href="../../icon/froala.ico">
  <style>
/* Add position to gallery items */
.gallery-item {
  position: relative; /* Make it the reference for absolute positioning of kebab menu */
  /* Other styles */
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
    <div class="toolbar">
      <!-- Toolbar -->
      <button id="uploadFolderButton"><i class="fa fa-folder-open"></i> Upload Folder</button>
      <button id="uploadFileButton"><i class="fa fa-file"></i> Upload File</button>
      <button id="createFolderButton"><i class="fa fa-folder"></i> Create Folder</button>
      <div class="dropdown">
    </div>
      <button id="deleteButton"><i class="fa fa-trash"></i> Delete</button>
      <button id="doneButton"><i class="fa fa-check"></i> Done</button> <!-- Button is hide -->
    </div>

    <div class="gallery" id="gallery">
      <!-- Gallery items  -->
    </div>
  </div>

  <!-- File Input for Folder Upload -->
  <input type="file" id="folderInput" webkitdirectory style="display:none;">
  <!-- File Input for File Upload -->
  <input type="file" id="fileInput" style="display:none;">

  <!-- Popup Div for Image Display -->
  <div id="imagePopup" class="image-popup" style="display:none;">
    <div class="popup-content">
      <span id="popupClose" class="popup-close">&times;</span>
      <img id="popupImage" src="" alt="Popup Image">
    </div>
  </div>

  <script>
    function renameItem(event) {
    const galleryItem = event.target.closest('.gallery-item');
    const fileNameElement = galleryItem.querySelector('p');
    const oldName = fileNameElement.textContent;

    // Prompt the user for a new name
    const newName = prompt('Enter the new name:', oldName);

    if (newName && newName !== oldName) {
        // Update the name displayed in the gallery
        fileNameElement.textContent = newName;

        // Send a request to the server to rename the file/folder
        fetch('rename_item.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `oldName=${encodeURIComponent(oldName)}&newName=${encodeURIComponent(newName)}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Item renamed successfully!');
                loadGallery(); // Refresh the gallery to reflect the new name
            } else {
                alert('Failed to rename the item.');
                fileNameElement.textContent = oldName; // Revert the name on failure
            }
        })
        .catch(error => {
            console.error('Error renaming item:', error);
            alert('An error occurred while renaming the item.');
            fileNameElement.textContent = oldName; // Revert the name on error
        });
    }
}

  // Hamburger menu toggle
  const menuToggle = document.getElementById('menuToggle');
  const menuContent = document.getElementById('menuContent');
  const textEditorButton = document.getElementById('textEditorButton');
  const fileManagerButton = document.getElementById('fileManagerButton');
  menuToggle.addEventListener('click', () => {
    menuContent.style.display = menuContent.style.display === 'block' ? 'none' : 'block';
  });
  textEditorButton.addEventListener('click', () => {
    window.location.href = '../full.php'; // Path to Text Editor
  });
  fileManagerButton.addEventListener('click', () => {
    window.location.href = 'fileManager.php'; // Adjusted path to File Manager
  });

  
  // Function to show the image popup
  function showImagePopup(imageSrc) {
    const popup = document.getElementById('imagePopup');
    const popupImage = document.getElementById('popupImage');
    popupImage.src = imageSrc;
    popup.style.display = 'block';
  }

  // Function to hide the image popup
  function hideImagePopup() {
    const popup = document.getElementById('imagePopup');
    popup.style.display = 'none';
  }

  // Event listener for closing the popup
  document.getElementById('popupClose').addEventListener('click', hideImagePopup);

  // Upload File
  const uploadFileButton = document.getElementById('uploadFileButton');
  const fileInput = document.getElementById('fileInput');

  uploadFileButton.addEventListener('click', () => {
    fileInput.click();
  });

  fileInput.addEventListener('change', () => {
    const file = fileInput.files[0];
    if (!file) {
      return;
    }

    const formData = new FormData();
    formData.append('file', file);

    fetch('upload_file.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('File uploaded successfully!');
        loadGallery(); // Refresh the gallery
      } else {
        alert('Failed to upload file.');
      }
    })
    .catch(error => {
      console.error('Error uploading file:', error);
      alert('An error occurred while uploading the file.');
    });
  });

  // Upload Folder
  const uploadFolderButton = document.getElementById('uploadFolderButton');
  const folderInput = document.getElementById('folderInput');

  uploadFolderButton.addEventListener('click', () => {
    folderInput.click();
  });

  folderInput.addEventListener('change', () => {
    const files = folderInput.files;
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

    fetch('upload_folder.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Folder uploaded successfully!');
        loadGallery(); // Refresh the gallery
      } else {
        alert('Failed to upload folder.');
      }
    })
    .catch(error => {
      console.error('Error uploading folder:', error);
      alert('An error occurred while uploading the folder.');
    });
  });

  // Create Folder
  const createFolderButton = document.getElementById('createFolderButton');
  createFolderButton.addEventListener('click', () => {
    const folderName = prompt('Enter the name of the new folder:');
    if (!folderName) {
      return;
    }

    const formData = new FormData();
    formData.append('folderName', folderName);

    fetch('create_folder.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Folder created successfully!');
        loadGallery(); // Refresh the gallery
      } else {
        alert('Failed to create folder: ' + data.message);
      }
    })
    .catch(error => {
      console.error('Error creating folder:', error);
      alert('An error occurred while creating the folder.');
    });
  });

  // Function to delete a file
  function deleteFile(filePath) {
    if (!confirm('Are you sure you want to delete this file?')) {
      return;
    }

    const formData = new FormData();
    formData.append('filePath', filePath);

    fetch('delete_file.php', {
      method: 'POST',
      body: formData
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('File deleted successfully!');
        loadGallery(); // Refresh the gallery
      } else {
        alert('Failed to delete file.');
      }
    })
    .catch(error => {
      console.error('Error deleting file:', error);
      alert('An error occurred while deleting the file.');
    });
  }

  // delete check-box
  // Function to delete a file
  // Function to delete files
function deleteFile(filePaths) {
  if (!confirm('Are you sure you want to delete the selected files?')) {
    return;
  }
  // Create the request payload as JSON
  const payload = JSON.stringify({ filePaths: filePaths });
  fetch('delete_file.php', {
    method: 'POST',
    headers: {
      'Content-Type': 'application/json' // Set the content type to JSON
    },
    body: payload
  })
  .then(response => response.json())
  .then(data => {
    if (data.success) {
      alert('Files deleted successfully!');
      loadGallery(); // Refresh the gallery
    } else {
      alert('Failed to delete files: ' + (data.message || 'Unknown error.'));
    }
  })
  .catch(error => {
    console.error('Error deleting files:', error);
    alert('An error occurred while deleting the files.');
  });
}
// Toggle checkboxes visibility when delete button is clicked
const deleteButton = document.getElementById('deleteButton');
const doneButton = document.getElementById('doneButton');
let isCheckboxVisible = false;
deleteButton.addEventListener('click', () => {
  isCheckboxVisible = !isCheckboxVisible;
  const galleryItems = document.querySelectorAll('.gallery-item');
  galleryItems.forEach(item => {
    const checkbox = item.querySelector('.checkbox');
    if (checkbox) {
      checkbox.style.display = isCheckboxVisible ? 'block' : 'none';
    }
  });
  // Show the Done button when checkboxes are visible
  doneButton.style.display = isCheckboxVisible ? 'block' : 'none';
});
doneButton.addEventListener('click', () => {
  const selectedFiles = [];
  const galleryItems = document.querySelectorAll('.gallery-item');
  galleryItems.forEach(item => {
    const checkbox = item.querySelector('.checkbox');
    if (checkbox && checkbox.checked) {
      const filePath = checkbox.getAttribute('data-filepath'); // Get the file path from the checkbox
      selectedFiles.push(filePath);
    }
  });
  if (selectedFiles.length > 0) {
    deleteFile(selectedFiles); // Call the delete function with selected file paths
  } else {
    alert('No files selected for deletion.');
  }
  // Hide checkboxes and Done button after deletion
  isCheckboxVisible = false;
  galleryItems.forEach(item => {
    const checkbox = item.querySelector('.checkbox');
    if (checkbox) {
      checkbox.style.display = 'none';
    }
  });
  doneButton.style.display = 'none';
});


// Function to open image in a new tab
function openImageInNewTab(imageSrc) {
  window.open(imageSrc, '_blank');
}


// Function to rename Folder and File " .gallery-item p "
// * If I double click ".gallery-item p" it will show prompt-box to rename the file*
// Rename Item Function
  function renameItem(event) {
  const galleryItem = event.target.closest('.gallery-item');
  const fileNameElement = galleryItem.querySelector('p');
  const oldName = fileNameElement.textContent;
  const folderPath = galleryItem.getAttribute('data-folderpath'); // Get folder path
  const fullPath = `${folderPath}/${oldName}`; // Full path of the item

  const newName = prompt('Enter the new name:', oldName);

  if (newName && newName !== oldName) {
    fileNameElement.textContent = newName;

    fetch('rename_item.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/x-www-form-urlencoded'
      },
      body: `oldPath=${encodeURIComponent(fullPath)}&newName=${encodeURIComponent(newName)}`
    })
    .then(response => response.json())
    .then(data => {
      if (data.success) {
        alert('Item renamed successfully!');
        loadGallery(folderPath); // Refresh the gallery
      } else {
        alert('Failed to rename item.');
        fileNameElement.textContent = oldName; // Revert on failure
      }
    })
    .catch(error => {
      console.error('Error renaming item:', error);
      alert('An error occurred while renaming the item.');
      fileNameElement.textContent = oldName; // Revert on error
    });
  }
}



// Function loadGallery 
function loadGallery(folderPath = '') {
  fetch(`list_files.php?folderPath=${folderPath}`)
    .then(response => response.json())
    .then(data => {
      const gallery = document.getElementById('gallery');
      gallery.innerHTML = '';

      data.files.forEach(file => {
        const fileDiv = document.createElement('div');
        fileDiv.classList.add('gallery-item');

        let filePath = `${folderPath}/${file.name}`;

        // Add checkbox for delete functionality
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.className = 'checkbox';
        checkbox.style.display = 'none'; // Initially hide the checkbox
        checkbox.setAttribute('data-filepath', filePath); // Store the file path in the checkbox
        fileDiv.appendChild(checkbox);

        // Add the rename functionality
        const fileNameElement = document.createElement('p');
        fileNameElement.textContent = file.name;

        if (file.type === 'folder') {
          const folderIcon = document.createElement('img');
          folderIcon.src = 'imageIcons/folder.png';
          folderIcon.alt = 'Folder';
          fileDiv.appendChild(folderIcon);
          fileDiv.appendChild(fileNameElement);

          fileDiv.addEventListener('dblclick', (event) => {
            // Prevent folder opening when clicking on the name
            if (event.target !== fileNameElement) {
              loadGallery(filePath);
            }
          });
        } else if (file.type.match(/image\//)) {
          const imgElement = document.createElement('img');
          imgElement.src = `image.php?path=${encodeURIComponent(filePath)}`; // Use the PHP script to serve the image
          imgElement.alt = file.name;
          imgElement.className = 'gallery-image';
          imgElement.onerror = () => {
            imgElement.src = 'path/to/default/icon.png'; // Default icon
            console.error('Failed to load image:', filePath);
          };
          imgElement.addEventListener('dblclick', () => {
            openImageInNewTab(imgElement.src);
          });
          fileDiv.appendChild(imgElement);
          fileDiv.appendChild(fileNameElement);
        } else if (file.type === 'application/pdf') {
          const fileIcon = document.createElement('img');
          fileIcon.src = 'imageIcons/pdfFile.png';
          fileIcon.alt = 'PDF File';
          fileDiv.appendChild(fileIcon);
          fileDiv.appendChild(fileNameElement);

          fileDiv.addEventListener('dblclick', () => {
            const pdfPath = `http://localhost/MyMCE/file-web/CDN/${filePath}`;
            window.open(pdfPath, '_blank'); // Open PDF file in new tab
          });
        } else if (file.type === 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet') {
          const fileIcon = document.createElement('img');
          fileIcon.src = 'imageIcons/xlsx.png'; // Update the icon path to match your .xlsx file icon
          fileIcon.alt = 'XLSX File';
          fileDiv.appendChild(fileIcon);
          fileDiv.appendChild(fileNameElement);

          fileDiv.addEventListener('dblclick', () => {
          // Create an anchor element for downloading
          const downloadLink = document.createElement('a');
          downloadLink.href = `http://localhost/MyMCE/file-web/CDN/${filePath}`;
          downloadLink.download = ''; // Triggers a download; you can specify a filename if needed

          // Programmatically trigger the click event on the anchor element
          downloadLink.click();
          });
        } else if (file.type === 'text/plain') {
          const fileIcon = document.createElement('img');
          fileIcon.src = 'imageIcons/txt.png';
          fileIcon.alt = 'Text File';
          fileDiv.appendChild(fileIcon);
          fileDiv.appendChild(fileNameElement);

          fileDiv.addEventListener('dblclick', () => {
            // Create an anchor element for downloading
            const downloadLink = document.createElement('a');
            downloadLink.href = `file.php?path=${encodeURIComponent(filePath)}`;
            downloadLink.download = ''; // Triggers a download; you can provide a filename if needed
            // Programmatically trigger the click event on the anchor element
            downloadLink.click();
          });
         } else if (file.type === 'audio/mpeg' || file.type === 'audio/mp3') {
           const fileIcon = document.createElement('img');
           fileIcon.src = 'imageIcons/mp3 (1).png'; // Use the icon for MP3 files
           fileIcon.alt = 'MP3 File';
           fileDiv.appendChild(fileIcon);
           fileDiv.appendChild(fileNameElement);

           fileDiv.addEventListener('dblclick', () => {
           // Create an anchor element for downloading
           const downloadLink = document.createElement('a');
           downloadLink.href = `file.php?path=${encodeURIComponent(filePath)}`;
           downloadLink.download = ''; // Triggers a download; you can provide a filename if needed
           // Programmatically trigger the click event on the anchor element
           downloadLink.click();
           });
         } else if (file.type === 'video/mp4') {
           const fileIcon = document.createElement('img');
           fileIcon.src = 'imageIcons/mp4.png'; // Use the icon for MP4 files
           fileIcon.alt = 'MP4 File';
           fileDiv.appendChild(fileIcon);
           fileDiv.appendChild(fileNameElement);

           fileDiv.addEventListener('dblclick', () => {
           // Create an anchor element for downloading
           const downloadLink = document.createElement('a');
           downloadLink.href = `file.php?path=${encodeURIComponent(filePath)}`;
           downloadLink.download = ''; // Triggers a download; you can provide a filename if needed
           // Programmatically trigger the click event on the anchor element
           downloadLink.click();
           });
         } else {
          const fileIcon = document.createElement('img');
          fileIcon.src = 'imageIcons/random.png'; // Update the icon path to match your .random file icon
          fileIcon.alt = 'Unknown File';
          fileDiv.appendChild(fileIcon);
          fileDiv.appendChild(fileNameElement);

          fileDiv.addEventListener('dblclick', () => {
          // Create an anchor element for downloading
          const downloadLink = document.createElement('a');
          downloadLink.href = `http://localhost/MyMCE/file-web/CDN/${filePath}`;
          downloadLink.click(); // Triggers a download; you can specify a filename if needed
          });
        }

        // Add double-click event for renaming
        fileNameElement.addEventListener('dblclick', (event) => {
          // Stop propagation to prevent triggering folder opening
          event.stopPropagation();
          renameItem(event, filePath, file.type === 'folder', folderPath);
        });

        gallery.appendChild(fileDiv);
      });

      if (folderPath) {
        const backButton = document.createElement('button');
        backButton.classList.add('back-button');
        backButton.innerHTML = `<img src="imageIcons/back.png" alt="Back">`;
        backButton.addEventListener('click', () => {
          const parentFolderPath = folderPath.split('/').slice(0, -1).join('/');
          loadGallery(parentFolderPath);
        });
        gallery.appendChild(backButton);
      }
    })
    .catch(error => {
      console.error('Error loading gallery:', error);
    });
}


function renameItem(event, fullPath, isFolder, folderPath) {
    const galleryItem = event.target.closest('.gallery-item');
    const fileNameElement = galleryItem.querySelector('p');
    const oldName = fileNameElement.textContent;
    const newName = prompt('Enter the new name:', oldName);

    if (newName && newName !== oldName) {
        // Update the name displayed in the gallery
        fileNameElement.textContent = newName;

        fetch('rename_item.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded'
            },
            body: `oldPath=${encodeURIComponent(fullPath)}&newName=${encodeURIComponent(newName)}&isFolder=${isFolder}`
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                alert('Item renamed successfully!');
                loadGallery(folderPath); // Refresh the gallery to reflect the new name
            } else {
                alert('Failed to rename item.');
                fileNameElement.textContent = oldName; // Revert the name on failure
            }
        })
        .catch(error => {
            console.error('Error renaming item:', error);
            alert('An error occurred while renaming the item.');
            fileNameElement.textContent = oldName; // Revert the name on error
        });
    }
}
// Load initial gallery content
document.addEventListener('DOMContentLoaded', () => {
  loadGallery();
  // list_files.php
});
</script>
</body>
</html>