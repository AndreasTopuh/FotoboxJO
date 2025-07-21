const layoutPaths = [
  '/frame/layout/finallayout1.png',
  '/frame/layout/finallayout2.png',
  '/frame/layout/finallayout3.png',
  '/frame/layout/finallayout4.png',
  '/frame/layout/finallayout5.png',
  '/frame/layout/finallayout6.png',
];

const descriptions = [
  '2x take foto, durasi 7 menit.',
  '4x take foto, durasi 7 menit.',
  '6x take foto, durasi 7 menit.',
  '8x take foto, durasi 7 menit.',
  '5x take foto, durasi 7 menit.', // Updated to 5x
  '4x take foto, durasi 7 menit.',
];

const photoCounts = [2, 4, 6, 8, 5, 4];

export default function FrameSelect() {
  return (
    <div className="p-6 text-center">
      <h2 className="text-3xl font-bold mb-6">Pilih Layout</h2>
      <div className="flex flex-wrap justify-center gap-6">
        {layoutPaths.map((frame, i) => (
          <a
            key={i}
            href={`/frametemplate?layout=${encodeURIComponent(frame)}&photos=${photoCounts[i]}`}
            className="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
          >
            <img
              className="object-cover w-full rounded-t-lg h-64 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
              src={frame}
              alt={`Layout ${i + 1}`}
            />
            <div className="flex flex-col justify-between p-4 leading-normal text-left">
              <h5 className="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                Layout {i + 1}
              </h5>
              <p className="mb-3 font-normal text-gray-700 dark:text-gray-400">
                {descriptions[i]}
              </p>
            </div>
          </a>
        ))}
      </div>
    </div>
  );
}