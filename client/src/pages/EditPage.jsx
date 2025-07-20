import { useLocation, useNavigate } from 'react-router-dom';
import { useEffect, useRef, useState } from 'react';

export default function EditPage() {
  const { state } = useLocation();
  const { photos = [], layout } = state || {};
  const navigate = useNavigate();
  const previewRef = useRef(null);
  const [stickers, setStickers] = useState({});

  useEffect(() => {
    if (photos.length) {
      const preview = previewRef.current;
      preview.innerHTML = '';
      const container = document.createElement('div');
      container.className = 'relative p-4 rounded-lg border-4 border-black';
      container.style.width = '378px'; // 10cm x 15cm at 96dpi
      container.style.height = '567px';
      container.style.backgroundImage = `url(${layout})`;
      container.style.backgroundSize = 'contain';
      container.style.backgroundRepeat = 'no-repeat';
      container.style.backgroundPosition = 'center';

      photos.forEach((photo, i) => {
        const img = document.createElement('img');
        img.src = photo;
        img.className = `w-full h-full object-cover absolute top-0 left-0`;
        container.appendChild(img);

        // Add sticker placeholders at corners
        ['top-left', 'top-right', 'bottom-left', 'bottom-right'].forEach(pos => {
          const stickerKey = `${i}-${pos}`;
          const sticker = stickers[stickerKey] || '/frame/stickers/love.png'; // Default to 'love'
          const stickerImg = document.createElement('img');
          stickerImg.src = sticker;
          stickerImg.className = 'w-12 absolute cursor-pointer';
          switch (pos) {
            case 'top-left':
              stickerImg.style.top = '5px';
              stickerImg.style.left = '5px';
              break;
            case 'top-right':
              stickerImg.style.top = '5px';
              stickerImg.style.right = '5px';
              break;
            case 'bottom-left':
              stickerImg.style.bottom = '5px';
              stickerImg.style.left = '5px';
              break;
            case 'bottom-right':
              stickerImg.style.bottom = '5px';
              stickerImg.style.right = '5px';
              break;
          }
          stickerImg.onclick = () => {
            const newSticker = prompt('Enter sticker name (e.g., bunny1, love, pearl2):') || 'love';
            setStickers(prev => ({
              ...prev,
              [stickerKey]: `/frame/stickers/${newSticker}.png`
            }));
          };
          container.appendChild(stickerImg);
        });
      });

      preview.appendChild(container);
    }
  }, [photos, layout, stickers]);

  const handleContinue = () => {
    navigate('/result', { state: { photos, layout, stickers } });
  };

  return (
    <main id="main-section" className="flex flex-col items-center p-4 min-h-screen bg-gradient-to-r from-[#FF8679] via-[#F2AAAE] to-[#F6D3AD]">
      <section className="custom-main flex flex-col md:flex-row gap-6 w-full max-w-6xl">
        <div
          id="photoPreview"
          ref={previewRef}
          className="flex items-center justify-center p-4 rounded-lg shadow-lg relative"
        />
        <div className="customization-container w-full max-w-md">
          <h1 className="custom-heading text-3xl font-bold text-center mb-6 text-gray-800">Customize your photo</h1>
          <div className="custom-options-container">
            <h3 className="options-label text-xl font-semibold mb-4">Stickers</h3>
            <div className="custom-buttons-container stickers-container flex flex-wrap gap-2 mb-6">
              {['bunny1', 'love', 'pearl2', 'doggyWhite1', 'ballerinaCappuccino3'].map(sticker => (
                <button
                  key={sticker}
                  onClick={() => {
                    const pos = prompt('Enter position (top-left, top-right, bottom-left, bottom-right):');
                    if (pos) {
                      const stickerKey = `${Object.keys(stickers).length}-${pos}`;
                      setStickers(prev => ({
                        ...prev,
                        [stickerKey]: `/frame/stickers/${sticker}.png`
                      }));
                    }
                  }}
                  className="buttonStickers w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-lg"
                >
                  <img src={`/frame/stickers/${sticker}.png`} alt={sticker} className="w-full h-full" />
                </button>
              ))}
            </div>
            <div className="custom-buttons-holder flex gap-4 justify-center">
              <button
                onClick={handleContinue}
                className="main-button download-button-design bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-lg shadow-lg"
              >
                Lanjut
              </button>
            </div>
          </div>
        </div>
      </section>
    </main>
  );
}