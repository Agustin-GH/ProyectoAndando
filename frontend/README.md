# Frontend - DinoKing Games

This is the frontend web application for DinoKing Games, a digital adaptation of board games.

## Structure

```
frontend/
├── index.html                   # Home page (landing)
├── assets/                      # Static assets
│   ├── css/                     # Stylesheets
│   │   ├── normalize.css
│   │   └── styles.css
│   ├── js/                      # JavaScript files
│   │   ├── auth.js              # Authentication UI logic
│   │   ├── admin.js             # Admin panel logic
│   │   ├── main.js              # Game logic
│   │   └── seguimiento.js       # Score tracker logic
│   └── imgs/                    # Images and icons
├── pages/                       # HTML pages
│   ├── jugar.html               # Game page
│   ├── admin.html               # Admin panel
│   ├── seguimiento.html         # Score tracker
│   ├── nosotros.html            # About us
│   ├── contacto.html            # Contact form
│   └── proximamente.html        # Upcoming games
├── components/                  # Reusable HTML components
│   ├── header.html              # Main header
│   ├── header_simple.html       # Simple header
│   ├── footer.html              # Footer
│   └── auth-modals.html         # Login/Register modals
├── services/                    # API integration
│   └── api.js                   # API client service
└── README.md                    # This file
```

## Features

### Pages

1. **Home (`index.html`)** - Landing page with game introduction
2. **Jugar (`pages/jugar.html`)** - Draftosaurus game interface
3. **Admin (`pages/admin.html`)** - User and role management (admin only)
4. **Seguimiento (`pages/seguimiento.html`)** - Score tracking application
5. **Nosotros (`pages/nosotros.html`)** - About the team
6. **Contacto (`pages/contacto.html`)** - Contact form
7. **Próximamente (`pages/proximamente.html`)** - Upcoming games

### Components

Components are loaded dynamically using JavaScript:
- Headers provide navigation and user account menu
- Footer contains links and branding
- Auth modals handle login and registration

### Services

The `api.js` service provides a centralized way to communicate with the backend:

```javascript
// Example usage
await API.auth.login({ email, password });
await API.admin.listUsers();
```

## Development

### Local Development

1. **Serve the frontend:**
   ```bash
   # Using Python
   cd frontend
   python -m http.server 8080
   
   # Or using PHP
   php -S localhost:8080
   ```

2. **Access the application:**
   ```
   http://localhost:8080
   ```

### API Configuration

The API base URL is configured in `services/api.js`:

```javascript
const API_CONFIG = {
    baseURL: '/backend/api.php'  // Adjust for your setup
};
```

For production, update this URL to point to your backend server.

## Architecture

### Separation of Concerns

- **No PHP in HTML** - All pages are pure HTML/CSS/JavaScript
- **Component-based** - Reusable components loaded dynamically
- **API-driven** - All backend communication through REST API
- **Session Management** - Authentication state managed via API

### Component Loading

Components are loaded dynamically when the page loads:

```javascript
await loadComponent('header-container', '../components/header.html');
```

This approach:
- Keeps code DRY (Don't Repeat Yourself)
- Makes updates easier (change once, update everywhere)
- Maintains clean separation from backend

### Authentication Flow

1. User clicks "Iniciar sesión" in header
2. Modal opens (loaded from `components/auth-modals.html`)
3. Form submission handled by `assets/js/auth.js`
4. API call made via `services/api.js`
5. On success, page reloads and header updates to show user info

## Browser Support

- Modern browsers (Chrome, Firefox, Safari, Edge)
- ES6+ JavaScript features used
- Fetch API for HTTP requests
- No external dependencies (pure vanilla JavaScript)

## Notes

- All paths use relative URLs for portability
- Assets are referenced relative to the page location
- Session state is fetched from backend on page load
- No build process required - runs directly in browser
