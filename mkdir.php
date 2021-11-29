<?php
if (!file_exists('docs')) {
    mkdir('docs', 0777, true);
    echo 'docs directory created.'. PHP_EOL;
}
if (!file_exists('temp')) {
    mkdir('temp', 0777, true);
    echo 'temp directory created.'. PHP_EOL;
}
if (!file_exists('media/proPic')) {
    mkdir('media/proPic', 0777, true);
    echo 'media/proPic directory created.'. PHP_EOL;
}

//Script completion message
echo '----------------------------------------------'. PHP_EOL;
echo 'Required directories created.'. PHP_EOL;
