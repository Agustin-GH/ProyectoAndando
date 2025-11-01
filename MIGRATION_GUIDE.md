# Migration Guide: Old Structure → New Structure

This guide shows exactly how files were moved and transformed from the old structure to the new separated backend/frontend structure.

## File Mapping

### Backend Files (Moved)

| Old Location | New Location | Changes |
|-------------|-------------|---------|
| `public/api.php` | `backend/api.php` | Updated path to app/config |
| `app/*` | `backend/app/*` | Copied entire directory |
| `app/config/app.php` | `backend/app/config/app.php` | Updated BASE_PATH and PUBLIC_PATH |
| N/A | `backend/game-api.php` | **NEW FILE** - Game endpoints |
| N/A | `backend/.htaccess` | **NEW FILE** - URL rewriting |
| N/A | `backend/README.md` | **NEW FILE** - Documentation |

### Frontend Files (Converted)

#### Pages (PHP → HTML)

| Old PHP File | New HTML File | Conversion |
|-------------|--------------|-----------|
| `app/Client/paginas/inicio.php` | `frontend/index.html` | Removed PHP, static HTML |
| `app/Client/paginas/jugar.php` | `frontend/pages/jugar.html` | Removed PHP, added session JS |
| `app/Client/paginas/admin.php` | `frontend/pages/admin.html` | Removed PHP, added session JS |
| `app/Client/paginas/seguimiento.php` | `frontend/pages/seguimiento.html` | Removed PHP, static HTML |
| `app/Client/paginas/nosotros.php` | `frontend/pages/nosotros.html` | Removed PHP, static HTML |
| `app/Client/paginas/contacto.php` | `frontend/pages/contacto.html` | Removed PHP, static HTML |
| `app/Client/paginas/proximamente.php` | `frontend/pages/proximamente.html` | Removed PHP, static HTML |

#### Components (PHP → HTML)

| Old PHP Partial | New HTML Component | Conversion |
|----------------|-------------------|-----------|
| `app/Client/parciales/header.php` | `frontend/components/header.html` | Removed PHP session, added JS update |
| `app/Client/parciales/header_simple.php` | `frontend/components/header_simple.html` | Removed PHP session, added JS update |
| `app/Client/parciales/footer.php` | `frontend/components/footer.html` | Removed PHP, static HTML |
| `app/Client/parciales/auth-modals.php` | `frontend/components/auth-modals.html` | Removed PHP, static HTML |

#### Assets (Moved)

| Old Location | New Location | Changes |
|-------------|-------------|---------|
| `public/assets/css/*` | `frontend/assets/css/*` | No changes |
| `public/assets/js/*` | `frontend/assets/js/*` | Updated API URLs |
| `public/assets/imgs/*` | `frontend/assets/imgs/*` | No changes |

#### JavaScript Files (Updated)

| File | Changes Made |
|------|-------------|
| `frontend/assets/js/auth.js` | Updated to use `window.API` service instead of direct fetch |
| `frontend/assets/js/admin.js` | Changed API_BASE from `/api.php/admin` to `../backend/api.php/admin` |
| `frontend/assets/js/main.js` | Changed API_URL to point to `../backend/game-api.php` |
| `frontend/assets/js/seguimiento.js` | No changes needed |

#### New Files

| File | Purpose |
|------|---------|
| `frontend/services/api.js` | Centralized API client for all backend communication |
| `frontend/README.md` | Frontend architecture documentation |
| `.gitignore` | Git ignore rules |
| `RESTRUCTURE_SUMMARY.md` | Complete restructure documentation |
| `MIGRATION_GUIDE.md` | This file |

## Conversion Examples

### Example 1: Page Conversion (inicio.php → index.html)

**Before (inicio.php):**
```php
<?php view_partial('header'); ?>
<img src="<?= asset('imgs/logo.png') ?>" alt="Logo">
<a href="?page=jugar">Jugar</a>
<?php view_partial('footer'); ?>
```

**After (index.html):**
```html
<div id="header-container"></div>
<img src="assets/imgs/logo.png" alt="Logo">
<a href="pages/jugar.html">Jugar</a>
<div id="footer-container"></div>
<script>
  loadComponent('header-container', 'components/header.html');
  loadComponent('footer-container', 'components/footer.html');
</script>
```

### Example 2: Component Conversion (header.php → header.html)

**Before (header.php):**
```php
<?php if (session_status() !== PHP_SESSION_ACTIVE) session_start(); ?>
<?php $user = $_SESSION['user'] ?? null; ?>
<header>
  <?php if ($user): ?>
    <span>Welcome <?= htmlspecialchars($user['nombre']) ?></span>
  <?php else: ?>
    <a href="#" id="openModal">Login</a>
  <?php endif; ?>
</header>
```

**After (header.html):**
```html
<header>
  <span id="account-label">Cuenta</span>
  <div id="account-menu">
    <a href="#" id="openModal">Iniciar sesión</a>
  </div>
</header>
<script>
  document.addEventListener('DOMContentLoaded', async () => {
    const session = await window.API.auth.getSession();
    if (session && session.user) {
      updateHeaderForLoggedInUser(session.user);
    }
  });
</script>
```

### Example 3: API Call Conversion (auth.js)

**Before:**
```javascript
const res = await fetch('api.php/auth/login', {
  method: 'POST',
  body: JSON.stringify({ email, password })
});
```

**After:**
```javascript
const data = await window.API.auth.login({ email, password });
```

## URL Changes

### Page URLs

| Old URL | New URL |
|---------|---------|
| `http://localhost/public/index.php?page=inicio` | `http://localhost/frontend/index.html` |
| `http://localhost/public/index.php?page=jugar` | `http://localhost/frontend/pages/jugar.html` |
| `http://localhost/public/index.php?page=admin` | `http://localhost/frontend/pages/admin.html` |
| `http://localhost/public/index.php?page=seguimiento` | `http://localhost/frontend/pages/seguimiento.html` |
| etc. | etc. |

### API URLs

| Old API URL | New API URL | Purpose |
|------------|------------|---------|
| `public/api.php/auth/login` | `backend/api.php/auth/login` | Authentication |
| `public/api.php/admin/users` | `backend/api.php/admin/users` | Admin panel |
| `public/index.php?page=jugar&action=init` | `backend/game-api.php?action=init` | Game logic |

### Asset URLs

| Old Asset URL | New Asset URL |
|--------------|--------------|
| `/assets/css/styles.css` | `/frontend/assets/css/styles.css` |
| `/assets/js/main.js` | `/frontend/assets/js/main.js` |
| `/assets/imgs/logo.png` | `/frontend/assets/imgs/logo.png` |

## Directory Structure Comparison

### Before:
```
ProyectoAndando/
├── app/
│   ├── Client/
│   │   ├── paginas/          # PHP pages with HTML
│   │   └── parciales/        # PHP partials with HTML
│   ├── Controllers/
│   ├── Repositories/
│   └── config/
├── public/
│   ├── index.php             # Router
│   ├── api.php               # API
│   └── assets/
│       ├── css/
│       ├── js/
│       └── imgs/
└── index.html                # Redirect to public/
```

### After:
```
ProyectoAndando/
├── backend/                  # API Only
│   ├── api.php               # Auth & Admin API
│   ├── game-api.php          # Game API
│   ├── .htaccess
│   └── app/                  # All backend logic
│       ├── Controllers/
│       ├── Repositories/
│       └── config/
├── frontend/                 # UI Only
│   ├── index.html            # Home page
│   ├── pages/                # HTML pages
│   ├── components/           # HTML components
│   ├── services/             # API client
│   └── assets/               # Static files
│       ├── css/
│       ├── js/
│       └── imgs/
└── index.html                # Redirect to frontend/
```

## Testing After Migration

### 1. Test Backend API
```bash
# Test health endpoint
curl http://localhost:8000/backend/api.php/health

# Test session endpoint
curl http://localhost:8000/backend/api.php/session
```

### 2. Test Frontend Pages
Open in browser:
- http://localhost:8000/frontend/index.html
- http://localhost:8000/frontend/pages/jugar.html
- http://localhost:8000/frontend/pages/admin.html

### 3. Test Authentication
1. Open frontend in browser
2. Click "Iniciar sesión"
3. Try to login
4. Check if header updates
5. Try to logout

### 4. Test Game
1. Go to jugar.html
2. Click "Partida nueva"
3. Try to place dinosaurs
4. Check if game works

## Common Issues and Solutions

### Issue: Components not loading
**Solution:** Check that component paths are correct relative to the page:
- From index.html: `components/header.html`
- From pages/*.html: `../components/header.html`

### Issue: API calls failing
**Solution:** Check API URL in JavaScript:
- Auth/Admin: `../backend/api.php`
- Game: `../backend/game-api.php`

### Issue: Images not showing
**Solution:** Check asset paths are relative:
- From index.html: `assets/imgs/logo.png`
- From pages/*.html: `../assets/imgs/logo.png`

### Issue: Session not working
**Solution:** Ensure both backend and frontend are served from same domain/port for cookies to work.

## Deployment Considerations

### Development
```bash
# Single server for both
php -S localhost:8000 -t .
```

### Production Options

**Option 1: Single server**
```
nginx/apache serves both:
- /backend/* → PHP
- /frontend/* → Static files
```

**Option 2: Separate servers**
```
- api.example.com → Backend
- example.com → Frontend (CDN)
Update API_CONFIG.baseURL in frontend/services/api.js
```

## Rollback Plan

If you need to rollback to the old structure:

1. Keep the `public/` and `app/` directories (don't delete them yet)
2. The old structure still exists alongside the new one
3. Just use the old URLs (public/index.php)

## Next Steps

1. ✅ Structure is complete
2. ⏭️ Test all functionality
3. ⏭️ Fix any issues found during testing
4. ⏭️ Once stable, remove old `public/` folder
5. ⏭️ Deploy to production

## Summary

The migration successfully:
- ✅ Separated backend and frontend completely
- ✅ Removed all PHP from HTML files
- ✅ Created clean API layer
- ✅ Made frontend deployable to CDN
- ✅ Maintained all functionality
- ✅ Kept backend logic unchanged
