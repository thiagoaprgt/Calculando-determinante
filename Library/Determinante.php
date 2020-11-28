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

            session_destroy();

            $this->getOrdem();


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
        

        // converte para o formato de matriz qualquer array genérico e mostra a matriz
        
        public function mostra_Matriz(Array $m) {

            

            $l =0;
            $c =0;

            $elementos = 0;

            $ordem = 0;


            print_r($m);

            echo "<br>";
            echo "<br>";           


            foreach($m as $k => $v) {

                $elementos++;
            }

            $ordem = pow($elementos, 0.5);

            

            
            echo 'A ordem é: '. $ordem;

            echo "<br>";
            echo "<br>";

            $matriz = array();

            
            echo "<br>";
            echo "<br>";

            foreach($m as $k => $v) {

                if($c == $ordem) {
                    $c = 0;
                    $l++;

                    echo "<br>";
                    echo "<br>";
                    
                }

                $matriz[$l][$c] = $v;

                
                echo $matriz[$l][$c] . " ";

                $c++;

            }

            echo "<br>";
            echo "<br>";

            

            $matriz = ['ordem' => $ordem, 'matriz' => $matriz];

            
            echo "<br>";
            echo "<br>";

            print_r($matriz);

            return $matriz;


        }

        /**
         * converte para o formato de matriz qualquer array genérico e não a mostra a matriz 
         * pois não possui os echo e os print_r
        */            
        
        public function matriz(Array $m) {

            $l =0;
            $c =0;
            $elementos = 0;
            $ordem = 0;

            foreach($m as $k => $v) {

                $elementos++;
            }

            $ordem = pow($elementos, 0.5); 
            
            $matriz = array();

            foreach($m as $k => $v) {

                if($c == $ordem) {
                    $c = 0;
                    $l++;
                                        
                }

                $matriz[$l][$c] = $v;  

                $c++;

            }
           
            $matriz = ['ordem' => $ordem, 'matriz' => $matriz];

            return $matriz;


        }


        // pega a matriz que foi enviada pela URL através do get

        public function getMatriz() {

            

            unset($_GET['function']);            

            $_SESSION['determinante'] = $_GET;            
            
            echo "<br>";
            echo "<br>";

            

            $m = $this->mostra_Matriz($_SESSION['determinante']);  
            
            echo "<br>";
            echo "<br>";

            

            echo "Redutor de ordem Chió:";

            echo "<br>";
            echo "<br>";

             /**
                 * A variável coeficiente_final é um número inteiro será usado para multiplicar o resultado da
                 * última iteração da regra de chio, pois caso o determinante não um elemento com o
                 * valor igual a 1 na linha então é necessário dividir os elementos do Determinante 
                 * para forçar o elemento de valor igual a 1.
                 * Portanto no final das iterações será necessário fazer o reajuste no cálculo final
                 * do determinante, esse ajuste é feito pela variável coeficiente_final
            */  


                                
                
                $coeficiente_final = 1;
            
            $this->Redutor_De_Ordem_Chio($m, 1);

        }



        

        

        public function Redutor_De_Ordem_Chio(Array $matriz, int $coeficiente_final) {

            // Para usar Chio tem que haver algum elemento cujo valor seja igual 1
           
            echo "Antes da redução de ordem:";

            echo "<br>";
            echo "<br>";            

            $m = $matriz;            
            
            $linha = 0;
            $coluna = 0;
            
            
            if($m['ordem'] == 1) {

                /**
                 * o coeficiente_final será usado para multiplicar o resultado da
                 * última iteração da regra de chio
                */                  

                $resultado = $m['matriz'][0][0] * $coeficiente_final;

                echo "Coeficiente final: " . $coeficiente_final;

                echo "<br>";

                echo "O resultado do Determinante é: " . $resultado;

                return $resultado;
                
            }else{
                
                
                $determinante = $m['matriz'];

                print_r($determinante);

                echo "<br>";
                echo "<br>"; 

                //ira força o número 1 em um elemento da primeira linha

                for ($i=0; $i < $m['ordem']; $i++) {   

                    
                    
                    if($determinante[0][$i] != 0) {

                        $coeficiente = $determinante[0][$i];

                        $linha = 0;
                        $coluna = $i;


                        break;
    

                    }

                    

                } 


                /**
                 * A regra de Chió só ser aplicado no elemento que está primeira linha e na primeira coluna cujo valor é igual a 1.
                 * Como nem todos o determinante vão satisfazer essa condição, será nesse inverter algumas fileiras (linha ou coluna)
                 * e também dividir as  fileiras para forçar que o primeiro elemento do determinante tenha valor igual a 1.
                 * 
                 * Logo serão usados as propriedades do determinante:
                 *  Uma fileira (linha ou coluna) nula resulta em um determinante de valor igual a zero;
                 *  Para cada inversão de fileiro inverte-se o sinal do valor do determinante;
                 *  Multiplicar uma fileira por valor faz com o resultado do determinante seja multiplicado pelo mesmo valor
                */

                
                
                
                

                /**
                 * o coeficiente_final será usado para multiplicar o resultado da
                 * última iteração da regra de chio
                 * 
                */  


                /**
                 * Inversão de fileira.
                 * Só a primeira linha será válidada, pois se todos os elementos dela forem nulos então o 
                 * determinante será nulo.
                 * Caso o primeiro seja zero e os outros diferente de zero então será necessário uma inversão
                 * que matematicamente signica multiplicar o valor do determinante pelo valor -1
                 * 
                */

                $fileira = $linha + $coluna;

                $inverte_fileira = ( $fileira == 0 ) ? 1 : -1;
                                
                
                $coeficiente_final *= $inverte_fileira * $coeficiente;                
                
                
                
                echo "Coeficiente: " . $coeficiente;                
                echo "<br>";
                echo "Coeficiente final: " . $coeficiente_final;                
                echo "<br>";
                echo "Linha: " . $linha;
                echo "<br>"; 
                echo "Coluna: " . $coluna;

                echo "<br>";
                echo "<br>";


                

                
                

                for ($i=0; $i < $m['ordem']; $i++) {  
                    
                    // analisando as colunas
                    
                    $determinante[0][$i] = $m['matriz'][0][$i] / $coeficiente;                    

                
                }


                


                echo "Analisando o determinante parte 1";

                echo "<br>";
                echo "<br>";


                print_r($determinante);

                echo "<br>";
                echo "<br>";
                echo "<br>";
                echo "<br>";

                 
                
                
                // ---------------- daqui pra cima esta certo ---------------------
                
                
                                  
                // aplicando a regra de Chió

                

                $chio = array();

                for ($i=0; $i < $m['ordem']; $i++) {
                    
                    for ($j=0; $j < $m['ordem'] ; $j++) { 

                        
                        if($i != $linha && $j != $coluna) {
                            

                            $det[] = $determinante[$i][$j];
                           

                            $chio[] = $determinante[$i][$j] -( $determinante[$linha][$j] * $determinante[$i][$coluna] );

                            

                        }
                                        

                    }
                    
                }


                // Vai permitir a usar recursividade pois deixará o array no formato certo

                $chio = $this->mostra_Matriz($chio);



                // Debug                


                echo "Depois da redução de ordem:";

                echo "<br>";
                echo "<br>";

               

                echo "Para Debug: Matriz em processo de redução de ordem: ";
                echo "<br>";
                print_r($det);
                
                echo "<br>";
                echo "<br>";

                echo "Redução de Chió Completa: ";
                echo "<br>";                

                print_r($chio);


                echo "<br>";
                echo "<br>";

                echo "---------------------------------------------------------";

                echo "<br>";

                echo "Nova redução de ordem do Determinante";

                echo "<br>";

                echo "---------------------------------------------------------";


                echo "<br>";
                echo "<br>";


                





                // ---------------- daqui pra cima esta certo ---------------------

                // Usando a recursidade para chegar na matriz de ordem 1

                

                $this->Redutor_De_Ordem_Chio($chio, $coeficiente_final);

                 


            }


            
            


        }

        

        
    }



?>