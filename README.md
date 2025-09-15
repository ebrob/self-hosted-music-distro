# Music Distribution Website Template

A modern, responsive music showcase website template that automatically displays your music library with a beautiful dark grey theme. Perfect for independent artists who want to showcase their music without relying on streaming platforms.

## Features

- 🎵 **Automatic Music Library**: Automatically scans and displays your music files
- 📱 **Mobile Responsive**: Works perfectly on all devices
- 🎨 **Modern Design**: Clean, elegant dark grey theme
- 🔄 **Auto-Updates**: New music files are automatically detected
- 🎧 **Audio Player**: Built-in audio player with controls
- 📸 **Artwork Support**: Displays album covers and artwork
- 🔗 **Social Links**: Easy integration with social media platforms
- ⚡ **Fast Loading**: Optimized for performance

## Requirements

### Web Hosting Requirements
- **PHP 7.4 or higher** (required for the music API)
- **Web server** (Apache, Nginx, or similar)
- **File upload access** (to upload your music files)
- **Basic file management** (to organize your music)

### Supported File Formats
- **Audio**: MP3, WAV, FLAC, M4A, OGG, AAC
- **Images**: JPG, JPEG, PNG, GIF, WEBP
- **Archives**: ZIP (for album downloads)

## Quick Setup Guide

### 1. Upload Files
Upload all files from this template to your web server's public directory (usually `public_html`, `www`, or `htdocs`).

### 2. Customize Your Branding
Replace `[ARTIST NAME]` throughout the files with your actual artist name:

**Files to edit:**
- `index.html` - Replace all instances of `[ARTIST NAME]`
- `manifest.json` - Update the app name
- `sw.js` - Update service worker references
- `MOBILE_FEATURES.md` - Update documentation

### 3. Add Your Music
Organize your music in the `music/` folder:

```
music/
├── albums/
│   └── Your Album Name/
│       ├── 01 - Track Name.wav
│       ├── 02 - Another Track.wav
│       ├── cover.jpg
│       └── Your Album Name.zip (optional)
└── songs/
    └── Single Track Name/
        ├── track.wav
        └── artwork.jpg
```

### 4. Update Your Bio
Edit the bio section in `index.html`:
- Update the "About [ARTIST NAME]" section
- Add your social media links
- Customize the description

### 5. Test Your Site
Visit your website to ensure everything works correctly.

## Detailed Setup Instructions

### File Structure
```
your-website/
├── index.html              # Main website file
├── manifest.json           # PWA manifest
├── sw.js                   # Service worker
├── logo.jpg                # Your logo (replace this)
├── api/
│   └── music.php          # Music API (PHP required)
└── music/
    ├── albums/            # Album folders
    ├── songs/             # Single track folders
    ├── default-album.jpg  # Default album artwork
    └── default-song.jpg   # Default song artwork
```

### Customizing Your Branding

1. **Replace Logo**: Replace `logo.jpg` with your own logo
2. **Update Artist Name**: Search and replace `[ARTIST NAME]` in all files
3. **Customize Colors**: Edit the CSS variables in `index.html` if desired
4. **Update Meta Tags**: Change the title and description in `index.html`

### Adding Music Files

#### Albums
Create a folder for each album in `music/albums/`:
- Name the folder with your album title
- Add audio files (numbered tracks work best: "01 - Track Name.wav")
- Add a cover image (cover.jpg, artwork.jpg, etc.)
- Optionally add a ZIP file for the entire album

#### Single Tracks
Create a folder for each single in `music/songs/`:
- Name the folder with your track title
- Add the audio file
- Add artwork (artwork.jpg, cover.jpg, etc.)

### Social Media Integration

The template includes blank social media links that you can easily customize. Simply add your username to the end of each URL:

**Current template links:**
- Instagram: `https://instagram.com/` → Add your username
- Spotify: `https://open.spotify.com/artist/` → Add your artist ID
- YouTube: `https://youtube.com/@/` → Add your channel handle
- SoundCloud: `https://soundcloud.com/` → Add your username

**Example customization:**
```html
<!-- Change this: -->
<a href="https://instagram.com/" class="social-btn" target="_blank">

<!-- To this: -->
<a href="https://instagram.com/yourusername" class="social-btn" target="_blank">
```

**Available social platforms:**
- Instagram (`fab fa-instagram`)
- Spotify (`fab fa-spotify`)
- YouTube (`fab fa-youtube`)
- SoundCloud (`fab fa-soundcloud`)
- Twitter (`fab fa-twitter`)
- Facebook (`fab fa-facebook`)

**To add more platforms:**
1. Copy an existing social button
2. Change the icon class (e.g., `fab fa-twitter`)
3. Update the URL and text
4. Add the new button to both mobile and desktop sections

## Troubleshooting

### Common Issues

**Music not showing up?**
- Check that your web server supports PHP
- Ensure the `api/music.php` file is accessible
- Verify your music files are in the correct folders
- Check file permissions (folders should be readable)

**Audio not playing?**
- Ensure your audio files are in supported formats
- Check that your web server allows serving audio files
- Verify the file paths are correct

**Images not displaying?**
- Check that image files are in supported formats
- Ensure images are properly named (cover.jpg, artwork.jpg, etc.)
- Verify file permissions

### File Permissions
Make sure your web server can read the music files:
```bash
chmod 755 music/
chmod 644 music/*/*
```

## Customization Options

### Changing Colors
Edit the CSS variables in `index.html`:
```css
:root {
    --bg-primary: #2a2a2a;      /* Main background */
    --bg-secondary: #3a3a3a;    /* Secondary background */
    --bg-tertiary: #4a4a4a;     /* Tertiary background */
    --text-primary: #ffffff;    /* Main text color */
    --text-secondary: #cccccc;  /* Secondary text color */
    --border-color: #555555;    /* Border color */
}
```

### Adding New Features
The template is built with vanilla JavaScript and is easy to extend. Key files to modify:
- `index.html` - Main structure and styling
- `api/music.php` - Backend music scanning
- `sw.js` - Service worker for offline functionality

## Browser Support

- Chrome 60+
- Firefox 55+
- Safari 12+
- Edge 79+

## License

This template is provided as-is for personal and commercial use. Feel free to modify and customize it for your needs.

## Support

For issues or questions:
1. Check the troubleshooting section above
2. Verify your web hosting meets the requirements
3. Ensure all files are uploaded correctly
4. Test with a simple setup first

## Changelog

### Version 1.0
- Initial template release
- Dark grey theme
- Automatic music library scanning
- Mobile responsive design
- Social media integration
- Audio player functionality