import { useLocation, useNavigate } from 'react-router-dom';
import { useEffect, useRef } from 'react';

export default function EditPage() {
  const { state } = useLocation();
  const { photos, frame } = state || { photos: [], frame: 0 };
  const navigate = useNavigate();
  const previewRef = useRef(null);

  useEffect(() => {
    if (photos.length) {
      const preview = previewRef.current;
      photos.forEach((photo, index) => {
        const img = document.createElement('img');
        img.src = photo;
        img.style.width = '100px'; // Inline style for photo size
        img.style.margin = '5px';
        preview.appendChild(img);
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
      const sticker = document.createElement('img');
      sticker.src = `/frame/stickers/${stickerName}`;
      sticker.style.width = '50px'; // Inline style for sticker size
      sticker.style.position = 'absolute';
      sticker.style.cursor = 'move'; // Optional: for drag functionality
      previewRef.current.appendChild(sticker);
    }
  };

  const handlePrint = () => {
    window.print();
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
      <main id="main-section" className="flex-grow flex items-center justify-center">
        <div className="gradientBgCanvas hidden"></div>
        <section className="custom-main flex flex-col items-center w-full max-w-4xl">
          <div
            id="photoPreview"
            ref={previewRef}
            className="flex justify-center items-center p-6 bg-white rounded-lg shadow-lg relative"
            style={{ minHeight: '400px', position: 'relative' }} // Inline style for preview area
          />
          <div className="customization-container mt-6 w-full max-w-md">
            <h1 className="custom-heading text-3xl font-bold text-center mb-6">
                customize your photo
            </h1>
            <div>
              <div className="custom-options-container">
                <h3 className="options-label text-xl mb-4">Frame Color</h3>
                <div className="custom-buttons-container flex flex-wrap gap-4 mb-6">
                  <input
                    type="color"
                    id="colorPicker"
                    className="w-12 h-12 cursor-pointer"
                    onChange={(e) => applyColor(e.target.value)}
                  />
                </div>
                <h3 className="options-label text-xl mb-4">Stickers</h3>
                <div className="custom-buttons-container stickers-container flex flex-wrap gap-4">
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
                <div className="custom-buttons-holder mt-6 text-center">
                  <button
                    className="main-button download-button-design bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-full shadow-lg"
                    onClick={handlePrint}
                  >
                    Print
                  </button>
                </div>
              </div>
            </div>
         </div>
          </section>
        </main>
      </div>
  );
}