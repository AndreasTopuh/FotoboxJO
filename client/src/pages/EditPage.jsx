import { useLocation, useNavigate } from 'react-router-dom';
import { useEffect, useRef } from 'react';
import html2canvas from 'html2canvas'; // Import html2canvas

export default function EditPage() {
  const { state } = useLocation();
  const { photos, frame } = state || { photos: [], frame: 0 };
  const navigate = useNavigate();
  const previewRef = useRef(null);

  useEffect(() => {
    if (photos.length) {
      const preview = previewRef.current;
      preview.innerHTML = ''; // Clear previous content
      const grid = document.createElement('div');
      grid.className = 'grid grid-cols-2 gap-2';
      for (let i = 0; i < 6; i++) { // Fixed 2x3 grid (6 slots)
        const div = document.createElement('div');
        div.className = `relative w-[110px] h-[110px] flex items-center justify-center rounded-lg border-2 ${
          i % 2 === 0 ? 'bg-blue-200' : 'bg-white'
        }`;
        if (i < photos.length && i < 6) {
          const img = document.createElement('img');
          img.src = photos[i];
          img.className = 'absolute inset-0 w-full h-full object-cover rounded-lg z-0';
          img.crossOrigin = 'anonymous'; // Ensure CORS for external images
          div.appendChild(img);
        } else {
          div.innerHTML = '<span className="text-sm text-blue-900 font-semibold z-10">Slot</span>';
        }
        grid.appendChild(div);
      }
      preview.appendChild(grid);
    }
  }, [photos]);

  const applyColor = (color) => {
    if (previewRef.current) {
      previewRef.current.style.backgroundColor = color;
    }
  };

  const addSticker = (stickerName, position) => {
    if (previewRef.current) {
      const sticker = document.createElement('img');
      sticker.src = `/assets/stickers/${stickerName}`;
      sticker.style.width = '50px';
      sticker.style.position = 'absolute';
      switch (position) {
        case 'top-left':
          sticker.style.top = '10px';
          sticker.style.left = '10px';
          break;
        case 'top-right':
          sticker.style.top = '10px';
          sticker.style.right = '10px';
          break;
        case 'bottom-left':
          sticker.style.bottom = '10px';
          sticker.style.left = '10px';
          break;
        case 'bottom-right':
          sticker.style.bottom = '10px';
          sticker.style.right = '10px';
          break;
        default:
          sticker.style.top = '50%';
          sticker.style.left = '50%';
          sticker.style.transform = 'translate(-50%, -50%)';
      }
      sticker.style.cursor = 'move';
      sticker.crossOrigin = 'anonymous'; // Ensure CORS for external images
      previewRef.current.appendChild(sticker);
    }
  };

  const handleDownload = () => {
    if (previewRef.current && photos.length) {
      html2canvas(previewRef.current, {
        useCORS: true, // Handle cross-origin images
        allowTaint: false, // Prevent tainted canvas
        backgroundColor: null, // Transparent background if needed
        scale: 2, // Higher quality (optional, adjust as needed)
      }).then((canvas) => {
        const link = document.createElement('a');
        link.download = 'photobooth-strip.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
      });
    }
  };

  const handleNext = () => {
    navigate('/'); // Adjust to desired next page
  };

  return (
    <div className="min-h-screen flex flex-col">
      <main id="main-section" className="flex-grow flex items-center justify-center p-4">
        <section className="custom-main flex flex-col items-center w-full max-w-6xl">
          {photos.length === 0 ? (
            <div className="text-center text-white text-xl">
              No photos available. Please capture photos first!
            </div>
          ) : (
            <>
              <div
                id="photoPreview"
                ref={previewRef}
                className="flex items-center justify-center p-4 bg-[#4B2E2E] rounded-lg shadow-lg"
                style={{ minHeight: '330px', width: '220px', position: 'relative' }}
              />
              <div className="customization-container mt-6 w-full max-w-md">
                <h1 className="custom-heading text-3xl font-bold text-center mb-6">customize your photo</h1>
                <div>
                  <div className="custom-options-container">
                    <h3 className="options-label text-xl mb-4">Frame Color</h3>
                    <div className="custom-buttons-container flex flex-wrap gap-2 mb-6">
                      <input
                        type="color"
                        className="w-10 h-10 cursor-pointer"
                        onChange={(e) => applyColor(e.target.value)}
                        defaultValue="#4B2E2E"
                      />
                      {['#FF69B4', '#00B7EB', '#FFFF00', '#98FF98', '#800080', '#4B2E2E', '#FF0000', '#FFFFFF', '#000000'].map((color) => (
                        <button
                          key={color}
                          onClick={() => applyColor(color)}
                          className="buttonFrames w-10 h-10 rounded-full"
                          style={{ backgroundColor: color }}
                        ></button>
                      ))}
                    </div>
                    <h3 className="options-label text-xl mb-4">Stickers</h3>
                    <div className="custom-buttons-container stickers-container flex flex-wrap gap-2">
                      {[
                        { id: 'noneSticker', src: 'noneShape.png', alt: 'None' },
                        { id: 'bunnySticker', src: 'bunny1.png', alt: 'Bunny' },
                        { id: 'luckySticker', src: 'lucky1.png', alt: 'Lucky' },
                        { id: 'kissSticker', src: 'kiss1.png', alt: 'Kiss' },
                        { id: 'sweetSticker', src: 'sweet1.png', alt: 'Sweet' },
                        { id: 'ribbonSticker', src: 'ribbon1.png', alt: 'Ribbon' },
                        { id: 'sparkleSticker', src: 'sparkle2.png', alt: 'Sparkle' },
                        { id: 'pearlSticker', src: 'pearl2.png', alt: 'Pearl' },
                      ].map((sticker, index) => (
                        <button
                          key={sticker.id}
                          onClick={() => addSticker(sticker.src, ['top-left', 'top-right', 'bottom-left', 'bottom-right'][index % 4])}
                          className="buttonStickers w-10 h-10"
                        >
                          <img src={`/assets/stickers/${sticker.src}`} alt={sticker.alt} className="w-full h-full" />
                        </button>
                      ))}
                    </div>
                    <div className="custom-buttons-holder mt-6 text-center">
                      <button
                        onClick={handleDownload}
                        className="main-button download-button-design bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-full shadow-lg"
                      >
                        Download
                      </button>
                      <button
                        onClick={handleNext}
                        className="main-button bg-blue-500 hover:bg-blue-600 text-white px-6 py-3 rounded-full shadow-lg ml-4"
                      >
                        Lanjut
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </>
          )}
        </section>
      </main>
    </div>
  );
}