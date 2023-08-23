<?php
if (!file_exists('password')){
    if(!isset($_GET['pass'])){
        echo "Пожалуйста отправьте гет запрос по типу 'example.com:8000?pass=123' Где 123 - ваш будующий пароль";
        echo "Please send a get request like 'example.com:8000?pass=123' Where 123 is your future password";
        exit;
        
    }
    else{
        file_put_contents('password', $_GET['pass']);
        mkdir('ps');
        exit;
    }
}

if (!file_exists('ps')){
    mkdir('ps');
}

if ($_GET['password'] != file_get_contents('password')){exit;}

include('zip.php');

if ($_GET['func'] == 'commit'){
if ($_FILES && $_FILES["data"]["error"]== UPLOAD_ERR_OK)
{
    $name = $_FILES["data"]["name"];
    move_uploaded_file($_FILES["data"]["tmp_name"], $name);
    echo "ok";
}
if (file_exists('./ps/'.$_GET['name'])){
    dirDel('./ps/'.$_GET['name']);
}
mkdir('./ps/'.$_GET['name']);
unzip_file('main.zip', './ps/'.$_GET['name']);
unlink('main.zip');


}
if ($_GET['func'] == 'get'){
    zip_folder(__DIR__.'/ps/'.$_GET['name'], __DIR__.'/ps/main.zip', false);
    send_file(__DIR__.'/ps/main.zip');
    unlink(__DIR__.'/ps/main.zip');
}


if ($_GET['func'] == 'clone'){
    zip_folder(__DIR__.'/ps/'.$_GET['name'], __DIR__.'/ps/main.zip', true);
    send_file(__DIR__.'/ps/main.zip');
    unlink(__DIR__.'/ps/main.zip');
}

if ($_GET['func'] == 'remove'){
    dirDel('./ps/'.$_GET['name']);
    echo 'ok';
}
if ($_GET['func'] == 'init'){
    echo shell_exec('cd ./ps/'.$_GET['name'].' ; bash -c "$(sed -n 3p lumake)" &');
}
if ($_GET['func'] == 'run'){
    echo shell_exec('cd ./ps/'.$_GET['name'].' ; bash -c "$(sed -n 4p lumake)" & ');
}

if ($_GET['func'] == 'build'){
    echo shell_exec('cd ./ps/'.$_GET['name'].' ; bash -c "$(sed -n 5p lumake)" & ');
}
if ($_GET['func'] == 'version'){
    echo shell_exec('cd ./ps/'.$_GET['name'].' ; echo "version: $(sed -n 6p lumake)"');
}
