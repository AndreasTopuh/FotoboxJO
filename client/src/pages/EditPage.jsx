
import { useLocation, useNavigate } from 'react-router-dom';
import { useEffect, useRef, useState } from 'react';
import html2canvas from 'html2canvas';

export default function EditPage() {
  const { state } = useLocation();
  const { photos = [] } = state || {};
  const navigate = useNavigate();
  const previewRef = useRef(null);
  const [frameColor, setFrameColor] = useState('#FFFFFF');
  const [frameShape, setFrameShape] = useState('none');
  const [stickers, setStickers] = useState([]);
  const [addDate, setAddDate] = useState(false);
  const [addTime, setAddTime] = useState(false);

  useEffect(() => {
    if (photos.length) {
      const preview = previewRef.current;
      preview.innerHTML = '';
      const container = document.createElement('div');
      container.className = 'flex flex-col gap-2 p-4 rounded-lg border-4 border-black';
      container.style.backgroundColor = frameColor;
      photos.forEach((photo, i) => {
        const img = document.createElement('img');
        img.src = photo;
        img.className = `w-[150px] h-[166.35px] object-cover border-2 border-black ${
          frameShape === 'circle' ? 'rounded-full' : frameShape === 'soft' ? 'rounded-2xl' : 'rounded-xl'
        }`;
        img.style.clipPath = frameShape === 'heart'
          ? 'path("M 75 150 C 30 150, 0 120, 0 75 C 0 30, 30 0, 75 0 C 120 0, 150 30, 150 75 C 150 120, 120 150, 75 150 Z")'
          : 'none';
        img.crossOrigin = 'anonymous';
        container.appendChild(img);
      });
      if (addDate || addTime) {
        const text = document.createElement('div');
        text.className = 'absolute bottom-4 left-4 text-white text-sm font-bold bg-black bg-opacity-50 px-2 py-1 rounded';
        text.innerText = `${addDate ? new Date().toLocaleDateString() : ''} ${addTime ? new Date().toLocaleTimeString() : ''}`.trim();
        container.appendChild(text);
      }
      stickers.forEach(({ src, position }) => {
        const sticker = document.createElement('img');
        sticker.src = `/assets/stickers/${src}`;
        sticker.className = 'w-12 absolute';
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
        sticker.crossOrigin = 'anonymous';
        container.appendChild(sticker);
      });
      preview.appendChild(container);
    }
  }, [photos, frameColor, frameShape, stickers, addDate, addTime]);

  const applyFrameColor = (color) => {
    setFrameColor(color);
  };

  const applyFrameShape = (shape) => {
    setFrameShape(shape);
  };

  const addSticker = (stickerSrc, position) => {
    setStickers([...stickers, { src: stickerSrc, position }]);
  };

  const handleDownload = () => {
    if (previewRef.current && photos.length) {
      html2canvas(previewRef.current, {
        useCORS: true,
        allowTaint: false,
        backgroundColor: null,
        scale: 2,
      }).then((canvas) => {
        const link = document.createElement('a');
        link.download = 'photobooth-strip.png';
        link.href = canvas.toDataURL('image/png');
        link.click();
      });
    }
  };

  const handleRetake = () => {
    navigate('/camera');
  };

  return (
    <main id="main-section" className="flex flex-col items-center p-4 min-h-screen bg-gradient-to-r from-[#FF8679] via-[#F2AAAE] to-[#F6D3AD]">
      <section className="custom-main flex flex-col md:flex-row gap-6 w-full max-w-6xl">
        <div
          id="photoPreview"
          ref={previewRef}
          className="flex items-center justify-center p-4 rounded-lg shadow-lg relative"
          style={{ minWidth: '170px', minHeight: '400px' }}
        />
        <div className="customization-container w-full max-w-md">
          <h1 className="custom-heading text-3xl font-bold text-center mb-6 text-gray-800">customize your photo</h1>
          <div className="custom-options-container">
            <h3 className="options-label text-xl font-semibold mb-4">Frame Color</h3>
            <div className="custom-buttons-container flex flex-wrap gap-2 mb-6">
              <input
                type="color"
                className="w-10 h-10 cursor-pointer rounded-full"
                onChange={(e) => applyFrameColor(e.target.value)}
                value={frameColor}
              />
              {[
                '#FF69B4', '#00B7EB', '#FFFF00', '#98FF98', '#800080', '#4B2E2E', '#FF0000', '#FFFFFF', '#000000',
                '#FFC1CC', '#ADD8E6', '#FFB6C1', '#8B4513', '#FF4040', '#FF1493', '#FF4040', '#00FF00', '#0000FF',
                '#FFFF00', '#FFFFFF', '#000000', '#FFD1DC', '#ADD8E6', '#FFFACD', '#F0E68C', '#DDA0DD', '#FF4500'
              ].map((color) => (
                <button
                  key={color}
                  onClick={() => applyFrameColor(color)}
                  className="buttonFrames w-10 h-10 rounded-full"
                  style={{ backgroundColor: color }}
                ></button>
              ))}
            </div>
            <h3 className="options-label text-xl font-semibold mb-4">Photo Shape</h3>
            <div className="custom-buttons-container flex flex-wrap gap-2 mb-6">
              {[
                { id: 'noneFrameShape', src: 'noneShape.png', alt: 'None', shape: 'none' },
                { id: 'softFrameShape', src: 'squareShape.png', alt: 'Soft Edge', shape: 'soft' },
                { id: 'circleFrameShape', src: 'circleShape.png', alt: 'Circle', shape: 'circle' },
                { id: 'heartFrameShape', src: 'heartShape.png', alt: 'Heart', shape: 'heart' },
              ].map(({ id, src, alt, shape }) => (
                <button
                  key={id}
                  onClick={() => applyFrameShape(shape)}
                  className="buttonShapes w-10 h-10"
                >
                  <img src={`/assets/frame-shapes/${src}`} alt={alt} className="w-full h-full" />
                </button>
              ))}
            </div>
            <h3 className="options-label text-xl font-semibold mb-4">Stickers</h3>
            <div className="custom-buttons-container stickers-container flex flex-wrap gap-2 mb-6">
              {[
                { id: 'noneSticker', src: 'noneShape.png', alt: 'None' },
                { id: 'bunnySticker', src: 'bunny1.png', alt: 'Bunny' },
                { id: 'luckySticker', src: 'lucky1.png', alt: 'Lucky' },
                { id: 'kissSticker', src: 'kiss1.png', alt: 'Kiss' },
                { id: 'sweetSticker', src: 'sweet1.png', alt: 'Sweet' },
                { id: 'ribbonSticker', src: 'ribbon1.png', alt: 'Ribbon' },
                { id: 'sparkleSticker', src: 'sparkle2.png', alt: 'Sparkle' },
                { id: 'pearlSticker', src: 'pearl2.png', alt: 'Pearl' },
                { id: 'confettiSticker', src: 'confetti/confetti.png', alt: 'Confetti' },
                { id: 'ribbonCoquetteSticker', src: 'ribboncq4.png', alt: 'Ribbon Coquette' },
                { id: 'blueRibbonCoquetteSticker', src: 'blueRibbon2.png', alt: 'Blue Ribbon' },
                { id: 'blackStarSticker', src: 'blackStar5.png', alt: 'Black Star' },
                { id: 'yellowChickenSticker', src: 'yellowChicken1.png', alt: 'Yellow Chicken' },
                { id: 'brownBearSticker', src: 'brownyBear6.png', alt: 'Brown Bear' },
                { id: 'lotsHeartSticker', src: 'lotsHeart8.png', alt: 'Lots Heart' },
                { id: 'tabbyCatSticker', src: 'tabbyCat6.png', alt: 'Tabby Cat' },
                { id: 'ballerinaCappuccinoSticker', src: 'ballerinaCappuccino/ballerinaCappuccino3.png', alt: 'Ballerina Cappuccino' },
                { id: 'doggyWhiteSticker', src: 'doggyWhite/doggyWhite1.png', alt: 'Doggy White' },
                { id: 'sakuraBlossomSticker', src: 'sakuraBlossom/sakuraBlossom6.png', alt: 'Sakura Blossom' },
                { id: 'myGirlsSticker', src: 'myGirls/myGirls12.png', alt: 'My Girls' },
                { id: 'classicSticker', src: 'classic1.png', alt: 'Classic Black' },
                { id: 'classicBSticker', src: 'classic4.png', alt: 'Classic White' },
              ].map((sticker, index) => (
                <button
                  key={sticker.id}
                  onClick={() => addSticker(sticker.src, ['top-left', 'top-right', 'bottom-left', 'bottom-right'][index % 4])}
                  className="buttonStickers w-10 h-10 bg-gray-200 hover:bg-gray-300 rounded-lg"
                >
                  <img src={`/assets/stickers/${sticker.src}`} alt={sticker.alt} className="w-full h-full" />
                </button>
              ))}
            </div>
            <div className="custom-logo-holder mb-6">
              <h3 className="options-label text-xl font-semibold mb-4">Logo</h3>
              <div className="logo-container flex gap-2">
                {['eng', 'kor', 'cn'].map(logo => (
                  <button
                    key={logo}
                    onClick={() => addSticker(`${logo}Logo.png`, 'bottom-right')}
                    className="logoCustomBtn bg-gray-200 hover:bg-gray-300 px-4 py-2 rounded-lg uppercase"
                  >
                    {logo}
                  </button>
                ))}
              </div>
            </div>
            <div className="date-overlay mb-6 flex flex-col gap-2">
              <label className="flex items-center gap-2">
                <input
                  type="checkbox"
                  id="dateCheckbox"
                  checked={addDate}
                  onChange={(e) => setAddDate(e.target.checked)}
                />
                Add Date
              </label>
              <label className="flex items-center gap-2">
                <input
                  type="checkbox"
                  id="dateTimeCheckbox"
                  checked={addTime}
                  onChange={(e) => setAddTime(e.target.checked)}
                />
                Add Time
              </label>
            </div>
            <div className="custom-buttons-holder flex gap-4 justify-center">
              <button
                onClick={handleRetake}
                className="sub-button customBtn retake-button-design bg-red-500 hover:bg-red-600 text-white px-6 py-3 rounded-lg shadow-lg"
              >
                Retake
              </button>
              <button
                onClick={handleDownload}
                className="main-button download-button-design bg-pink-500 hover:bg-pink-600 text-white px-6 py-3 rounded-lg shadow-lg"
              >
                Download
              </button>
            </div>
          </div>
        </div>
      </section>
    </main>
  );
}
