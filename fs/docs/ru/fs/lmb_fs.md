# Класс lmbFs
Класс lmbFs — применяется для работы с файловой системой:

* Создание, копирование и удаление папок рекурсивно.
* Нормализация путей до файлов в каталогов.
* Обход по папкам рекурсивно.

lmbFs — полностью статичный класс

## Наиболее часто используемые методы
Метод | Назначение
------|-----------
**mkdir**($dir, $perm=0777, $parents=true) | Рекурсивно создает директории по указанному пути
**rm**($path) | Рекурсивно удаляет директории по указанному пути или файл. Удаляет директории, даже если они непустые (в отличие от стандартной rmdir)
**safeWrite**($file, $content, $perm=0664) | Записывает контент в указанный файл наиболее безопасным способом.
**dirpath**($path) | Возвращает имя последнего каталога из пути $path
**cp**($src, $dest, $as_child = false, $include_regex = '', $exclude_regex = '', $include_hidden = false) | Рекурсивно копирует файлы или каталоги в другой каталог или файл
**normalizePath**($path, $to_type = self :: UNIX) | Нормализует путь $path
**find**($dir, $types = 'dfl', $include_regex = '', $exclude_regex = '', $add_path = true, $include_hidden = false) | Используется для поиска файлов и каталогов по определенным критериям.
**recursiveFind**($path, $types = 'dfl', $include_regex = '', $exclude_regex = '', $add_path = true, $include_hidden = false) | Рекурсивная версия метода find
**walkDir**($dir, $function_def, $params=array(), $include_first=false) | Используется для рекурсивного обхода директорий и применения функции $function_def к каждому элементу

Подробнее смотри тесты в lmbFsTest.class.php
