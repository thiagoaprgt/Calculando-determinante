<?php

    // foi adotado o design patter Front Controller 

    use Library\Determinante;
    

    require_once 'Library/Determinante.php';


    $template = file_get_contents('templates/Home.html');

    ob_start();

    $obj = new Library\Determinante();   
    

    $output = ob_get_contents();


    ob_end_clean();


    $template = str_replace('{content}', $output, $template);

    echo $template;


    



    


    

    

    



?>