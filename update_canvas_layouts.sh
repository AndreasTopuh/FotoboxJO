#!/bin/bash

# Update canvasLayout3.js for 6 photos
sed -i 's/canvasLayout1.php/canvasLayout3.php/g' /var/www/html/FotoboxJO/src/pages/canvasLayout3.js
sed -i 's/0\/2/0\/6/g' /var/www/html/FotoboxJO/src/pages/canvasLayout3.js
sed -i 's/< 2; i++/< 6; i++/g' /var/www/html/FotoboxJO/src/pages/canvasLayout3.js
sed -i 's/< 1) await/< 5) await/g' /var/www/html/FotoboxJO/src/pages/canvasLayout3.js
sed -i 's/=== 2/=== 6/g' /var/www/html/FotoboxJO/src/pages/canvasLayout3.js
sed -i 's/>= 2/>=6/g' /var/www/html/FotoboxJO/src/pages/canvasLayout3.js
sed -i 's/have 2 pictures/have 6 pictures/g' /var/www/html/FotoboxJO/src/pages/canvasLayout3.js
sed -i 's/6 images/6 images/g' /var/www/html/FotoboxJO/src/pages/canvasLayout3.js
sed -i 's/customizeLayout1.php/customizeLayout3.php/g' /var/www/html/FotoboxJO/src/pages/canvasLayout3.js

# Update canvasLayout4.js for 8 photos  
sed -i 's/canvasLayout1.php/canvasLayout4.php/g' /var/www/html/FotoboxJO/src/pages/canvasLayout4.js
sed -i 's/0\/2/0\/8/g' /var/www/html/FotoboxJO/src/pages/canvasLayout4.js
sed -i 's/< 2; i++/< 8; i++/g' /var/www/html/FotoboxJO/src/pages/canvasLayout4.js
sed -i 's/< 1) await/< 7) await/g' /var/www/html/FotoboxJO/src/pages/canvasLayout4.js
sed -i 's/=== 2/=== 8/g' /var/www/html/FotoboxJO/src/pages/canvasLayout4.js
sed -i 's/>= 2/>= 8/g' /var/www/html/FotoboxJO/src/pages/canvasLayout4.js
sed -i 's/have 2 pictures/have 8 pictures/g' /var/www/html/FotoboxJO/src/pages/canvasLayout4.js
sed -i 's/2 images/8 images/g' /var/www/html/FotoboxJO/src/pages/canvasLayout4.js
sed -i 's/customizeLayout1.php/customizeLayout4.php/g' /var/www/html/FotoboxJO/src/pages/canvasLayout4.js

# Update canvasLayout5.js for 6 photos
sed -i 's/canvasLayout1.php/canvasLayout5.php/g' /var/www/html/FotoboxJO/src/pages/canvasLayout5.js
sed -i 's/0\/2/0\/6/g' /var/www/html/FotoboxJO/src/pages/canvasLayout5.js
sed -i 's/< 2; i++/< 6; i++/g' /var/www/html/FotoboxJO/src/pages/canvasLayout5.js
sed -i 's/< 1) await/< 5) await/g' /var/www/html/FotoboxJO/src/pages/canvasLayout5.js
sed -i 's/=== 2/=== 6/g' /var/www/html/FotoboxJO/src/pages/canvasLayout5.js
sed -i 's/>= 2/>= 6/g' /var/www/html/FotoboxJO/src/pages/canvasLayout5.js
sed -i 's/have 2 pictures/have 6 pictures/g' /var/www/html/FotoboxJO/src/pages/canvasLayout5.js
sed -i 's/2 images/6 images/g' /var/www/html/FotoboxJO/src/pages/canvasLayout5.js
sed -i 's/customizeLayout1.php/customizeLayout5.php/g' /var/www/html/FotoboxJO/src/pages/canvasLayout5.js

# Update canvasLayout6.js for 4 photos
sed -i 's/canvasLayout1.php/canvasLayout6.php/g' /var/www/html/FotoboxJO/src/pages/canvasLayout6.js
sed -i 's/0\/2/0\/4/g' /var/www/html/FotoboxJO/src/pages/canvasLayout6.js
sed -i 's/< 2; i++/< 4; i++/g' /var/www/html/FotoboxJO/src/pages/canvasLayout6.js
sed -i 's/< 1) await/< 3) await/g' /var/www/html/FotoboxJO/src/pages/canvasLayout6.js
sed -i 's/=== 2/=== 4/g' /var/www/html/FotoboxJO/src/pages/canvasLayout6.js
sed -i 's/>= 2/>= 4/g' /var/www/html/FotoboxJO/src/pages/canvasLayout6.js
sed -i 's/have 2 pictures/have 4 pictures/g' /var/www/html/FotoboxJO/src/pages/canvasLayout6.js
sed -i 's/2 images/4 images/g' /var/www/html/FotoboxJO/src/pages/canvasLayout6.js
sed -i 's/customizeLayout1.php/customizeLayout6.php/g' /var/www/html/FotoboxJO/src/pages/canvasLayout6.js

echo "All canvas layout JS files updated!"
