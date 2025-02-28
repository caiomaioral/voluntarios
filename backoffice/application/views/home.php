<?php 

    echo $AddCss;
    echo $AddJavascripts;

?>

<div class="jumbotron">
    <div class="col-sm-12 mx-auto">
        <div class="row">
        
            <div class="col-md-4 mb-0">

            </div>

            <div class="col-md-8 mb-0">
                <div id="accordion">
                    <div class="card">
                        <div class="card-header" id="headingTwo">
                            <h5 class="mb-0">
                                <button class="btn btn-link collapsed text-left" data-toggle="collapse" data-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Pagamentos de <?php echo date('Y'); ?>
                                </button>
                            </h5>
                        </div>
                        <div id="collapseTwo" class="collapse show" aria-labelledby="headingTwo" data-parent="#accordion">
                            <div class="card-body">
                                
                            </div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-header" id="headingOne">
                            <h5 class="mb-0">
                                <button class="btn btn-link text-left" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                    Regulamento de <?php echo date('Y'); ?>
                                </button>
                            </h5>
                        </div>
                        <div id="collapseOne" class="collapse" aria-labelledby="headingOne" data-parent="#accordion">
                            <div class="card-body">

                                <h5 class="mb-3">Introdução</h5>
                                <p class="mb-3">O time Pyngaiada foi fundado em 15 de outubro de 2015 por sócios e participantes de outros rachas do Clube Esportivo da Penha, com o intuito de dar oportunidade a jogadores de futebol amador de jogar. Sendo que a maioria dos rachas são formados por panelas e boleiros dificultando o jogador a participar desses grupos.</p>

                                <h5 class="mb-3">Objetivo</h5>
                                <p class="mb-3">O objetivo desse regulamento e colocar regras e estabelecer diretrizes para os jogadores e participantes da comissão.</p>

                                <h5 class="mb-3">1º Regra ingressar novos jogadores</h5>
                                <p class="mb-3">O atleta que desejar participar do Racha do Pyngaiada deve ser associado do Clube Esportivo da Penha e estar com a mensalidade em dia, ter a idade mínima de 40 anos de idade, exceto filho de jogador que poderá ingressar com menos idade. O participante vai passar por uma avaliação de disciplina e comprometimento com o time durante 04(quatro) sábados. Após essa peneira o jogador se torna parte do grupo. Durante esse período o atleta poderá ficar no banco de reserva mesmo cumprindo todos os requisitos estabelecidos para ser escalado.</p>
                                <h5 class="mb-3">2º Regra escalação do time</h5>
                                <p class="mb-3">A prioridade dos times que irão iniciar o jogo, vai ser definida pela ordem dos seguintes fatores:</p>
                                <p class="mb-0">1º) Pagamento em dia:</p>
                                <p class="mb-0">2º) Regularidade na frequência;</p>
                                <p class="mb-3">3º) horário de chegada com nome na lista;</p>

                                <p class="mb-3">Os integrantes da Comissão do Racha tem preferência de sair jogando, desde que esteja com o pagamento da mensalidade em dia e presente até o fechamento da lista.</p>
                                <p class="mb-3">Para a formação dos Times. O Jogador com pendência no pagamento, não poderá participar do jogo exceto a Comissão permita que ele jogue.</p>
                                <p class="mb-3">A abertura da lista é as 6:20 horas e o fechamento da lista 6:50 horas. O início da partida será ás 7:00 horas. Caso o jogador chegue fora do horário deve procurar alguém da comissão para verificar a possibilidade de colocar o nome na lista para jogar o segundo tempo.</p>
                                <p class="mb-3">Substituição do jogador será por espontaneidade ou pelos mesmos fatores de escalação.</p>
                                <p class="mb-3">Caso um dos times perca um jogador por lesão o mesmo ser substituído preferencialmente por um atleta da mesma posição.</p>

                                <h5 class="mb-3">3º Regra local da partida</h5>
                                <p class="mb-3">O local da partida será no Estádio. Caso o Estádio esteja interditado, será no Campo do meio ou no Campo do fundo, a ser definido pela comissão junto com os funcionários e responsável do clube.</p>
                                <h5 class="mb-3">4º Regra dos Pagamentos</h5>
                                <p class="mb-3">Para a manutenção do racha o atleta/jogador deverá contribuir com o valor de R$ <?php echo num_to_user(VALOR_TRIMESTRAL); ?> a cada trimestre sendo o primeiro pagamento de o dia 10 de fevereiro o segundo até 10 de maio, a terceira até 10 de agosto e a quarta até dia 10 de novembro. Totalizando R$ <?php echo num_to_user(VALOR_TRIMESTRAL * TRIMESTRAL); ?>.</p>
                                <p class="mb-3">O pagamento deve ser feito até as datas mencionadas acima ao Sr. Sergio tesoureiro/financeiro do Racha.</p>
                                <p class="mb-3">O atleta que ficar um período fora por motivos de força maior, deverá pagar as mensalidades em atraso para voltar a participar do Racha.</p>
                                <p class="mb-3">Goleiro fixo, o pagamento é opcional, a ser definido pelo mesmo, optante em colaborar com o pagando da contribuição, entra nas mesmas regras dos jogadores de linha.</p>
                                <p class="mb-3">O jogador que ingressar no Racha deve pagar a contribuição referente ao trimestre de ingresso logo após o fim do jogo.</p>
                                <h5 class="mb-3">5º Regra das penalidades e disciplinas</h5>
                                <p class="mb-3">É inadmissível palavrões, brigas e desrespeitos com qualquer colaborador ou jogador do grupo, podendo ser suspenso por 1 jogo a 3 jogos, ou até mesmo expulso do grupo dependendo da gravidade da ação e avaliação da comissão.</p>
                                <p class="mb-3">Cartão Vermelho, o jogador será expulso da partida, após 5 minutos poderá ser substituído por outro atleta. O próximo jogo o Atleta não poderá participar como jogador. Caso não de quórum, o atleta poderá jogar pagamento multa de R$ 20,00 de forma disciplinar.</p>
                                <p class="mb-3">O Atleta/Jogador que abandonar o campo por qualquer que seja o motivo, o próximo jogo ficará no banco de reserva, independente dos requisitos para a escalação. Caso não der quórum o jogador poderá pagar a taxa disciplinar no valor de R$ 20,00 para participar da partida desde o início.</p>
                                <h5 class="mb-3">6º Regra das Festas</h5>
                                <p class="mb-3">As confraternizações do grupo vão ser feitas com o intuito de interagir a aproximar mais os participantes. As datas e os valores a ser pagos por convidados serão informadas no grupo e com antecedência, é muito importante a participação de todos.</p>

                                <h5 class="mb-3">Comissão do Pyngaiada <?php echo date('Y'); ?></h5>
                                <p class="mb-0">Sergio – Financeiro</p>
                                <p class="mb-0">Marcelo – Administrativo</p>
                                <p class="mb-0">Fernando – Administrativo</p>
                                <p class="mb-0">Beto – Eventos</p>
                                <p class="mb-0">Cleber – Disciplina</p>
                                <p class="mb-0">Edir – Disciplina</p>
                                <p class="mb-0">Sergio e Cleber – Escalação</p>
                                <p class="mb-0">William – Divulgação</p>

                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>             
    </div>
</div>