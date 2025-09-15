<?php
// Set proper headers for JSON API
header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

// Handle preflight OPTIONS requests
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Security: Only allow GET requests (when running as web server)
if (isset($_SERVER['REQUEST_METHOD']) && $_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed', 'method' => $_SERVER['REQUEST_METHOD']]);
    exit;
}

// Debug: Log the request method
error_log('Music API called with method: ' . $_SERVER['REQUEST_METHOD']);

// Security: Validate and sanitize input (when running as web server)
$requestedPath = '';
if (isset($_GET['path'])) {
    $requestedPath = $_GET['path'];
    $allowedPaths = ['albums', 'songs'];
    
    if (!in_array($requestedPath, $allowedPaths)) {
        http_response_code(400);
        echo json_encode(['error' => 'Invalid path requested']);
        exit;
    }
}

// Security: Define safe file extensions
$audioExtensions = ['mp3', 'wav', 'flac', 'm4a', 'ogg', 'aac'];
$imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
$archiveExtensions = ['zip'];

// Helper functions
function getFileExtension($filename) {
    return strtolower(pathinfo($filename, PATHINFO_EXTENSION));
}



function getFilenameWithoutExtension($filename) {
    return pathinfo($filename, PATHINFO_FILENAME);
}

function isAudioFile($filename) {
    global $audioExtensions;
    return in_array(getFileExtension($filename), $audioExtensions);
}

function isImageFile($filename) {
    global $imageExtensions;
    return in_array(getFileExtension($filename), $imageExtensions);
}

function isArchiveFile($filename) {
    global $archiveExtensions;
    return in_array(getFileExtension($filename), $archiveExtensions);
}

function sanitizePath($path) {
    // Remove any potentially dangerous characters
    $path = str_replace(['..', '\\'], '', $path);
    return $path;
}

function scanMusicDirectory($basePath) {
    $musicData = ['albums' => [], 'songs' => []];
    
    // Scan albums directory
    $albumsPath = $basePath . '/albums';
    if (is_dir($albumsPath)) {
        $albums = [];
        $albumDirs = glob($albumsPath . '/*', GLOB_ONLYDIR);
        
        foreach ($albumDirs as $albumDir) {
            $albumName = basename($albumDir);
            $albumData = [
                'name' => $albumName,
                'artwork' => 'music/default-album.jpg',
                'tracks' => [],
                'zip' => null
            ];
            
            // Scan files in album directory
            $files = glob($albumDir . '/*');
            foreach ($files as $file) {
                $filename = basename($file);
                $relativePath = 'music/albums/' . $albumName . '/' . $filename;
                
                if (isAudioFile($filename)) {
                    $albumData['tracks'][] = [
                        'path' => $relativePath,
                        'name' => getFilenameWithoutExtension($filename)
                    ];
                } elseif (isImageFile($filename) && $albumData['artwork'] === 'music/default-album.jpg') {
                    $albumData['artwork'] = $relativePath;
                } elseif (isArchiveFile($filename)) {
                    $albumData['zip'] = $relativePath;
                }
            }
            
            // Only add albums that have tracks
            if (!empty($albumData['tracks'])) {
                $albums[] = $albumData;
            }
        }
        
        $musicData['albums'] = $albums;
    }
    
    // Scan songs directory
    $songsPath = $basePath . '/songs';
    if (is_dir($songsPath)) {
        $songs = [];
        
        // First, check for song folders (new structure)
        $songDirs = glob($songsPath . '/*', GLOB_ONLYDIR);
        foreach ($songDirs as $songDir) {
            $songName = basename($songDir);
            $songData = [
                'name' => $songName,
                'artwork' => 'music/default-song.jpg'
            ];
            
            // Scan files in song directory
            $files = glob($songDir . '/*');
            foreach ($files as $file) {
                $filename = basename($file);
                $relativePath = 'music/songs/' . $songName . '/' . $filename;
                
                if (isAudioFile($filename)) {
                    $songData['path'] = $relativePath;
                } elseif (isImageFile($filename) && $songData['artwork'] === 'music/default-song.jpg') {
                    $songData['artwork'] = $relativePath;
                }
            }
            
            // Only add songs that have audio files
            if (isset($songData['path'])) {
                $songs[] = $songData;
            }
        }
        
        // Then, check for individual files (legacy structure)
        $files = glob($songsPath . '/*');
        $songGroups = [];
        foreach ($files as $file) {
            $filename = basename($file);
            $baseName = getFilenameWithoutExtension($filename);
            
            if (!isset($songGroups[$baseName])) {
                $songGroups[$baseName] = ['audio' => null, 'artwork' => null];
            }
            
            if (isAudioFile($filename)) {
                $songGroups[$baseName]['audio'] = 'music/songs/' . $filename;
            } elseif (isImageFile($filename)) {
                $songGroups[$baseName]['artwork'] = 'music/songs/' . $filename;
            }
        }
        
        // Convert legacy file groups to songs array
        foreach ($songGroups as $baseName => $songData) {
            if ($songData['audio']) {
                $songs[] = [
                    'path' => $songData['audio'],
                    'name' => $baseName,
                    'artwork' => $songData['artwork'] ?: 'music/default-song.jpg'
                ];
            }
        }
        
        $musicData['songs'] = $songs;
    }
    
    return $musicData;
}

// Main execution
try {
    // Security: Use relative path from script location
    $basePath = dirname(__DIR__) . '/music';
    $basePath = sanitizePath($basePath);
    
    // Check if music directory exists
    if (!is_dir($basePath)) {
        echo json_encode(['albums' => [], 'songs' => []]);
        exit;
    }
    
    // Scan and return music data
    $musicData = scanMusicDirectory($basePath);
    echo json_encode($musicData, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
    
} catch (Exception $e) {
    // Log error (in production, you might want to log to a file)
    error_log('Music API Error: ' . $e->getMessage());
    
    http_response_code(500);
    echo json_encode([
        'error' => 'Failed to scan music directory',
        'albums' => [],
        'songs' => []
    ]);
}
?>
