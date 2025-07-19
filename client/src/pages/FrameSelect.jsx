const frames = [
  '/frame/finalframe1.png',
  '/frame/finalframe2.png',
  '/frame/finalframe3.png',
  '/frame/finalframe4.png',
];

const descriptions = [
  '8x take foto, durasi 7 menit.',
  '6x take foto, durasi 7 menit.',
  '4x take foto, durasi 7 menit.',
  '4x take foto, durasi 7 menit.',
];

const photoCounts = [6, 8, 6, 4];

export default function FrameSelect() {
  return (
    <div className="p-6 text-center">
      <h2 className="text-3xl font-bold mb-6">Pilih Frame</h2>
      <div className="flex flex-wrap justify-center gap-6">
        {frames.map((frame, i) => (
          <a
            key={i}
            href={`/camera?frame=${i}&photos=${photoCounts[i]}`}
            className="flex flex-col items-center bg-white border border-gray-200 rounded-lg shadow-sm md:flex-row md:max-w-xl hover:bg-gray-100 dark:border-gray-700 dark:bg-gray-800 dark:hover:bg-gray-700"
          >
            <img
              className="object-cover w-full rounded-t-lg h-64 md:h-auto md:w-48 md:rounded-none md:rounded-s-lg"
              src={frame}
              alt={`Frame ${i + 1}`}
            />
            <div className="flex flex-col justify-between p-4 leading-normal text-left">
              <h5 className="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                Frame {i + 1}
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