12. package.json
 — Dependency Misplacements
Beberapa package di dependencies seharusnya di devDependencies:

Package	Seharusnya
typescript vite @vitejs/plugin-react @tailwindcss/vite tailwindcss concurrently globals

13. Pinned @rollup Versions Sangat Outdated
package.json:66-67
: @rollup/rollup-* di-pin ke 4.9.5 tapi project menggunakan Vite 7 yang membutuhkan Rollup 4.x terbaru. Pin ini bisa menyebabkan incompatibility warnings.