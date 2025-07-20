import { useLocation } from 'react-router-dom';
import { useEffect, useRef } from 'react';
import html2canvas from 'html2canvas';

export default function ResultPage() {
  const { state } = useLocation();
  const { photos = [], layout, stickers = {} } = state || {};
  const previewRef = useRef(null);

  useEffect(() => {
    if (photos.length) {
      const preview = previewRef.current;
      preview.innerHTML = '';
      const container = document.createElement('div');
      container.className = 'flex flex-col gap-2 p-4 rounded-lg border-4 border-black';
      container.style.backgroundImage = `url(${layout})`;
      container.style.backgroundSize = 'contain';
      container.style.backgroundRepeat = 'no-repeat';
      container.style.backgroundPosition = 'center';
      container.style.width = '300px';
      container.style.height = '450px';

      photos.forEach((photo, i) => {
        const img = document.createElement('img');
        img.src = photo;
        img.className = `w-[150px] h-[166.35px] object-cover border-2 border-black absolute`;
        img.style.top = `${i * 50}%`;
        img.style.left = '25%';
        container.appendChild(img);

        ['top-left', 'top-right', 'bottom-left', 'bottom-right'].forEach(pos => {
          const stickerKey = `${i}-${pos}`;
          const sticker = stickers[stickerKey] || '/frame/stickers/love.png';
          const stickerImg = document.createElement('img');
          stickerImg.src = sticker;
          stickerImg.className = 'w-12 absolute';
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
          container.appendChild(stickerImg);
        });
      });

      preview.appendChild(container);
    }
  }, [photos, layout, stickers]);

  const handleDownload = () => {
    if (previewRef.current && photos.length) {
      html2canvas(previewRef.current, {
        useCORS: true,
        allowTaint: false,
        backgroundColor: null,
        scale: 2, // Higher scale for better quality
      }).then((canvas) => {
        const link = document.createElement('a');
        link.download = 'photobooth-strip.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
      });
    }
  };

  return (
    <main id="main-section" className="flex flex-col items-center p-4 min-h-screen bg-gradient-to-r from-[#FF8679] via-[#F2AAAE] to-[#F6D3AD]">
      <div className="w-full max-w-6xl">
        <h2 className="text-3xl font-bold mb-6 text-center">Hasil Foto</h2>
        <div id="photoPreview" ref={previewRef} className="flex items-center justify-center p-4 rounded-lg shadow-lg mx-auto" />
        <div className="flex justify-center mt-4">
          <button
            onClick={handleDownload}
            className="bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-lg shadow-lg"
          >
            Download
          </button>
          <button
            onClick={() => window.history.back()}
            className="ml-4 bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg"
          >
            Back
          </button>
        </div>
      </div>
    </main>
  );
}