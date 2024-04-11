<?php

function sortearElemento(array $array)
{
    // var_dump($array);
    $indiceSorteado = array_rand($array);
    return $array[$indiceSorteado];
    // var_dump($array[$indiceSorteado]);
}

$call = function ($array) {
    // print_r($array);
    return str_getcsv($array, ";");
};


try {

    // Nome do arquivo CSV e a coluna que será usada para o sorteio
    $nomeArquivoCSV = 'sorteio.csv';
    $colunaSorteio = 'descricao'; 
    $nomeSorteio = 'nome'; 
    $nomeSorteado = "";

    // Verifica se o arquivo existe
    if (!file_exists($nomeArquivoCSV)) {
        die("O arquivo CSV '{$nomeArquivoCSV}' não foi encontrado.");
    }

    // Lê o conteúdo do arquivo CSV
    $linhas = file($nomeArquivoCSV, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

    // Verifica se o arquivo está vazio
    if (empty($linhas)) {
        die("O arquivo CSV '{$nomeArquivoCSV}' está vazio ou mal formatado.");
    }


    $colunas = str_getcsv(array_shift($linhas), ";");
    $colunaSorteioIndex = array_search($colunaSorteio, $colunas);
    $nomesSorteioIndex = array_search($nomeSorteio, $colunas);


    if ($colunaSorteioIndex === false) {
        die("A coluna '{$colunaSorteio}' não foi encontrada no arquivo CSV.");
    }

    // Obtém os elementos da coluna de sorteio
    $elementosSorteio = array_column(array_map($call, $linhas), $colunaSorteioIndex);
    // Realiza o sorteio
    $sorteado = sortearElemento($elementosSorteio);

    // Obtém os dados dos participantes do sorteio
    $mapNomes = array_map($call, $linhas);

    // filtra as colunas e associa o id ao index do array
    $nomesAndId = array_column($mapNomes, 0, 1);

    // percorre o array para identificar o nome do ganhador e salvar na variavel
    foreach ($nomesAndId as $key => $value) {
        $items = strval($key);
        if($items === $sorteado){
            $ganhador = $value;
        }
    }

    // Exibe o resultado
   echo "<h1>O ganhador(a) foi o participante : <span style='font-style: italic;font-weight: bold;'> {$ganhador} </span> - Número : <span style='font-style: italic;font-weight: bold;'> {$sorteado} </span></h1>";

} catch (\Throwable $th) {

    echo "Erro:  " . $th->getMessage();
}