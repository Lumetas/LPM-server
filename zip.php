<?php
function zip_folder( $source, $destination, $include_sourse = true ){

	$source = str_replace( '\\', '/', rtrim( realpath( $source ), '/' ) );

	if( ! file_exists( $source ) )
		return 'Error: file not exists';

	$zip = new ZipArchive();
	if( ! $zip->open( $destination, ZIPARCHIVE::CREATE | ZIPARCHIVE::OVERWRITE ) )
		return 'Error: ZipArchive not installed';

	if( $include_sourse )
		$zip->addEmptyDir( basename($source) );

	if( is_file( $source ) ){
		$zip->addFile( $source );
	}
	elseif( is_dir( $source ) ){

		foreach( new RecursiveIteratorIterator( new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST )
			as $file_path => $file_obj ){
			// Ignore . | .. folders
			if( preg_match('~/[.]{1,2}$~', $file_path) )
				continue;

			$file_rel_path = str_replace( "$source/", '', $file_path );
			if( $include_sourse )
				$file_rel_path = basename($source) .'/'. $file_rel_path;

			if( is_dir( $file_path ) ){
				$zip->addEmptyDir( $file_rel_path );
			}
			elseif( is_file( $file_path ) ){
				$zip->addFile( $file_path, $file_rel_path );
			}
		}

	}

	$zip->close();

	return 'Done';
}

function unzip_file( $file_path, $dest ){
	$zip = new ZipArchive;

	if( ! is_dir($dest) ) return 'Нет папки, куда распаковывать...';

	// открываем архив
	if( true === $zip->open($file_path) ) {

		 $zip->extractTo( $dest );

		 $zip->close();

		 return true;
	}
	else
		return 'Произошла ошибка при распаковке архива';
}




function dirDel ($dir) 
{  
    $d=opendir($dir);  
    while(($entry=readdir($d))!==false) 
    { 
        if ($entry != "." && $entry != "..") 
        { 
            if (is_dir($dir."/".$entry)) 
            {  
                dirDel($dir."/".$entry);  
            } 
            else 
            {  
                unlink ($dir."/".$entry);  
            } 
        } 
    } 
    closedir($d);  
    rmdir ($dir);  
 } 


function send_file($file) {
  if (file_exists($file)) {
    // сбрасываем буфер вывода PHP, чтобы избежать переполнения памяти выделенной под скрипт
    // если этого не сделать файл будет читаться в память полностью!
    if (ob_get_level()) {
      ob_end_clean();
    }
    // заставляем браузер показать окно сохранения файла
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename=' . basename($file));
    header('Content-Transfer-Encoding: binary');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    // читаем файл и отправляем его пользователю
    readfile($file);
    exit;
  }
}
