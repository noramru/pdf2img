<?php
// $pdf = $_SERVER['DOCUMENT_ROOT'] . '/price.pdsf';

$folder = 'i/'; // Импорт
$target = 'e/'; // Импорт
$pdf = 'price.pdf';
$import_file = $folder . $pdf;
$import_file2 = $target . 'price-0.png';

$jpg = str_replace("pdf", "jpg", $pdf);
// Разбирает и на винде
	// exec("convert -density 300 -colorspace RGB $import_file $target$jpg",$output);
// Работает обрезка
	// exec("convert $import_file2 -trim +repage output.png");
// Тоже разворачивает
	// exec('convert output.png -rotate "-90" output2.png',$output);

/*
	0. Страница авторизации не нужна, нужна UI форма
	1. Создаём папку - проект. Проверяем на существование и добавляем индекс
	2. Импортируем туда PDF с разбором по страницам в /pdf-source/, можно выбрать проект
	3. Переводим в jpg, с потоворотом и обрезкой по заданному шаблону
	4. Собираем HTML-документ и выводим для вида со статистикой - сколько страниц, сколько ценников, сколько пустых наклеек
	5. Генерируем PDF и скачиваем - имя yyyy-mm-dd-имя-проекта.pdf
	6. По желанию удаляем проект.

	/project_1
		/source/ - загружаемые pdf с переименованием
		/pdf/ - разобранные 
		/jpg/ - оптимизированные
		2019-10-30-(имя на кириллице).pdf - изначально не сущетсвует, собирается отдельной кнопкой
		data.json - имя проекта, комментарий

 */

function trim_png($filename) {

    if (preg_match("/\.png$/", $filename) == 0) {
        return;
    }

    $img = new Imagick($filename);
    $img->borderImage("#FFFFFF", 1, 1);
    $img->writeImages(' /e/price-0.jpg', true);

    $img = new Imagick('/e/price-0.jpg');
    $img->trimImage(0);

    $ip = $img->getImagePage();
    list($x, $y) = array($ip['x'], $ip['y']);
    $img->setImagePage(0, 0, 0, 0);
    list($width, $height) = array($img->width, $img->height);

    $img = new Imagick($filename);
    $img->cropImage($width, $height, $x, $y);

    echo $filename.'<br>';
    @unlink($filename);
    $img->writeImages($filename, true);

    $img->destroy();
    @unlink("/e/price-0.jpg");
}
