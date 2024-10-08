<?php
/**
 * PHP Editor
 * 
 * An open-source, web-based PHP editor designed to help developers efficiently navigate, view, and edit PHP source code. 
 * Automatically nests all files starting from `index.php`, supporting PHP5 to ensure compatibility with legacy projects.
 * 
 * Features:
 * - **Recursive File Management:** Automatically detects and nests included or required files from `index.php`.
 * - **PHP5 Compatibility:** Ensures functionality with older PHP versions, making it ideal for maintaining legacy systems.
 * - **User-Friendly Interface:** Powered by Ace Editor for enhanced code readability and editing capabilities.
 * - **Save with Shortcuts:** Easily save changes using the **Ctrl+S** (or **Cmd+S** on macOS) keyboard shortcut.
 * - **Comprehensive Copy Functionality:** Copy code along with essential file and system information, including filename, directory, PHP version, and SQL version.
 * 
 * Made for humanity by Abdou Traya https://www.instagram.com/abdoualittlebit  https://www.instagram.com/putout github.com/putout 
 * 
 * @license MIT
 * @version 1.1
 */

 // Define the base directory as the current directory
 $document_root = realpath(__DIR__) . '/'; // Ensure it ends with a slash

 // Function to get source code of a file and any included/required files
 function get_source_code($file, &$all_source_codes = array(), &$file_tree = array(), $parent = null, &$processed_files = array()) {
     global $document_root;

     // Normalize file path
     $file = realpath($file);
     if ($file === false) {
         return;
     }

     // Check if the file has already been processed
     if (isset($processed_files[$file])) {
         return; // If file is already processed, don't include it again
     }

     if (!file_exists($file)) {
         return; // If file does not exist, skip processing
     }

     // Mark the file as processed
     $processed_files[$file] = true;

     // Read the content of the current file
     $content = file_get_contents($file);
     if ($content === false) {
         return; // If unable to read the file, skip
     }

     // Add file info to the tree
     if ($parent === null) {
         $file_tree[$file] = array();
     } else {
         if (!isset($file_tree[$parent])) {
             $file_tree[$parent] = array();
         }
         $file_tree[$parent][] = $file;
     }

     // Store source code for the current file
     $all_source_codes[$file] = $content;

     // Use regex to find include/include_once/require/require_once statements
     $pattern = '/\b(include|include_once|require|require_once)\s*(\(?\s*[\'"])([^\'"]+)([\'"]\s*\)?\s*);/i';
     preg_match_all($pattern, $content, $matches);

     // Recursively fetch the included/required files' source code
     foreach ($matches[3] as $included_file) {
         // Resolve relative paths
         $included_file_path = dirname($file) . '/' . $included_file;
         $included_file_path = realpath($included_file_path);
         if ($included_file_path !== false && strpos($included_file_path, $document_root) === 0) {
             get_source_code($included_file_path, $all_source_codes, $file_tree, $file, $processed_files);
         }
     }
 }

 // Initialize the arrays to store source codes, file tree structure, and processed files
 $all_source_codes = array();
 $file_tree = array();
 $processed_files = array();

 // Starting file
 $starting_file = $document_root . 'index.php'; // Adjust if your starting file is different

 // Fetch the source code and file structure recursively
 get_source_code($starting_file, $all_source_codes, $file_tree, null, $processed_files);

 // Split the source code into sections, one file per section
 $sections = array_keys($all_source_codes);

 // Create a mapping from relative file paths to absolute paths
 $file_path_map = array();
 foreach ($sections as $abs_path) {
     $relative_path = substr($abs_path, strlen($document_root));
     $file_path_map[$relative_path] = $abs_path;
 }

 // Get the current file from the GET parameter
 $current_file_relative = isset($_GET['file']) ? $_GET['file'] : substr($starting_file, strlen($document_root));
 $current_file_absolute = isset($file_path_map[$current_file_relative]) ? $file_path_map[$current_file_relative] : $starting_file;

 // Validate that the file exists in our list
 if (!isset($all_source_codes[$current_file_absolute])) {
     $current_file_absolute = $starting_file;
     $current_file_relative = substr($starting_file, strlen($document_root));
 }

 // Function to build file structure tree with clickable links
 function build_file_tree($file_tree, $parent = null, $depth = 0, $current_file_absolute = null) {
     global $document_root;
     $indentation = str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth);
     $output = '';
     $children = $parent === null ? array_keys($file_tree) : (isset($file_tree[$parent]) ? $file_tree[$parent] : array());

     foreach ($children as $file) {
         // Get relative path
         $relative_path = substr($file, strlen($document_root));
         // Highlight the selected file
         $style = ($file === $current_file_absolute) ? "font-weight:bold;background-color:#555;padding:2px;border-radius:3px;color:#fff;" : "color:#7ecbff;";
         $output .= $indentation . "|-- <a href='?file=" . urlencode($relative_path) . "' style='" . $style . "'>" . htmlspecialchars($relative_path) . "</a><br>\n";
         if (isset($file_tree[$file])) {
             $output .= build_file_tree($file_tree, $file, $depth + 1, $current_file_absolute);
         }
     }
     return $output;
 }

 $file_structure = build_file_tree($file_tree, null, 0, $current_file_absolute);

 // Handle form submission to save the edited file
 if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action']) && $_POST['action'] === 'save_file') {
     // Get the edited code
     $edited_code = isset($_POST['sourceCode']) ? $_POST['sourceCode'] : '';

     // Define the file to save
     $file_to_save = $current_file_absolute;

     // Option to preserve modification date
     $preserve_mod_date = isset($_POST['preserveModDate']) && $_POST['preserveModDate'] === 'on';

     // Get the current modification time
     $original_mod_time = filemtime($file_to_save);

     // Check if the file is writable
     if (is_writable($file_to_save)) {
         // Save the edited code
         $result = file_put_contents($file_to_save, $edited_code);
         if ($result !== false) {
             // Preserve modification date if the option is selected
             if ($preserve_mod_date) {
                 touch($file_to_save, $original_mod_time);
             }
             // Send success response
             echo json_encode(array('status' => 'success', 'message' => 'File saved successfully.'));
             exit();
         } else {
             echo json_encode(array('status' => 'error', 'message' => 'Error: Unable to save the file.'));
             exit();
         }
     } else {
         echo json_encode(array('status' => 'error', 'message' => 'Error: The file is not writable.'));
         exit();
     }
 }

 // Get PHP version
 $php_version = phpversion();

 // Get Operating System
 $os_info = php_uname();

 // Get SQL version (attempt to connect and retrieve version)
 $sql_version = 'N/A'; // Default value
 // Uncomment and configure the following lines if you have a database connection
 /*
 $servername = "localhost";
 $username = "username";
 $password = "password";
 $dbname = "database";

 // Create connection
 $conn = new mysqli($servername, $username, $password, $dbname);
 // Check connection
 if ($conn->connect_error) {
     $sql_version = 'Connection failed: ' . $conn->connect_error;
 } else {
     $sql_version = $conn->server_info;
     $conn->close();
 }
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($current_file_relative); ?> - Source Code Editor</title>
    <!-- Include Ace Editor -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.11.1/ace.js" crossorigin="anonymous"></script>
    <style>
        /* Reset some default styles */
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background-color: #121212;
            color: #e0e0e0;
            height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .header {
            padding: 5px 10px;
            background-color: #1e1e1e;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
        }

        .header-left, .header-right {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .header-left {
            flex: 1;
        }

        .header-right {
            flex: 2;
            justify-content: flex-end;
        }

        .buttons {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
        }

        .buttons form {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            margin-right: 10px;
        }

        .buttons button, .buttons input[type="button"] {
            background-color: #444;
            color: #fff;
            border: none;
            padding: 5px 10px;
            margin-right: 5px;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 5px;
            font-size: 0.9em;
        }

        .buttons button:hover, .buttons input[type="button"]:hover {
            background-color: #555;
        }

        .file-info {
            display: flex;
            flex-direction: column;
            align-items: flex-end;
            margin-top: 5px;
            width: 100%;
        }

        .file-info .file-name {
            font-weight: bold;
            font-size: 0.9em;
            word-break: break-all;
        }

        .file-info .mod-date {
            font-size: 0.8em;
            color: #ccc;
        }

        .file-info .system-info {
            font-size: 0.7em;
            color: #999;
            margin-top: 5px;
        }

        .file-selector {
            display: flex;
            align-items: center;
            flex-wrap: wrap;
            margin-top: 5px;
            width: 100%;
        }

        .file-selector input {
            padding: 5px;
            width: 300px;
            max-width: 100%;
            border: 1px solid #444;
            border-radius: 4px;
            background-color: #2a2a2a;
            color: #fff;
            margin-right: 5px;
            margin-top: 5px;
            word-break: break-all;
            font-size: 0.9em;
        }

        .file-selector button {
            padding: 5px 10px;
            border: none;
            border-radius: 4px;
            background-color: #444;
            color: #fff;
            cursor: pointer;
            margin-top: 5px;
            font-size: 0.9em;
        }

        .file-selector button:hover {
            background-color: #555;
        }

        .preserve-mod-date {
            display: flex;
            align-items: center;
            margin-right: 10px;
            margin-top: 5px;
        }

        .preserve-mod-date input {
            margin-right: 5px;
        }

        .copy-button {
            background-color: #444;
            color: #fff;
            border: none;
            padding: 5px 10px;
            cursor: pointer;
            border-radius: 4px;
            margin-top: 5px;
            font-size: 0.9em;
        }

        .copy-button:hover {
            background-color: #555;
        }

        .main {
            flex: 1;
            display: flex;
            overflow: hidden;
        }

        .file-structure {
            width: 250px;
            background-color: #1e1e1e;
            padding: 10px;
            overflow-y: auto;
            border-right: 1px solid #333;
        }

        .file-structure h2 {
            margin-top: 0;
            color: #fff;
            font-size: 1em;
            text-align: center;
            border-bottom: 1px solid #444;
            padding-bottom: 5px;
        }

        .file-structure pre {
            white-space: pre-wrap;
            word-wrap: break-word;
            font-family: monospace;
            font-size: 0.85em;
            margin: 0;
        }

        .editor-container {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        #editor {
            flex: 1;
            font-family: monospace;
            height: 100%;
        }

        a {
            text-decoration: none;
        }

        a:hover {
            text-decoration: underline;
        }

        @media (max-width: 768px) {
            .file-structure {
                width: 200px;
            }
            .file-selector input {
                width: 150px;
            }
        }
    </style>
</head>
<body>

    <div class="header">
        <div class="header-left">
            <div class="buttons">
                <form id="saveForm" method="post" style="display: inline;">
                    <input type="hidden" name="action" value="save_file">
                    <div class="preserve-mod-date">
                        <input type="checkbox" id="preserveModDate" name="preserveModDate">
                        <label for="preserveModDate" style="font-size: 0.9em;">Preserve Mod Date</label>
                    </div>
                    <button type="submit">Save</button>
                </form>
                <button onclick="navigateToFile('<?php echo addslashes($prevFile); ?>')">Prev</button>
                <button onclick="navigateToFile('<?php echo addslashes($nextFile); ?>')">Next</button>
                <button class="copy-button" onclick="copyToClipboard()">Copy</button>
            </div>
        </div>
        <div class="header-right">
            <div class="file-info">
                <div class="file-selector">
                    <input type="text" id="fileInput" value="<?php echo htmlspecialchars($current_file_relative); ?>" placeholder="Enter full file path">
                    <button onclick="goToFile()">Go</button>
                </div>
                <div class="file-name">Path: <?php echo htmlspecialchars($current_file_absolute); ?></div>
                <div class="mod-date">Modified: <?php echo date("F d Y H:i:s", filemtime($current_file_absolute)); ?></div>
                <div class="system-info">
                    PHP: <?php echo htmlspecialchars($php_version); ?> | 
                    SQL: <?php echo htmlspecialchars($sql_version); ?> | 
                    OS: <?php echo htmlspecialchars($os_info); ?>
                </div>
            </div>
        </div>
    </div>

    <div class="main">
        <div class="file-structure" tabindex="0" id="file-structure">
            <h2>File Structure</h2>
            <!-- Output the file structure without htmlspecialchars -->
            <pre><?php echo $file_structure; ?></pre>
        </div>
        <div class="editor-container">
            <div id="editor"></div>
        </div>
    </div>

    <script>
        // List of files for navigation
        var files = <?php echo json_encode(array_keys($file_path_map)); ?>;
        var currentFile = '<?php echo addslashes($current_file_relative); ?>';
        var currentIndex = files.indexOf(currentFile);
        var prevFile = files[(currentIndex - 1 + files.length) % files.length];
        var nextFile = files[(currentIndex + 1) % files.length];

        // Initialize Ace Editor
        var editor = ace.edit("editor");
        editor.setTheme("ace/theme/monokai");
        // Set mode based on file extension
        var fileName = "<?php echo basename($current_file_relative); ?>";
        var mode = "ace/mode/php"; // default
        if (fileName.endsWith('.js')) {
            mode = "ace/mode/javascript";
        } else if (fileName.endsWith('.css')) {
            mode = "ace/mode/css";
        } else if (fileName.endsWith('.html') || fileName.endsWith('.htm')) {
            mode = "ace/mode/html";
        } else if (fileName.endsWith('.json')) {
            mode = "ace/mode/json";
        } else if (fileName.endsWith('.txt')) {
            mode = "ace/mode/text";
        }
        editor.session.setMode(mode);
        editor.setOptions({
            fontSize: "14px",
            showLineNumbers: true,
            showGutter: true,
            vScrollBarAlwaysVisible: true,
            enableBasicAutocompletion: true,
            enableLiveAutocompletion: true
        });

        // Set the editor content with proper line breaks
        var codeContent = <?php echo json_encode($all_source_codes[$current_file_absolute]); ?>;
        editor.setValue(codeContent, -1); // The second parameter moves cursor to the start

        // Handle Save button click
        document.getElementById('saveForm').addEventListener('submit', function(e) {
            e.preventDefault();
            // Submit the form with the edited code via AJAX
            var xhr = new XMLHttpRequest();
            xhr.open('POST', window.location.href, true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
            xhr.onreadystatechange = function() {
                if (xhr.readyState === 4) {
                    try {
                        var response = JSON.parse(xhr.responseText);
                        alert(response.message);
                        if (response.status === 'success') {
                            // Update the modification date without reloading
                            var now = new Date();
                            var options = { year: 'numeric', month: 'long', day: 'numeric', hour: '2-digit', minute:'2-digit', second:'2-digit' };
                            document.querySelector('.mod-date').textContent = 'Modified: ' + now.toLocaleDateString("en-US", options);
                        }
                    } catch (e) {
                        alert('An error occurred while saving the file.');
                    }
                }
            };
            var code = encodeURIComponent(editor.getValue());
            var preserveModDate = document.getElementById('preserveModDate').checked ? 'on' : 'off';
            xhr.send('action=save_file&sourceCode=' + code + '&preserveModDate=' + preserveModDate);
        });

        // Implement Ctrl+S to save the file
        document.addEventListener('keydown', function(e) {
            if ((e.ctrlKey || e.metaKey) && e.key === 's') {
                e.preventDefault();
                document.getElementById('saveForm').dispatchEvent(new Event('submit'));
            }
        });

        // Function to navigate to a specific file
        function navigateToFile(file) {
            window.location.href = '?file=' + encodeURIComponent(file);
        }

        // Function to go to the file entered in the textbox
        function goToFile() {
            var file = document.getElementById('fileInput').value.trim();
            if (files.includes(file)) {
                navigateToFile(file);
            } else {
                alert('File not found in the current file structure.');
            }
        }

        // Function to copy to clipboard with file info, PHP version, SQL version, and code
        function copyToClipboard() {
            var fileName = '<?php echo addslashes($current_file_relative); ?>';
            var directory = '<?php echo addslashes(dirname($current_file_absolute)); ?>';
            var phpVersion = '<?php echo addslashes($php_version); ?>';
            var sqlVersion = '<?php echo addslashes($sql_version); ?>';
            var code = editor.getValue();

            var copyText = "#filename: " + fileName + "\n#directory: " + directory + "\n#phpversion: " + phpVersion + "\n#sqlversion: " + sqlVersion + "\n\n" + code;

            // Create a temporary textarea to copy the text
            var tempTextarea = document.createElement('textarea');
            tempTextarea.value = copyText;
            document.body.appendChild(tempTextarea);
            tempTextarea.select();
            try {
                var successful = document.execCommand('copy');
                if (successful) {
                    alert('Code copied to clipboard.');
                } else {
                    alert('Failed to copy code.');
                }
            } catch (err) {
                alert('Browser does not support copy to clipboard.');
            }
            document.body.removeChild(tempTextarea);
        }

        // Keyboard navigation
        document.addEventListener('keydown', function(event) {
            var activeElement = document.activeElement;
            // If not focused on the editor or input fields
            if (activeElement !== editor.textInput.getElement() && activeElement.tagName !== 'INPUT') {
                if (event.key === 'ArrowRight') {
                    navigateToFile(nextFile);
                } else if (event.key === 'ArrowLeft') {
                    navigateToFile(prevFile);
                }
            }
        });
    </script>
</body>
</html>
