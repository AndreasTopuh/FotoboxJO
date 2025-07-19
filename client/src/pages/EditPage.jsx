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
      for (let i = 0; i < 6; i++) { // Fixed 2x3 grid (6 slots)
        const div = document.createElement('div');
        div.className = `relative w-[110px] h-[110px] flex items-center justify-center rounded-lg border-2 ${
          i % 2 === 0 ? 'bg-blue-200' : 'bg-white'
        }`;
        if (i < photos.length && i < 6) {
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
      // Convert cm to px (assuming 96 DPI: 1 cm = 37.8 px)
      const widthPx = 2 * 37.8; // 2 cm
      const heightPx = 6 * 37.8; // 6 cm
      canvas.width = widthPx;
      canvas.height = heightPx;

      // Draw background color
      ctx.fillStyle = previewRef.current.style.backgroundColor || '#4B2E2E';
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      // Draw each photo in a 2x3 grid, scaled to fit
      const photoWidth = widthPx / 2; // 2 columns
      const photoHeight = heightPx / 3; // 3 rows
      photos.forEach((photo, index) => {
        const row = Math.floor(index / 2);
        const col = index % 2;
        const img = new Image();
        img.src = photo;
        img.onload = () => {
          ctx.drawImage(img, col * photoWidth, row * photoHeight, photoWidth, photoHeight);
          // Add stickers (simplified; adjust based on actual sticker positions)
          const stickers = previewRef.current.getElementsByTagName('img');
          for (let sticker of stickers) {
            if (sticker.src.includes('/frame/stickers/')) {
              const stickerImg = new Image();
              stickerImg.src = sticker.src;
              stickerImg.onload = () => {
                const posX = parseFloat(sticker.style.left || '0') * (widthPx / 220) || 0;
                const posY = parseFloat(sticker.style.top || '0') * (heightPx / 660) || 0;
                ctx.drawImage(stickerImg, posX, posY, 50 * (widthPx / 220), 50 * (heightPx / 660));
              };
            }
          }
          if (index === photos.length - 1) {
            // Trigger print after last image
            const printWindow = window.open('', '', 'width=' + widthPx + ',height=' + heightPx);
            printWindow.document.write('<img src="' + canvas.toDataURL('image/png') + '" style="width:100%;height:100%;">');
            printWindow.document.close();
            printWindow.print();
            printWindow.close();
          }
        };
      });
    }
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
                onClick={() => addSticker('pearl2.png', 'top-left')}
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
              style={{ minHeight: '330px', width: '220px', position: 'relative' }} // Adjusted for 2x3
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
                  Print
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