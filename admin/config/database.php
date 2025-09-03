<?php
class Database
{
    private static $connection = null;

    private static $host = 'localhost';
    private static $dbname = 'db_gofotobox';
    private static $username = 'root';
    private static $password = 'loron';

    public static function connect()
    {
        if (self::$connection === null) {
            try {
                self::$connection = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8mb4",
                    self::$username,
                    self::$password,
                    [
                        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                        PDO::ATTR_EMULATE_PREPARES => false
                    ]
                );
            } catch (PDOException $e) {
                error_log("Database connection failed: " . $e->getMessage());
                throw new Exception("Database connection failed: " . $e->getMessage());
            }
        }
        return self::$connection;
    }

    // Frame methods
    public static function getAllFrames()
    {
        $db = self::connect();
        $stmt = $db->query("SELECT * FROM table_frame WHERE is_active = 1 ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public static function insertFrame($nama, $warna, $filename, $file_path, $file_size)
    {
        $db = self::connect();
        $stmt = $db->prepare("INSERT INTO table_frame (nama, warna, filename, file_path, file_size) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nama, $warna, $filename, $file_path, $file_size]);
    }

    public static function deleteFrame($id)
    {
        $db = self::connect();
        $stmt = $db->prepare("UPDATE table_frame SET is_active = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Layout-specific Frame methods
    public static function getFramesByLayout($layout_id)
    {
        $db = self::connect();
        $table_name = "table_frame_layout" . $layout_id;
        $stmt = $db->query("SELECT * FROM {$table_name} WHERE is_active = 1 ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public static function insertFrameToLayout($layout_id, $nama, $warna, $filename, $file_path, $file_size)
    {
        $db = self::connect();
        $table_name = "table_frame_layout" . $layout_id;
        $stmt = $db->prepare("INSERT INTO {$table_name} (nama, warna, filename, file_path, file_size) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nama, $warna, $filename, $file_path, $file_size]);
    }

    public static function deleteFrameFromLayout($layout_id, $id)
    {
        $db = self::connect();
        $table_name = "table_frame_layout" . $layout_id;
        $stmt = $db->prepare("UPDATE {$table_name} SET is_active = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public static function getFrameFromLayout($layout_id, $id)
    {
        $db = self::connect();
        $table_name = "table_frame_layout" . $layout_id;
        $stmt = $db->prepare("SELECT * FROM {$table_name} WHERE id = ? AND is_active = 1");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    // Sticker methods
    public static function getAllStickers()
    {
        $db = self::connect();
        $stmt = $db->query("SELECT * FROM table_sticker WHERE is_active = 1 ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    // Get stickers by layout (NULL layout_id means universal stickers)
    public static function getStickersByLayout($layout_id)
    {
        $db = self::connect();
        $stmt = $db->prepare("SELECT * FROM table_sticker WHERE (layout_id = ? OR layout_id IS NULL) AND is_active = 1 ORDER BY created_at DESC");
        $stmt->execute([$layout_id]);
        return $stmt->fetchAll();
    }

    public static function insertSticker($nama, $filename, $file_path, $file_size, $layout_id = null)
    {
        $db = self::connect();
        $stmt = $db->prepare("INSERT INTO table_sticker (nama, filename, file_path, file_size, layout_id) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$nama, $filename, $file_path, $file_size, $layout_id]);
    }

    public static function deleteSticker($id)
    {
        $db = self::connect();
        $stmt = $db->prepare("UPDATE table_sticker SET is_active = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }

    // Frame & Sticker Combo methods
    public static function getFrameStickerComboByLayout($layout_id)
    {
        $db = self::connect();
        $stmt = $db->prepare("SELECT * FROM table_frame_sticker_combo WHERE layout_id = ? AND is_active = 1 ORDER BY created_at DESC");
        $stmt->execute([$layout_id]);
        return $stmt->fetchAll();
    }

    public static function insertFrameStickerCombo($layout_id, $nama, $filename, $file_path, $file_size)
    {
        $db = self::connect();
        $stmt = $db->prepare("INSERT INTO table_frame_sticker_combo (layout_id, nama, filename, file_path, file_size) VALUES (?, ?, ?, ?, ?)");
        return $stmt->execute([$layout_id, $nama, $filename, $file_path, $file_size]);
    }

    public static function deleteFrameStickerCombo($id)
    {
        $db = self::connect();
        $stmt = $db->prepare("UPDATE table_frame_sticker_combo SET is_active = 0 WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
