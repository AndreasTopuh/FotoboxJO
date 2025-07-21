import { useNavigate } from 'react-router-dom';

export default function Landing() {
  const navigate = useNavigate();

  return (
    <div className="relative w-screen h-screen overflow-hidden text-white bg-gray-900">
      {/* Box tengah (utama) */}
      <div className="absolute top-1/2 left-1/2 transform -translate-x-1/2 -translate-y-1/2">
        <div className="w-[90vw] max-w-3xl backdrop-blur-md bg-white/10 border border-white/20 rounded-2xl px-10 py-16 text-center shadow-2xl space-y-8">
          
          {/* Judul */}
          <div className="text-5xl lg:text-6xl font-extrabold drop-shadow-lg">
            FotoboxJO
          </div>

          {/* Deskripsi Website */}
          <div className="text-base lg:text-lg text-white/80">
            Selamat datang di <span className="font-semibold text-white">FotoboxJO</span> – 
            layanan photobooth otomatis yang seru dan praktis! Abadikan momenmu,
            tambahkan filter dan stiker lucu, lalu cetak atau kirim langsung ke email.
          </div>

          {/* Tombol Mulai */}
          <button
            className="bg-pink-400 hover:bg-pink-500 transition px-8 py-4 lg:px-10 lg:py-5 rounded-full text-lg lg:text-xl font-semibold shadow-md text-white"
            onClick={() => navigate('/selectpayment')}
          >
            Ketuk Untuk Mulai
          </button>

          {/* Preview Hasil Fotobox */}
          <div className="backdrop-blur-sm bg-white/10 border border-white/20 rounded-xl p-4 shadow-md inline-block">
            <img
              src="https://placehold.co/300x200?text=Contoh+Hasil"
              alt="Contoh hasil fotobox"
              className="rounded-lg"
            />
            <p className="mt-2 text-sm text-white/70">Contoh hasil fotomu di FotoboxJO</p>
          </div>
        </div>
      </div>

      {/* Copyright bawah */}
      <div className="absolute bottom-4 w-full text-center text-sm text-white/70">
        © {new Date().getFullYear()} @GoFotobox
      </div>
    </div>
  );
}
