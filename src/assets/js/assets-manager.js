// Database Assets Helper for Frontend
class AssetsManager {
    static frames = [];
    static stickers = [];
    static loaded = false;

    // Load all assets from database
    static async loadAssets() {
        if (this.loaded) return;
        
        try {
            console.log('🔄 Loading assets from database...');
            
            // Load frames and stickers in parallel
            const [framesResponse, stickersResponse] = await Promise.all([
                fetch('/src/api-fetch/get-frames.php'),
                fetch('/src/api-fetch/get-stickers.php')
            ]);

            // Parse responses
            const framesData = await framesResponse.json();
            const stickersData = await stickersResponse.json();

            if (framesData.success) {
                this.frames = framesData.data;
                console.log(`✅ Loaded ${this.frames.length} frames from database`);
            } else {
                console.warn('⚠️ Failed to load frames:', framesData.error);
                this.frames = this.getFallbackFrames();
            }

            if (stickersData.success) {
                this.stickers = stickersData.data;
                console.log(`✅ Loaded ${this.stickers.length} stickers from database`);
            } else {
                console.warn('⚠️ Failed to load stickers:', stickersData.error);
                this.stickers = this.getFallbackStickers();
            }

            this.loaded = true;
            console.log('✅ Assets loaded successfully');
            
        } catch (error) {
            console.error('❌ Error loading assets:', error);
            // Use fallback assets
            this.frames = this.getFallbackFrames();
            this.stickers = this.getFallbackStickers();
            this.loaded = true;
        }
    }

    // Get frames for UI
    static getFrames() {
        return this.frames.map(frame => ({
            id: `frame_${frame.id}`,
            name: frame.nama,
            src: frame.file_path,
            dbId: frame.id
        }));
    }

    // Get stickers for UI
    static getStickers() {
        return this.stickers.map(sticker => ({
            id: `sticker_${sticker.id}`,
            name: sticker.nama,
            src: sticker.file_path,
            dbId: sticker.id
        }));
    }

    // Fallback frames if database fails
    static getFallbackFrames() {
        return [
            { id: 1, nama: 'Matcha Frame', file_path: '/src/assets/frame-backgrounds/matcha.jpg' },
            { id: 2, nama: 'Black Star Frame', file_path: '/src/assets/frame-backgrounds/blackStar.jpg' },
            { id: 3, nama: 'Blue Stripe Frame', file_path: '/src/assets/frame-backgrounds/blueStripe.jpg' }
        ];
    }

    // Fallback stickers if database fails
    static getFallbackStickers() {
        return [
            { id: 1, nama: 'Star Sticker', file_path: '/src/assets/stickers/bintang1.png' },
            { id: 2, nama: 'Heart Sticker', file_path: '/src/assets/stickers/heart.png' }
        ];
    }

    // Preload images for better performance
    static async preloadImages() {
        const allAssets = [...this.getFrames(), ...this.getStickers()];
        const imagePromises = allAssets.map(asset => {
            return new Promise((resolve, reject) => {
                const img = new Image();
                img.onload = () => resolve(img);
                img.onerror = () => {
                    console.warn(`⚠️ Failed to preload: ${asset.src}`);
                    resolve(null); // Don't fail the entire loading process
                };
                img.src = asset.src;
            });
        });

        try {
            await Promise.all(imagePromises);
            console.log('✅ All assets preloaded');
        } catch (error) {
            console.warn('⚠️ Some assets failed to preload:', error);
        }
    }

    // Initialize assets (call this in DOMContentLoaded)
    static async initialize() {
        await this.loadAssets();
        await this.preloadImages();
        return {
            frames: this.getFrames(),
            stickers: this.getStickers()
        };
    }
}
