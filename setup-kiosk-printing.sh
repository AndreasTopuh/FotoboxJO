#!/bin/bash

# 🖨️ KIOSK PRINTING SETUP SCRIPT
# Script untuk setup otomatis kiosk printing mode

echo "🖨️ Photobooth Kiosk Printing Setup"
echo "=================================="

# Detect OS
if [[ "$OSTYPE" == "linux-gnu"* ]]; then
    OS="linux"
    CHROME_PATH="/usr/bin/google-chrome"
elif [[ "$OSTYPE" == "darwin"* ]]; then
    OS="mac"
    CHROME_PATH="/Applications/Google Chrome.app/Contents/MacOS/Google Chrome"
elif [[ "$OSTYPE" == "msys" || "$OSTYPE" == "cygwin" ]]; then
    OS="windows"
    CHROME_PATH="C:\Program Files\Google\Chrome\Application\chrome.exe"
else
    echo "❌ OS tidak support: $OSTYPE"
    exit 1
fi

echo "🔍 Detected OS: $OS"

# Check if Chrome is installed
if [[ "$OS" == "windows" ]]; then
    if [ ! -f "$CHROME_PATH" ]; then
        echo "❌ Chrome tidak ditemukan di: $CHROME_PATH"
        echo "   Please install Google Chrome first"
        exit 1
    fi
else
    if ! command -v google-chrome &> /dev/null; then
        echo "❌ Chrome tidak ditemukan"
        echo "   Please install Google Chrome first"
        exit 1
    fi
fi

echo "✅ Chrome ditemukan"

# Default URL
DEFAULT_URL="https://www.gofotobox.online/src/pages/customizeLayout1.php"
read -p "📍 Enter photobooth URL [$DEFAULT_URL]: " URL
URL=${URL:-$DEFAULT_URL}

echo "🔧 Setting up kiosk mode..."

# Create kiosk launch script
if [[ "$OS" == "linux" ]]; then
    SCRIPT_PATH="$HOME/photobooth_kiosk.sh"
    cat > "$SCRIPT_PATH" << EOF
#!/bin/bash
# Photobooth Kiosk Mode Launcher
google-chrome \\
    --kiosk \\
    --kiosk-printing \\
    --disable-web-security \\
    --allow-running-insecure-content \\
    --disable-features=VizDisplayCompositor \\
    --disable-background-timer-throttling \\
    --disable-backgrounding-occluded-windows \\
    --disable-renderer-backgrounding \\
    --no-first-run \\
    --no-default-browser-check \\
    --disable-default-apps \\
    --disable-popup-blocking \\
    --disable-translate \\
    --disable-infobars \\
    --autoplay-policy=no-user-gesture-required \\
    "$URL"
EOF
    chmod +x "$SCRIPT_PATH"
    echo "✅ Kiosk script created: $SCRIPT_PATH"

elif [[ "$OS" == "mac" ]]; then
    SCRIPT_PATH="$HOME/photobooth_kiosk.command"
    cat > "$SCRIPT_PATH" << EOF
#!/bin/bash
# Photobooth Kiosk Mode Launcher
"$CHROME_PATH" \\
    --kiosk \\
    --kiosk-printing \\
    --disable-web-security \\
    --allow-running-insecure-content \\
    --no-first-run \\
    --no-default-browser-check \\
    --disable-default-apps \\
    --disable-popup-blocking \\
    --disable-translate \\
    --autoplay-policy=no-user-gesture-required \\
    "$URL"
EOF
    chmod +x "$SCRIPT_PATH"
    echo "✅ Kiosk script created: $SCRIPT_PATH"

elif [[ "$OS" == "windows" ]]; then
    SCRIPT_PATH="$HOME/photobooth_kiosk.bat"
    cat > "$SCRIPT_PATH" << 'EOF'
@echo off
REM Photobooth Kiosk Mode Launcher
"C:\Program Files\Google\Chrome\Application\chrome.exe" ^
    --kiosk ^
    --kiosk-printing ^
    --disable-web-security ^
    --allow-running-insecure-content ^
    --disable-features=VizDisplayCompositor ^
    --no-first-run ^
    --no-default-browser-check ^
    --disable-default-apps ^
    --disable-popup-blocking ^
    --disable-translate ^
    --autoplay-policy=no-user-gesture-required ^
    %URL%
EOF
    # Replace %URL% with actual URL
    sed -i "s|%URL%|$URL|g" "$SCRIPT_PATH"
    echo "✅ Kiosk script created: $SCRIPT_PATH"
fi

# Create desktop shortcut (Linux)
if [[ "$OS" == "linux" ]] && command -v xdg-user-dir &> /dev/null; then
    DESKTOP_DIR=$(xdg-user-dir DESKTOP)
    if [ -d "$DESKTOP_DIR" ]; then
        SHORTCUT_PATH="$DESKTOP_DIR/Photobooth_Kiosk.desktop"
        cat > "$SHORTCUT_PATH" << EOF
[Desktop Entry]
Version=1.0
Name=Photobooth Kiosk
Comment=Launch Photobooth in Kiosk Mode
Exec=$SCRIPT_PATH
Icon=google-chrome
Terminal=false
Type=Application
Categories=Application;Network;
EOF
        chmod +x "$SHORTCUT_PATH"
        echo "✅ Desktop shortcut created: $SHORTCUT_PATH"
    fi
fi

# Auto-start setup (optional)
echo ""
read -p "🚀 Setup auto-start on boot? (y/n): " AUTOSTART
if [[ "$AUTOSTART" == "y" || "$AUTOSTART" == "Y" ]]; then
    if [[ "$OS" == "linux" ]]; then
        AUTOSTART_DIR="$HOME/.config/autostart"
        mkdir -p "$AUTOSTART_DIR"
        AUTOSTART_PATH="$AUTOSTART_DIR/photobooth-kiosk.desktop"
        cat > "$AUTOSTART_PATH" << EOF
[Desktop Entry]
Type=Application
Name=Photobooth Kiosk
Exec=$SCRIPT_PATH
Hidden=false
NoDisplay=false
X-GNOME-Autostart-enabled=true
EOF
        echo "✅ Auto-start setup complete"
    else
        echo "⚠️ Auto-start manual setup required for $OS"
        echo "   Add script to startup folder: $SCRIPT_PATH"
    fi
fi

# Test kiosk mode
echo ""
read -p "🧪 Test kiosk mode now? (y/n): " TEST
if [[ "$TEST" == "y" || "$TEST" == "Y" ]]; then
    echo "🚀 Launching kiosk mode..."
    if [[ "$OS" == "windows" ]]; then
        cmd.exe /c "$SCRIPT_PATH"
    else
        "$SCRIPT_PATH" &
    fi
fi

echo ""
echo "🎉 Setup completed!"
echo "📋 Summary:"
echo "   - Kiosk script: $SCRIPT_PATH"
if [[ -n "$SHORTCUT_PATH" ]]; then
    echo "   - Desktop shortcut: $SHORTCUT_PATH"
fi
echo "   - URL: $URL"
echo ""
echo "💡 Tips:"
echo "   - Double-click script to launch kiosk mode"
echo "   - Press Alt+F4 (Windows/Linux) or Cmd+Q (Mac) to exit"
echo "   - Use ?kiosk=true URL parameter for testing"
echo ""
echo "📚 For more info, check: KIOSK_PRINTING_SETUP.md"
