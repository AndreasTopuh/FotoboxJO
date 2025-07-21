import { useState } from 'react';
import { useNavigate } from 'react-router-dom';

export default function Landing() {
  const navigate = useNavigate();
  const [isUnlocked, setIsUnlocked] = useState(false);
  const [passwordInput, setPasswordInput] = useState('');
  const [showPopup, setShowPopup] = useState(true);
  const correctPassword = '1386';

  const handlePasswordSubmit = () => {
    if (passwordInput === correctPassword) {
      setIsUnlocked(true);
      setShowPopup(false);
    } else {
      alert('Password salah! Coba lagi.');
    }
  };

  return (
    <div className="relative w-screen h-screen text-white overflow-y-scroll snap-y snap-mandatory scroll-smooth">

      {/* 🔒 POP-UP Password */}
      {showPopup && (
        <div className="absolute inset-0 bg-black bg-opacity-80 flex items-center justify-center z-50">
          <div className="bg-white text-black rounded-lg p-8 shadow-lg max-w-sm w-full text-center">
            <h2 className="text-2xl font-bold mb-4">Masukkan Kode Akses</h2>
            <input
              type="password"
              placeholder=""
              value={passwordInput}
              onChange={(e) => setPasswordInput(e.target.value)}
              className="w-full px-4 py-2 mb-4 border rounded focus:outline-none focus:ring-2 focus:ring-pink-400"
            />
            <button
              onClick={handlePasswordSubmit}
              className="bg-pink-400 hover:bg-pink-500 text-white px-6 py-2 rounded font-semibold transition"
            >
              Masuk
            </button>
          </div>
        </div>
      )}

      {/* Bagian 1: Headline */}
      <div className="h-screen flex items-center justify-center snap-start px-4 text-center">
        <div className="space-y-8">
          <div className="text-5xl lg:text-6xl font-extrabold drop-shadow-lg">
            FotoboxJO
          </div>
          <button
            disabled={!isUnlocked}
            className={`${
              isUnlocked
                ? 'bg-pink-400 hover:bg-pink-500'
                : 'bg-gray-400 cursor-not-allowed'
            } transition px-8 py-4 lg:px-10 lg:py-5 rounded-full text-lg lg:text-xl font-semibold shadow-md text-white`}
            onClick={() => navigate('/selectpayment')}
          >
            Ketuk Untuk Mulai
          </button>
        </div>
      </div>

      {/* Bagian 2 & 3: Biarkan tetap bisa scroll, tapi tombol tetap disable kalau belum unlock */}
      <div className="h-screen flex items-center justify-center snap-start px-4 text-center">
        <div className="w-[90vw] max-w-3xl backdrop-blur-md bg-white/10 border border-white/20 rounded-2xl px-10 py-16 shadow-2xl space-y-6">
          <h2 className="text-3xl lg:text-4xl font-bold text-white drop-shadow mb-4">Apa itu FotoboxJO?</h2>
          <p className="text-base lg:text-lg text-white/80 leading-relaxed">
            FotoboxJO adalah layanan photobooth otomatis yang dirancang untuk event kekinian.
            Kamu bisa memilih frame, mengambil foto dari kamera langsung, edit dengan stiker dan filter,
            lalu mencetak hasilnya atau mengirim ke email. Semua dilakukan dalam satu alur yang cepat dan fun!
          </p>
        </div>
      </div>

      <div className="h-screen flex flex-col items-center justify-center snap-start px-4 text-center">
        <div className="w-[90vw] max-w-3xl backdrop-blur-md bg-white/10 border border-white/20 rounded-2xl p-10 shadow-2xl">
          <h2 className="text-3xl lg:text-4xl font-bold text-white mb-6">Contoh Hasil Foto</h2>
          <div className="backdrop-blur-sm bg-white/10 border border-white/20 rounded-xl p-4 shadow-md inline-block">
            <img
              src="https://placehold.co/300x200?text=Contoh+Hasil"
              alt="Contoh hasil fotobox"
              className="rounded-lg"
            />
            <p className="mt-2 text-sm text-white/70">Contoh hasil fotomu di FotoboxJO</p>
          </div>
        </div>
        <div className="text-sm text-white/60 mt-6">
          © {new Date().getFullYear()} @GoFotobox
        </div>
      </div>
    </div>
  );
}
