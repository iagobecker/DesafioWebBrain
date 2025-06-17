<?php
header('Content-Type: application/json');

$cidadesPorEstado = [
    'SP' => ['São Paulo', 'Campinas', 'Guarulhos', 'Santos'],
    'RJ' => ['Rio de Janeiro', 'Niterói', 'Duque de Caxias', 'São Gonçalo'],
    'MG' => ['Belo Horizonte', 'Uberlândia', 'Contagem', 'Juiz de Fora'],
    'BA' => ['Salvador', 'Feira de Santana', 'Vitória da Conquista'],
    'RS' => ['Porto Alegre', 'Caxias do Sul', 'Pelotas', 'Santa Maria'],
    'PR' => ['Curitiba', 'Londrina', 'Maringá', 'Ponta Grossa'],
    'SC' => ['Florianópolis', 'Joinville', 'Blumenau', 'Chapecó'],
    'GO' => ['Goiânia', 'Aparecida de Goiânia', 'Anápolis', 'Rio Verde'],
    'DF' => ['Brasília', 'Taguatinga', 'Ceilândia', 'Gama'],
    'PE' => ['Recife', 'Olinda', 'Jaboatão dos Guararapes', 'Caruaru'],
    'CE' => ['Fortaleza', 'Caucaia', 'Juazeiro do Norte', 'Maracanaú'],
    'AM' => ['Manaus', 'Parintins', 'Itacoatiara', 'Tabatinga'],
    'ES' => ['Vitória', 'Vila Velha', 'Serra', 'Cariacica'],
    'MT' => ['Cuiabá', 'Várzea Grande', 'Rondonópolis', 'Sinop'],
    'MS' => ['Campo Grande', 'Dourados', 'Três Lagoas', 'Corumbá'],
    'AL' => ['Maceió', 'Arapiraca', 'Palmeiras dos Índios', 'Rio Largo'],
    'PB' => ['João Pessoa', 'Campina Grande', 'Santa Rita', 'Patos'],
    'RN' => ['Natal', 'Mossoró', 'Parnamirim', 'Caicó'],
    'SE' => ['Aracaju', 'Nossa Senhora do Socorro', 'Lagarto', 'Itabaiana'],
    'PI' => ['Teresina', 'Parnaíba', 'Picos', 'Floriano'],
    'MA' => ['São Luís', 'Imperatriz', 'Caxias', 'Codó'],
    'RO' => ['Porto Velho', 'Ji-Paraná', 'Ariquemes', 'Vilhena'],
    'AC' => ['Rio Branco', 'Cruzeiro do Sul', 'Sena Madureira', 'Tarauacá'],
    'AP' => ['Macapá', 'Santana', 'Laranjal do Jari', 'Oiapoque'],
    'TO' => ['Palmas', 'Araguaína', 'Gurupi', 'Paraíso do Tocantins'],
    'RR' => ['Boa Vista', 'Rorainópolis', 'Caracaraí', 'Mucajaí'],
    'PA' => ['Belém', 'Ananindeua', 'Santarem', 'Marabá'],
];

$estado = isset($_GET['estado']) ? strtoupper(trim($_GET['estado'])) : '';

$response = [];

if (array_key_exists($estado, $cidadesPorEstado)) {
    $response = $cidadesPorEstado[$estado];
}

echo json_encode($response);
