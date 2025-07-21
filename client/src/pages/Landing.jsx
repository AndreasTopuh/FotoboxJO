import { useNavigate } from 'react-router-dom';

export default function Landing() {
  const navigate = useNavigate();

  return (
    <div className="w-screen h-screen text-white overflow-y-scroll snap-y snap-mandatory scroll-smooth">
      {/* Section 1: Headline */}
      <section className="h-screen flex items-center justify-center snap-start px-4 text-center">
        <div className="space-y-8">
          <div className="text-5xl lg:text-6xl font-extrabold drop-shadow-lg">
            FotoboxJO
          </div>
          <button
            className="bg-pink-400 hover:bg-pink-500 transition px-8 py-4 lg:px-10 lg:py-5 rounded-full text-lg lg:text-xl font-semibold shadow-md text-white"
            onClick={() => navigate('/selectpayment')}
          >
            Ketuk Untuk Mulai
          </button>
        </div>
      </section>

      {/* Section 2: Deskripsi */}
      <section className="h-screen flex items-center justify-center snap-start px-4">
        <div className="w-[90vw] max-w-3xl backdrop-blur-md bg-white/10 border border-white/20 rounded-2xl px-10 py-16 text-center shadow-2xl space-y-6">
          <h2 className="text-3xl lg:text-4xl font-bold text-white drop-shadow mb-4">Apa itu FotoboxJO?</h2>
          <p className="text-base lg:text-lg text-white/80 leading-relaxed">
            FotoboxJO adalah layanan photobooth otomatis yang dirancang untuk event kekinian.
            Kamu bisa memilih frame, mengambil foto dari kamera langsung, edit dengan stiker dan filter,
            lalu mencetak hasilnya atau mengirim ke email. Semua dilakukan dalam satu alur yang cepat dan fun!
          </p>
        </div>
      </section>

      {/* Section 3: Contoh Hasil */}
      <section className="h-screen flex flex-col items-center justify-center snap-start px-4">
        <div className="w-[90vw] max-w-3xl backdrop-blur-md bg-white/10 border border-white/20 rounded-2xl p-10 text-center shadow-2xl">
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

        {/* Footer */}
        <div className="text-sm text-white/60 mt-6">© {new Date().getFullYear()} @GoFotobox</div>
      </section>
    </div>
  );
}
