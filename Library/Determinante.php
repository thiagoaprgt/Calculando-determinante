<?php

    namespace Library;
    


    class Determinante {

        
        protected $matriz;


        public function __construct() {

            // verificando se é uma matrix quadrada, N linhas e N colunas

            
            session_start();

            if(empty($_GET['ordem']) && !isset($_SESSION['ordem'])) {

                echo $this->getOrdem();

                print_r($_SESSION);

            }else {

                

                // call_user_func chama a função da classe através de um array, array(classe, função que será chamada da classe escolhida)

                call_user_func( array($this, $_GET['function'] ) );                
                
                echo "<br>";                
                
                
                $this->reiniciar();

                //print_r($_SESSION);


            }


        }


        public function destroy() {

            return session_destroy();

        }


        public function reiniciar() {

            $form = file_get_contents('templates/Elementos_matriz.html');

            $form = str_replace('{function}', 'destroy', $form);

            $submit =  "<br>"." <input type=\"submit\" value=\"Reiniciar\"> ";

            $form = str_replace('{elementos}', $submit, $form);            

            echo $form;
        }        

       

        public function getOrdem() {

            
            
            $form = " <form action=\"index.php\" method=\"get\"> "; 
            
            $form .= "<input type=\"hidden\" name=\"function\" value=\"matrizQuadrada\" >";

            $label = "<label>Digite a ordem do Determinante </label>";

            $input = "<input type=\"number\" name=\"ordem\">";

            $submit =  "<br>"." <input type=\"submit\" value=\"enviar\"> ";


            $form .= $form . $label . $input . $submit . "</form>";

            echo $form;

            

        }


        
        

        public function matrizQuadrada() {

            $_SESSION['ordem'] = $_GET['ordem'];


            $form = file_get_contents('templates/Elementos_matriz.html');

            $label = "<label>elemento a{linha}{coluna} </label>";

            $input = "<input type=\"number\" name=\"a{linha}{coluna}\">";
            

            $submit =  "<br>"." <input type=\"submit\" value=\"enviar\"> ";


            $linha = 0;
            $coluna = 0;
            $ordem = $_SESSION['ordem'];
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

            $form = str_replace('{function}', 'getMatriz', $form);

            $form = str_replace('{elementos}', $fields.$submit, $form);

            echo $form;
            

           
        }

        public function getMatriz() {

            

            unset($_GET['function']);            

            $_SESSION['determinante'] = $_GET;            
            
            echo "<br>";
            echo "<br>";

            

            $this->calculaDeterminanteLaplace();

            

        }



        public function determinante() {

            $ordem = $_SESSION['ordem'];
            $matriz = $_SESSION['determinante'];

            $n = $ordem - 1;

            $l =0;
            $c =0;

            foreach($matriz as $k => $v) {

                if($c == $ordem) {
                    $c = 0;
                    $l++;

                    echo "<br>";
                    
                }

                $m[$l][$c] = $v;

                echo $m[$l][$c];

                $c++;

            }

            echo "<br>";
            echo "<br>";


            $_SESSION['determinante'] = $m;
            
            
            return $_SESSION['determinante'];         

            

        }



        public function calculaDeterminanteLaplace() {

            $m = $this->determinante();

            $ordem = $_SESSION['ordem'];

            print_r($m);

           


        }


        

        

        
        
    }



?>