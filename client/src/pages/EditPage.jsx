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
      photos.forEach((photo, index) => {
        const div = document.createElement('div');
        div.className = `relative w-[110px] h-[110px] flex items-center justify-center rounded-lg border-2 ${
          index % 2 === 0 ? 'bg-blue-200' : 'bg-white'
        }`;
        const img = document.createElement('img');
        img.src = photo;
        img.className = 'absolute inset-0 w-full h-full object-cover rounded-lg z-0';
        div.appendChild(img);
        preview.appendChild(div);
      });
    }
  }, [photos]);

  const applyColor = (color) => {
    if (previewRef.current) {
      previewRef.current.style.backgroundColor = color;
    }
  };

  const addSticker = (stickerName) => {
    if (previewRef.current) {
      const stickerDivs = previewRef.current.children;
      if (stickerDivs.length > 0) {
        const targetDiv = stickerDivs[stickerDivs.length - 1]; // Add to last photo
        const sticker = document.createElement('img');
        sticker.src = `/frame/stickers/${stickerName}`;
        sticker.style.width = '50px';
        sticker.style.position = 'absolute';
        sticker.style.top = '50%';
        sticker.style.left = '50%';
        sticker.style.transform = 'translate(-50%, -50%)';
        sticker.style.cursor = 'move';
        targetDiv.appendChild(sticker);
      }
    }
  };

  const handleDownload = () => {
    if (previewRef.current && photos.length) {
      const canvas = canvasRef.current;
      const ctx = canvas.getContext('2d');
      const scale = 4; // Increase resolution for 1 MB+ quality
      canvas.width = 300 * scale; // Adjust based on frame width
      canvas.height = (110 * photos.length) * scale; // Adjust based on number of photos

      // Draw background color
      ctx.fillStyle = previewRef.current.style.backgroundColor || '#4B2E2E';
      ctx.fillRect(0, 0, canvas.width, canvas.height);

      // Draw each photo
      photos.forEach((photo, index) => {
        const img = new Image();
        img.src = photo;
        img.onload = () => {
          ctx.drawImage(img, 10 * scale, (110 * index) * scale, 110 * scale, 110 * scale);
          if (index === photos.length - 1) {
            // Trigger download after last image
            const dataUrl = canvas.toDataURL('image/png', 1.0); // High quality
            const link = document.createElement('a');
            link.href = dataUrl;
            link.download = 'photobooth-strip.png';
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
      <header id="main-header" className="bg-gray-800 p-4">
        <nav id="new-navbar" className="flex justify-center">
          <div id="logo-container">
            <p className="side-logo text-white text-2xl font-bold">
              <a href="/" className="home-tag text-blue-400 hover:text-blue-300">photobooth</a>
            </p>
          </div>
        </nav>
      </header>
      <main id="main-section" className="flex-grow flex items-center justify-center p-4">
        <section className="flex w-full max-w-6xl">
          <div className="w-1/2 p-4">
            <h2 className="text-xl font-bold mb-4">Tambahkan Sticker</h2>
            <div className="grid grid-cols-3 gap-2">
              <button
                onClick={() => addSticker('balerinaCappuccino3.png')}
                className="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded"
              >
                Balerina
              </button>
              <button
                onClick={() => addSticker('bunny1.png')}
                className="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded"
              >
                Bunny
              </button>
              <button
                onClick={() => addSticker('doggyWhite1.png')}
                className="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded"
              >
                Dog
              </button>
              <button
                onClick={() => addSticker('love.png')}
                className="bg-gray-200 hover:bg-gray-300 text-gray-800 px-4 py-2 rounded"
              >
                Love
              </button>
              <button
                onClick={() => addSticker('pearl2.png')}
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
              className="flex flex-col items-center p-4 bg-[#4B2E2E] rounded-lg shadow-lg"
              style={{ minHeight: '400px', position: 'relative' }}
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