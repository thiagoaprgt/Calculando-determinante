<?php

    namespace Library;
    


    class Determinante {

        
        protected $matrix = array();


        public function __construct() {

            // verificando se é uma matrix quadrada, N linhas e N colunas

            
            session_start();
            
            if( isset($_GET['ordem']) && !isset($_SESSION['ordem']) ) {

                $_SESSION['ordem'] = $_GET['ordem'];

                echo "A ordem do determinante é: " . $_SESSION['ordem'] . "<br>";

                if($_SESSION['ordem'] <= 0) {

                    exit("Digite um número maior que 0");

                }

            }else if ( !isset($_SESSION['ordem']) ) {

                echo $this->getOrdem();

            }


            if( isset($_SESSION['ordem']) ) {

               echo $this->matrizQuadrada($_SESSION['ordem']);

               

            }


            session_destroy();

            

        }


        public function getOrdem() {

            
            
            $form = " <form action=\"index.php\" method=\"get\"> ";                

            $label = "<label>Digite a ordem do Determinante </label>";

            $input = "<input type=\"number\" name=\"ordem\">";

            $submit =  "<br>"." <input type=\"submit\" value=\"enviar\"> ";


            $form .= $form . $label . $input . $submit . "</form>";

            return $form;

            

        }
        

        public function matrizQuadrada($ordem) {

            $form = file_get_contents('templates/Elementos_matriz.html');

            $label = "<label>elemento a{linha}{coluna} </label>";

            $input = "<input type=\"number\" name=\"a{linha}{coluna}\">";
            

            $submit =  "<br>"." <input type=\"submit\" value=\"enviar\"> ";


            $linha = 0;
            $coluna = 0;
            $elementos = pow($ordem, 2);
            $n = $ordem - 1;

            $contador = 0;

            $fields = '';


            while($contador < $elementos) {
                

                if($coluna == $ordem) {

                    $linha++;
                    $coluna = 0;

                    $div = "</div><br>";

                    $fields .= $div;

                }
                
                if($coluna == 0) {

                    $div = "<div>";
                    
                    $fields .= $div;


                }


                $field = $label.$input;

                $field = str_replace('{linha}', $linha, $field);
                $field = str_replace('{coluna}', $coluna, $field);

                $fields .= $field;




                $coluna++;
                $contador++;    
                
                
                

            }

            $form = str_replace('{elementos}', $fields.$submit, $form);

            return $form;
            

           
        }

        

        
        
    }



?>