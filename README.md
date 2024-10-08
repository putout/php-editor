PHP Editor

Checkout my open-source web Editor! It's a robust tool for PHP developers that automatically nests all files starting from index.php. Developed since 2006, it supports PHP5, ensuring compatibility with older projects.

Overview
PHP Editor is an open-source, web-based editor that allows you to view and edit PHP source code recursively, including all included or required files. This tool is designed to help developers navigate and modify their code efficiently, streamlining the development process.

Features
Automatic File Nesting: Detects and nests all files starting from index.php, providing a comprehensive view of your project structure.
PHP5 Compatibility: Supports PHP5, making it ideal for maintaining legacy projects.
User-Friendly Interface: Intuitive web-based interface powered by Ace Editor for enhanced code readability and editing.
File Navigation: Easily browse through your project's file structure and access files with a single click.
Save Functionality: Edit and save files directly from the browser with options to preserve file modification dates.
Copy to Clipboard: Quickly copy code along with essential file and system information, including filename, directory, PHP version, and SQL version.
Keyboard Shortcuts: Use Ctrl+S (or Cmd+S on macOS) to save files swiftly without navigating through the interface.
Responsive Design: Optimized layout for various screen sizes, ensuring usability across devices.
Open-Source License: Released under the MIT License, promoting collaboration and free use.
Installation
Follow these steps to set up the PHP Editor on your server:

Clone the Repository:

bash
Copy code
git clone https://github.com/your-username/php-editor.git
Navigate to the Directory:

bash
Copy code
cd php-editor
Set Up Permissions:

Ensure that the web server has read and write permissions for the files you intend to edit.

bash
Copy code
sudo chown -R www-data:www-data /path/to/php-editor
sudo chmod -R 755 /path/to/php-editor
Replace /path/to/php-editor with the actual path to your project directory.

Configure SQL Version Retrieval (Optional):

If you wish to display the SQL version, uncomment and configure the database connection section in the PHP code (index.php).

php
Copy code
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
Replace the placeholder values with your actual database credentials.

Usage
Access the Editor:

Open index.php in your web browser.

arduino
Copy code
http://your-domain.com/php-editor/index.php
Navigate Files:

File Tree: Use the file structure pane on the left to browse and select files.
Previous/Next Buttons: Navigate through files sequentially using the "Prev" and "Next" buttons.
Filename Textbox: Enter the full relative path of a file in the textbox and click "Go" to navigate directly.
Edit and Save Files:

Editing: Make changes to your PHP files in the Ace Editor on the right.
Saving:
Click the Save button.
Or use the Ctrl+S (or Cmd+S on macOS) keyboard shortcut to save changes.
Preserve Modification Date: Optionally, check the "Preserve Mod Date" checkbox to save the file without updating its modification timestamp.
Copy to Clipboard:

Click the Copy button to copy the code along with file and system information.
The copied text includes:
less
Copy code
#filename: example.php
#directory: /path/to/directory
#phpversion: 7.4.3
#sqlversion: 5.7.31
 
// Your PHP code here...
Screenshots
Include screenshots or GIFs demonstrating the editor interface, file navigation, editing, and other features.

Credits
Made for humanity by Abdou Traya and Abdou Traya.

License
This project is licensed under the MIT License.

Security Considerations
Important: Exposing a web-based file editor can pose significant security risks. It's crucial to implement proper authentication and access controls to prevent unauthorized access and modifications.

Implement Authentication:

HTTP Basic Authentication: Protect the editor using .htaccess and .htpasswd.
Session-Based Login: Develop a secure login system using PHP sessions.
Restrict File Access:

Ensure that the script only allows access to files within the intended directory structure.
Sanitize all user inputs to prevent directory traversal and other attacks.
Secure Data Transmission:

Use HTTPS to encrypt data transmitted between the client and server, protecting against eavesdropping.
Regular Backups:

Maintain regular backups of your files to recover from accidental deletions or unauthorized modifications.
Monitor and Log Activity:

Implement logging to monitor access and changes made through the editor.
Contributing
Contributions are welcome! Please follow these steps to contribute:

Fork the Repository:

Click the "Fork" button at the top-right corner of the repository page.

Clone Your Fork:

bash
Copy code
git clone https://github.com/your-username/php-editor.git
Create a Branch:

bash
Copy code
git checkout -b feature/YourFeatureName
Make Your Changes:

Implement your feature or fix.

Commit Your Changes:

bash
Copy code
git commit -m "Add feature: YourFeatureName"
Push to Your Fork:

bash
Copy code
git push origin feature/YourFeatureName
Create a Pull Request:

Navigate to the original repository and create a pull request from your fork.

Issues
If you encounter any issues or have suggestions for improvements, please open an issue in the repository.

Contact
For any questions or contributions, feel free to reach out via Instagram (@abdoualittlebit) or Instagram (@putout).
