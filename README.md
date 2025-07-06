# HCJ Code Editor - Professional Web-Based Code Editor

A modern, professional web-based code editor for HTML, CSS, and JavaScript with live preview functionality, user authentication, and advanced features.

## 🚀 Features

### Core Features
- **Multi-language Support**: HTML, CSS, and JavaScript editing
- **Live Preview**: Real-time preview with device simulation (Desktop, Tablet, Mobile)
- **User Authentication**: Secure login/signup system with session management
- **File Management**: Download individual files or complete projects
- **Line Numbers**: Professional code editor with line numbering
- **Syntax Highlighting**: Basic syntax highlighting for better code readability

### Advanced Features
- **Auto-save**: Automatic saving to localStorage
- **Undo/Redo**: Full undo/redo functionality with history
- **Code Formatting**: Auto-format HTML, CSS, and JavaScript
- **Search & Replace**: Find and replace functionality
- **Keyboard Shortcuts**: Professional keyboard shortcuts
- **Auto-completion**: Smart auto-completion for tags and brackets
- **Error Detection**: Real-time syntax error detection
- **Responsive Design**: Works perfectly on all devices

### Security Features
- **CSRF Protection**: Cross-Site Request Forgery protection
- **Input Sanitization**: Proper input validation and sanitization
- **Secure Sessions**: Secure session management
- **Password Hashing**: Bcrypt password hashing

## 📁 Project Structure

```
code-editor/
├── config/
│   ├── database.php          # Database configuration and connection
│   └── app.php              # Application configuration and utilities
├── includes/
│   ├── header.php           # Common header template
│   └── footer.php           # Common footer template
├── auth/
│   ├── login.php            # Login page
│   ├── signup.php           # Registration page
│   ├── logout.php           # Logout handler
│   └── forgot-password.php  # Password reset page
├── assets/
│   ├── css/
│   │   ├── main.css         # Main application styles
│   │   └── auth.css         # Authentication pages styles
│   ├── js/
│   │   ├── app.js           # Main application JavaScript
│   │   ├── editor.js        # Enhanced editor functionality
│   │   ├── preview-controls.js # Preview management
│   │   └── download-manager.js # File download management
│   └── img/                 # Images and icons
├── vendor/                  # Third-party libraries (Bootstrap, etc.)
├── database/
│   └── schema.sql          # Database schema
├── old-files/              # Backup of old files
├── index.php               # Main application page
└── README.md               # This file
```

## 🛠️ Installation

### Prerequisites
- PHP 7.4 or higher
- MySQL 5.7 or higher
- Web server (Apache/Nginx)
- Modern web browser

### Setup Steps

1. **Clone or download** the project to your web server directory
2. **Import the database schema**:
   ```sql
   mysql -u your_username -p < database/schema.sql
   ```
3. **Configure database connection** in `config/database.php`:
   ```php
   private $host = 'localhost';
   private $dbname = 'hcjcode_db';
   private $username = 'your_username';
   private $password = 'your_password';
   ```
4. **Set proper permissions** for the web server
5. **Access the application** through your web browser

### Database Setup
The application requires a MySQL database. Run the SQL commands in `database/schema.sql` to create the necessary tables:

- `users` - User accounts and authentication
- `user_sessions` - Session management (optional)
- `user_projects` - Saved projects (future enhancement)

## 🎯 Usage

### Getting Started
1. **Register** a new account or login with existing credentials
2. **Start coding** in the HTML, CSS, or JavaScript tabs
3. **Preview** your work in real-time in the preview panel
4. **Download** your files when ready

### Keyboard Shortcuts
- `Ctrl+S` - Save project
- `Ctrl+R` - Refresh preview
- `Ctrl+/` - Toggle comment
- `Ctrl+Z` - Undo
- `Ctrl+Y` - Redo
- `Ctrl+F` - Find
- `Ctrl+H` - Replace
- `Ctrl+L` - Go to line
- `Ctrl+D` - Duplicate line
- `Tab` - Indent

### Device Preview
Switch between different device views:
- **Desktop** - Full-width preview
- **Tablet** - 768px width with rounded corners
- **Mobile** - 375px width with mobile styling

## 🔧 Configuration

### Application Settings
Edit `config/app.php` to modify:
- Application name and version
- Security settings
- File upload limits
- Error reporting levels

### Database Settings
Edit `config/database.php` to configure:
- Database connection parameters
- Connection options
- Error handling

## 🚀 Features in Detail

### Code Editor
- **Syntax Highlighting**: Visual code highlighting for better readability
- **Auto-indentation**: Smart indentation based on code structure
- **Bracket Matching**: Automatic bracket and quote completion
- **Line Numbers**: Professional line numbering with sync scrolling
- **Tab Support**: Proper tab handling for code indentation

### Live Preview
- **Real-time Updates**: Instant preview updates as you type
- **Device Simulation**: Preview in different device sizes
- **Error Handling**: Graceful error handling with user feedback
- **Responsive Design**: Preview adapts to different screen sizes

### File Management
- **Individual Downloads**: Download HTML, CSS, or JS files separately
- **Combined Download**: Download complete project as single HTML file
- **Format Options**: Download as code files or plain text
- **Project Export**: Export entire project with metadata

### User Management
- **Secure Authentication**: Bcrypt password hashing
- **Session Management**: Secure session handling
- **CSRF Protection**: Protection against cross-site request forgery
- **Input Validation**: Comprehensive input validation and sanitization

## 🔒 Security

### Implemented Security Measures
- **Password Hashing**: Bcrypt with salt
- **CSRF Tokens**: All forms protected with CSRF tokens
- **Input Sanitization**: All user inputs are sanitized
- **SQL Injection Prevention**: Prepared statements used throughout
- **Session Security**: Secure session configuration
- **XSS Prevention**: Output escaping and content security

### Best Practices
- Regular security updates
- Strong password requirements
- Secure session management
- Input validation on both client and server side

## 🎨 Customization

### Themes
The application uses CSS custom properties for easy theming. Modify the `:root` variables in `assets/css/main.css` to change colors and styling.

### Adding Features
The modular architecture makes it easy to add new features:
1. Add new JavaScript modules in `assets/js/`
2. Create new CSS files in `assets/css/`
3. Add new PHP pages following the existing structure

## 🐛 Troubleshooting

### Common Issues
1. **Database Connection Error**: Check database credentials in `config/database.php`
2. **Permission Denied**: Ensure proper file permissions for web server
3. **JavaScript Errors**: Check browser console for detailed error messages
4. **Preview Not Loading**: Verify iframe security settings

### Debug Mode
Enable debug mode by setting error reporting in `config/app.php`:
```php
error_reporting(E_ALL);
ini_set('display_errors', 1);
```

## 📱 Browser Support

### Supported Browsers
- Chrome 80+
- Firefox 75+
- Safari 13+
- Edge 80+

### Mobile Support
- iOS Safari 13+
- Chrome Mobile 80+
- Samsung Internet 12+

## 🤝 Contributing

### Development Setup
1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Test thoroughly
5. Submit a pull request

### Code Standards
- Follow PSR-12 for PHP code
- Use ES6+ JavaScript features
- Follow BEM methodology for CSS
- Write meaningful commit messages

## 📄 License

This project is licensed under the MIT License - see the LICENSE file for details.

## 🙏 Acknowledgments

- Bootstrap for the UI framework
- SweetAlert for beautiful alerts
- Font Awesome for icons
- Google Fonts for typography

## 📞 Support

For support and questions:
- Create an issue on GitHub
- Check the troubleshooting section
- Review the documentation

---

**Version**: 2.0.0  
**Last Updated**: 2024  
**Author**: HCJ Code Editor Team
