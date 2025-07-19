import { useLocation, useNavigate } from 'react-router-dom';
import { useEffect, useRef } from 'react';

export default function EditPage() {
  const { state } = useLocation();
  const { photos, frame } = state || { photos: [], frame: 0 };
  const navigate = useNavigate();
  const previewRef = useRef(null);
  const canvasRef = useRef(null);

  useEffect(() => {
    if (photos.length) {
      const preview = previewRef.current;
      preview.innerHTML = ''; // Clear previous content
      const grid = document.createElement('div');
      grid.className = 'grid grid-cols-2 gap-2';
      for (let i = 0; i < 12; i++) { // 2x6 grid (12 slots)
        const div = document.createElement('div');
        div.className = `relative w-[110px] h-[110px] flex items-center justify-center rounded-lg border-2 ${
          i % 2 === 0 ? 'bg-blue-200' : 'bg-white'
        }`;
        if (i < photos.length) {
          const img = document.createElement('img');
          img.src = photos[i];
          img.className = 'absolute inset-0 w-full h-full object-cover rounded-lg z-0';
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
      sticker.src = `/frame/stickers/${stickerName}`;
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
      previewRef.current.appendChild(sticker);
    }
  };

  const handleDownload = () => {
    if (previewRef.current && photos.length) {
      const canvas = canvasRef.current;
      const ctx = canvas.getContext('2d');
      const scale = 4; // Increase resolution for 1 MB+ quality
      canvas.width = 220 * scale; // 2 * 110px
      canvas.height = 660 * scale; // 6 * 110px

      // Draw background color
      ctx.fillStyle = previewRef.current.style.backgroundColor || '#4B2E2E';
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      // Draw each photo centered in the 2x6 grid
      const paddingX = (canvas.width - 220 * scale) / 2;
      const paddingY = (canvas.height - 660 * scale) / 2;
      photos.forEach((photo, index) => {
        const row = Math.floor(index / 2);
        const col = index % 2;
        const img = new Image();
        img.src = photo;
        img.onload = () => {
          ctx.drawImage(img, paddingX + (col * 110 * scale), paddingY + (row * 110 * scale), 110 * scale, 110 * scale);
          if (index === photos.length - 1) {
            // Trigger download after last image
            const dataUrl = canvas.toDataURL('image/png', 1.0); // High quality
            const link = document.createElement('a');
            link.href = dataUrl;
            link.download = 'photo-strip.png'; // Updated filename
            link.click();
          }
        };
      });
    }
  };

  const handleNext = () => {
    navigate('/'); // Adjust to desired next page
  };

  return (
    <div className="min-h-screen flex flex-col">
      <main id="main-section" className="flex-grow flex items-center justify-center p-4">
        <section className="flex w-full max-w-6xl">
          <div className="w-1/2 p-4">
            <h2 className="text-xl font-bold mb-4">Tambahkan Sticker</h2>
            <div className="grid grid-cols-3 gap-2">
              <button
                onClick={() => addSticker('balerinaCappuccino3.png', 'top-left')}
                className="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded"
              >
                Balerina
              </button>
              <button
                onClick={() => addSticker('bunny1.png', 'top-right')}
                className="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded"
              >
                Bunny
              </button>
              <button
                onClick={() => addSticker('doggyWhite1.png', 'bottom-left')}
                className="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded"
              >
                Dog
              </button>
              <button
                onClick={() => addSticker('love.png', 'bottom-right')}
                className="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded"
              >
                Love
              </button>
              <button
                onClick={() => addSticker('pearl2.png', 'top-left')} // Reuse position as example
                className="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded"
              >
                Pearl
              </button>
            </div>
          </div>
          <div className="w-1/2 p-4">
            <h2 className="text-xl font-bold mb-4">Preview</h2>
            <div
              id="photoPreview"
              ref={previewRef}
              className="flex items-center justify-center p-4 bg-[#4B2E2E] rounded-lg shadow-lg"
              style={{ minHeight: '660px', width: '220px', position: 'relative' }}
            />
            <div className="mt-4 flex justify-between">
              <div>
                <h3 className="text-lg mb-2">Frame Color</h3>
                <input
                  type="color"
                  id="colorPicker"
                  className="w-12 h-12 cursor-pointer"
                  onChange={(e) => applyColor(e.target.value)}
                  defaultValue="#4B2E2E"
                />
              </div>
              <div className="flex space-x-2">
                <button
                  onClick={handleDownload}
                  className="bg-pink-500 hover:bg-pink-600 text-white px-4 py-2 rounded"
                >
                  Download
                </button>
                <button
                  onClick={handleNext}
                  className="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded"
                >
                  Lanjut
                </button>
              </div>
            </div>
          </div>
        </section>
      </main>
      <canvas ref={canvasRef} style={{ display: 'none' }} />
    </div>
  );
}
