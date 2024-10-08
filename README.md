<h1>PHP Editor</h1>

<p><strong>Checkout my open-source web Editor!</strong> It's a robust tool for PHP developers that automatically nests all files starting from <code>index.php</code>. Developed since 2006, it supports PHP5, ensuring compatibility with older projects.</p>

<hr>

<h2>Overview</h2>
<p><strong>PHP Editor</strong> is an open-source, web-based editor that allows you to view and edit PHP source code recursively, including all included or required files. This tool is designed to help developers navigate and modify their code efficiently, streamlining the development process.</p>

<hr>

<h2>Features</h2>
<ul>
    <li><strong>Automatic File Nesting:</strong> Detects and nests all files starting from <code>index.php</code>, providing a comprehensive view of your project structure.</li>
    <li><strong>PHP5 Compatibility:</strong> Supports PHP5, making it ideal for maintaining legacy projects.</li>
    <li><strong>User-Friendly Interface:</strong> Intuitive web-based interface powered by Ace Editor for enhanced code readability and editing.</li>
    <li><strong>File Navigation:</strong> Easily browse through your project's file structure and access files with a single click.</li>
    <li><strong>Save Functionality:</strong> Edit and save files directly from the browser with options to preserve file modification dates.</li>
    <li><strong>Copy to Clipboard:</strong> Quickly copy code along with essential file and system information, including filename, directory, PHP version, and SQL version.</li>
    <li><strong>Keyboard Shortcuts:</strong> Use <code>Ctrl+S</code> (or <code>Cmd+S</code> on macOS) to save files swiftly without navigating through the interface.</li>
    <li><strong>Responsive Design:</strong> Optimized layout for various screen sizes, ensuring usability across devices.</li>
    <li><strong>Open-Source License:</strong> Released under the MIT License, promoting collaboration and free use.</li>
</ul>

<hr>

<h2>Installation</h2>
<p>Follow these steps to set up the PHP Editor on your server:</p>

<h3>1. Clone the Repository</h3>
<pre><code>git clone https://github.com/putout/php-editor.git</code></pre>

<h3>2. Navigate to the Directory</h3>
<pre><code>cd php-editor</code></pre>

<h3>3. Set Up Permissions</h3>
<p>Ensure that the web server has read and write permissions for the files you intend to edit.</p>
<pre><code>sudo chown -R www-data:www-data /path/to/php-editor
sudo chmod -R 755 /path/to/php-editor
</code></pre>
<p><em>Replace <code>/path/to/php-editor</code> with the actual path to your project directory.</em></p>

<h3>4. Configure SQL Version Retrieval (Optional)</h3>
<p>If you wish to display the SQL version, uncomment and configure the database connection section in the PHP code (<code>index.php</code>).</p>
<pre><code>/*
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
</code></pre>
<p><em>Replace the placeholder values with your actual database credentials.</em></p>

<hr>

<h2>Usage</h2>

<h3>1. Access the Editor</h3>
<p>Open <code>index.php</code> in your web browser.</p>
<pre><code>http://your-domain.com/php-editor/index.php</code></pre>

<h3>2. Navigate Files</h3>
<ul>
    <li><strong>File Tree:</strong> Use the file structure pane on the left to browse and select files.</li>
    <li><strong>Previous/Next Buttons:</strong> Navigate through files sequentially using the "Prev" and "Next" buttons.</li>
    <li><strong>Filename Textbox:</strong> Enter the full relative path of a file in the textbox and click "Go" to navigate directly.</li>
</ul>

<h3>3. Edit and Save Files</h3>
<ul>
    <li><strong>Editing:</strong> Make changes to your PHP files in the Ace Editor on the right.</li>
    <li><strong>Saving:</strong>
        <ul>
            <li>Click the <strong>Save</strong> button.</li>
            <li>Or use the <strong>Ctrl+S</strong> (or <strong>Cmd+S</strong> on macOS) keyboard shortcut to save changes.</li>
        </ul>
    </li>
    <li><strong>Preserve Modification Date:</strong> Optionally, check the "Preserve Mod Date" checkbox to save the file without updating its modification timestamp.</li>
</ul>

<h3>4. Copy to Clipboard</h3>
<p>Click the <strong>Copy</strong> button to copy the code along with file and system information. The copied text includes:</p>
<pre><code>#filename: example.php
#directory: /path/to/directory
#phpversion: 7.4.3
#sqlversion: 5.7.31

// Your PHP code here...
</code></pre>

<hr>

<h2>Screenshots</h2>
<p><em>Include screenshots or GIFs demonstrating the editor interface, file navigation, editing, and other features.</em></p>
<!-- Example Screenshot -->
<!-- <img src="path-to-your-screenshot.png" alt="PHP Editor Interface" class="screenshot"> -->

<hr>

<h2>Credits</h2>
<p>
    Made for humanity by 
    <a href="https://www.instagram.com/abdoualittlebit" target="_blank">Abdou Traya</a> and 
    <a href="https://www.instagram.com/putout" target="_blank">Abdou Traya</a>.
    <br>
    GitHub: <a href="https://www.github.com/putout" target="_blank">putout</a>
</p>

<hr>

<h2>License</h2>
<p>This project is licensed under the <a href="LICENSE" target="_blank">MIT License</a>.</p>

<hr>

<h2>Security Considerations</h2>
<p><strong>Important:</strong> Exposing a web-based file editor can pose significant security risks. It's crucial to implement proper authentication and access controls to prevent unauthorized access and modifications.</p>

<h3>Implement Authentication</h3>
<ul>
    <li><strong>HTTP Basic Authentication:</strong> Protect the editor using <code>.htaccess</code> and <code>.htpasswd</code>.</li>
    <li><strong>Session-Based Login:</strong> Develop a secure login system using PHP sessions.</li>
</ul>

<h3>Restrict File Access</h3>
<ul>
    <li>Ensure that the script only allows access to files within the intended directory structure.</li>
    <li>Sanitize all user inputs to prevent directory traversal and other attacks.</li>
</ul>

<h3>Secure Data Transmission</h3>
<ul>
    <li>Use HTTPS to encrypt data transmitted between the client and server, protecting against eavesdropping.</li>
</ul>

<h3>Regular Backups</h3>
<ul>
    <li>Maintain regular backups of your files to recover from accidental deletions or unauthorized modifications.</li>
</ul>

<h3>Monitor and Log Activity</h3>
<ul>
    <li>Implement logging to monitor access and changes made through the editor.</li>
</ul>

<hr>

<h2>Contributing</h2>
<p>Contributions are welcome! Please follow these steps to contribute:</p>

<h3>1. Fork the Repository</h3>
<p>Click the "Fork" button at the top-right corner of the repository page.</p>

<h3>2. Clone Your Fork</h3>
<pre><code>git clone https://github.com/putout/php-editor.git</code></pre>

<h3>3. Create a Branch</h3>
<pre><code>git checkout -b feature/YourFeatureName</code></pre>

<h3>4. Make Your Changes</h3>
<p>Implement your feature or fix.</p>

<h3>5. Commit Your Changes</h3>
<pre><code>git commit -m "Add feature: YourFeatureName"</code></pre>

<h3>6. Push to Your Fork</h3>
<pre><code>git push origin feature/YourFeatureName</code></pre>

<h3>7. Create a Pull Request</h3>
<p>Navigate to the original repository and create a pull request from your fork.</p>

<hr>

<h2>Issues</h2>
<p>If you encounter any issues or have suggestions for improvements, please <a href="https://github.com/putout/php-editor/issues" target="_blank">open an issue</a> in the repository.</p>

<hr>

<h2>Contact</h2>
<p>For any questions or contributions, feel free to reach out via:</p>
<ul>
    <li><a href="https://www.instagram.com/abdoualittlebit" target="_blank">Instagram (@abdoualittlebit)</a></li>
    <li><a href="https://www.instagram.com/putout" target="_blank">Instagram (@putout)</a></li>
</ul>

<hr>

<div>
    <p>Made for humanity by Abdou Traya.</p>
</div>
