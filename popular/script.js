document.addEventListener('DOMContentLoaded', () => {
  console.log('JavaScript file loaded');
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
