<!doctype html>
<html lang="pt-br">
<head>
    <title>Prefeitura de Rio das Ostras</title>
    <!-- Estilos -->
    <link href="https://www.riodasostras.rj.gov.br/wp-content/themes/pmro/style.css" rel="stylesheet" type="text/css" media="all">
    <!-- Latest compiled and minified CSS -->

    <script type="text/javascript" src="https://www.riodasostras.rj.gov.br/wp-content/themes/pmro/js/jquery-2.0.3.min.js"></script>

    <script type="text/javascript" src="https://www.riodasostras.rj.gov.br/wp-content/themes/pmro/js/scripts.js"></script>


</head>
<body>
<script type="text/javascript" src="ViewModel/PesquisaUABViewModel.js">formPesquisaUAB</script>
<style type="text/css">
    .jCheckBox {
        position: relative;
        top: 15px;
        left: 15px;
        display: inline-block;
    }

    .jCheckBox [type="checkbox"] {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .jCheckBox [type="checkbox"] + label {
        position: relative;
        padding-left: 35px;
        cursor: pointer;
        display: inline-block;
        height: 25px;
        line-height: 25px;
        font-size: 1.2rem;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
    }

    .jCheckBox [type="checkbox"]:checked + label:before {
        top: -4px;
        left: -5px;
        width: 12px;
        height: 22px;
        border-top: 2px solid transparent;
        border-left: 2px solid transparent;
        border-right: 2px solid #0076af;
        border-bottom: 2px solid #0076af;
        -webkit-transform: rotate(40deg);
        transform: rotate(40deg);
        -webkit-backface-visibility: hidden;
        backface-visibility: hidden;
        -webkit-transform-origin: 100% 100%;
        transform-origin: 100% 100%;
    }

    .jCheckBox [type="checkbox"] + label:before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 18px;
        height: 18px;
        z-index: 0;
        border: 2px solid #b4b4b4;
        border-radius: 1px;
        margin-top: 2px;
        -webkit-transition: .2s;
        transition: .2s;
    }
</style>
<section class="bar bar-3 bar--sm bg--site">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="bar__module">
                    <ul class="migalhas">
                        <li><h3>Você está em</h3></li>
                        <li><a href="/">Página Inicial</a></li>
                        <li>Pesquisa Universidade Aberta do Brasil</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

<hr class="barra">

<section class="space--xs" id="content">
    <div class="container">
        <div class="row">

            <div class="col-md-12 text-center">
                <i class="icon icon--lg2 icon-lupa-10"></i>
                <img src="<?php print IMAGES; ?>/uab.png" alt="Logo da Universidade Aberta do Brasil">
                <h2 class="title-p1 mt-30">Pesquisa Universidade Aberta do Brasil</h2>
                <p class="lead text-left">
                    A Universidade Aberta do Brasil (UAB), em parceria com a Prefeitura Municipal de Rio das Ostras, poderá disponibilizar ao Polo de Rio das Ostras, localizado no Centro de Qualificação Profissional, na Zona Especial de Negócios (ZEN), cursos online e gratuitos de Pós-graduação, com duração de 18 meses, onde as avaliações serão presenciais e obrigatórias, realizadas no polo, havendo também encontros bimestrais presenciais, com oficinas, atividades e etc. O processo seletivo tem ocorrido no segundo semestre letivo de cada ano. Todos os cursos da Universidade Aberta do Brasil (UAB) são oferecidos no formato Ensino a Distância (EAD), com encontros presenciais em datas a serem estipuladas posteriormente pelas Universidades. Não há quantidade exata de aulas e encontros presenciais, geralmente são um ou dois encontros por disciplina, podendo haver aulas em alguns sábados pela manhã e em outros à tarde.
                    <br><br>
                    As avaliações finais são presenciais e obrigatórias, tendo outras formas de avaliação durante o curso, sendo calculada uma média de todas as avaliações.
                    <br><br>
                    Para tanto, torna-se necessário realizar uma pesquisa de demanda da população interessada nos cursos relacionados, a seguir, visto que as turmas só serão formadas com a garantia de um número mínimo de alunos.
                </p>
            </div>

            <div class="col-md-12 text-center mt-60 ttp1">
                <h2 class="title-p3">Conheça os Cursos</h2><br>
            </div>

            <div class="col-md-6">
                <div class="boxed boxed--border boxed--border">

                    <div class="col-sm-12">

                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12 block bg--secondary border--round border-bot-round2">
                                    <h4 class="text-center title-p7 ptb-10">ESPORTES E ATIVIDADES FÍSICAS INCLUSIVAS PARA PESSOAS COM DEFICIÊNCIA</h4>
                                </div>
                            </div>
                        </div>
                        <p class="mt-20">
                            O Curso de Especialização em "Esportes e Atividades Físicas Inclusivas para pessoas com deficiência” tem como objetivos: Estimular e intensificar a participação das pessoas com deficiência nas aulas de Educação Física e que esta participação seja uma possibilidade de reconhecimento e viabilização da cidadania.
                        </p>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="boxed boxed--border boxed--border">

                    <div class="col-sm-12">

                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12 block bg--secondary border--round border-bot-round2">
                                    <h4 class="text-center title-p7 ptb-10">MÍDIAS NA EDUCAÇÃO</h4>
                                </div>
                            </div>
                        </div>
                        <p class="mt-20">
                            Formar docentes e demais profissionais interessados nos estudos e atuação no campo das Mídias na Educação, para o uso e produção de mídias e tecnologias da informação e comunicação nos diferentes segmentos educacionais (formal - Básica e Superior, não formal), e também para o desenvolvimento de estudos, aplicações e potencialidades cognitivas e pedagógicas das mídias contemporâneas na educação e na sala de aula.
                        </p>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="boxed boxed--border boxed--border">

                    <div class="col-sm-12">

                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12 block bg--secondary border--round border-bot-round2">
                                    <h4 class="text-center title-p7 ptb-10">ESPECIALIZAÇÃO EM GESTÃO EM MEIO AMBIENTE</h4>
                                </div>
                            </div>
                        </div>
                        <p class="mt-20">
                            O curso auxilia na formação de profissionais com visão globalizada, atualizada e sólida base prática, capazes de realizar ações de forma competente e com atitudes críticas e criativas - fornecendo subsídios técnicos, científicos e metodológicos para melhores práticas. Destinado ao profissional graduado em área ambiental e demais áreas do conhecimento, que pretende atuar na área com excelência.
                        </p>

                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="boxed boxed--border boxed--border">

                    <div class="col-sm-12">

                        <div class="container">
                            <div class="row">
                                <div class="col-sm-12 block bg--secondary border--round border-bot-round2">
                                    <h4 class="text-center title-p7 ptb-10">TECNOLOGIAS DIGITAIS DE INFORMAÇÃO E COMUNICAÇÃO NO ENSINO BÁSICO</h4>
                                </div>
                            </div>
                        </div>
                        <p class="mt-20">
                            O Curso de Tecnologias de Informação e Comunicação no Ensino Básico está orientado ao desenvolvimento de competências especializadas para plena apropriação e domínio de métodos e técnicas no uso do computador e da internet em atividades do ensino fundamental. <br><br>
                        </p>

                    </div>
                </div>
            </div>

            <div class="col-md-12 text-center mt-60 ttp1">
                <h2 class="title-p1">Interessado em Participar?</h2><br>
            </div>

            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-12">
                        <div class="boxed boxed--border">
                            <div id="formPesquisaUAB" class="text-left form-email">
                                <div class="row">
                                    <div class="col-sm-12">
                                        <label>Nome completo:</label>
                                        <input id="nome" type="text" name="nome" class="validate-required"/>
                                    </div>
                                </div>
                                <div class="row mt-10">
                                    <div class="col-sm-6">
                                        <label>E-mail:</label>
                                        <input id="email" type="email" name="email" class="validate-required validate-email"/>
                                    </div>
                                    <div class="col-sm-6">
                                        <label>Telefone:</label>
                                        <input id="telefone" type="text" name="telefone" class="validate-required"/>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-4">
                                        <label>Logradouro:</label>
                                        <input id="logradouro" type="text" name="logradouro" class="validate-required"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Bairro:</label>
                                        <input id="bairro" type="text" name="bairro" class="validate-required"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Complemento:</label>
                                        <input id="complemento" type="text" name="complemento" class="validate-required"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Cidade:</label>
                                        <input id="cidade" type="text" name="cidade" class="validate-required"/>
                                    </div>
                                    <div class="col-sm-2">
                                        <label>Estado:</label>
                                        <select id="estado" name="estado">
                                            <option selected="" value="Default">Selecione</option>
                                            <option>Rio de Janeiro</option>
                                            <option>Acre</option>
                                            <option>Alagoas</option>
                                            <option>Amapá</option>
                                            <option>Amazonas</option>
                                            <option>Bahia</option>
                                            <option>Ceará</option>
                                            <option>Distrito Federal</option>
                                            <option>Espírito Santos</option>
                                            <option>Goiás</option>
                                            <option>Maranhão</option>
                                            <option>Mato Grosso</option>
                                            <option>Mato Grosso do Sul</option>
                                            <option>Minas Gerais</option>
                                            <option>Pará</option>
                                            <option>Paraíba</option>
                                            <option>Paraná</option>
                                            <option>Pernambuco</option>
                                            <option>Piauí</option>
                                            <option>Rio Grande do Norte</option>
                                            <option>Rio Grande do Sul</option>
                                            <option>Rondônia</option>
                                            <option>Roraima</option>
                                            <option>Santa Catarina</option>
                                            <option>São Paulo</option>
                                            <option>Sergipe</option>
                                            <option>Tocantins</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-sm-12">
                                        <h6>Escolha o Curso:</h6>
                                    </div>
                                </div>
                                <div id="cursosBlock">
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group jCheckBox">
                                                <input id="curso01" type="checkbox" name="curso01"/>
                                                <label for="curso01">Esportes e atividades físicas inclusivas para pessoas com deficiência</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group jCheckBox">
                                                <input id="curso02" type="checkbox" name="curso02"/>
                                                <label for="curso02">Mídias na Educação</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group jCheckBox">
                                                <input id="curso03" type="checkbox" name="curso03"/>
                                                <label for="curso03">Especialização em Gestão em Meio Ambiente</label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-sm-12">
                                            <div class="form-group jCheckBox">
                                                <input id="curso04" type="checkbox" name="curso04"/>
                                                <label for="curso04">Tecnologias Digitais de Informação e Comunicação no Ensino Básico </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-4 mt-15 text-left logorp">
                                            <ul class="btns">
                                                <li><a id="btnSave" class="btn btn-primary btn--sm"><span class=" type--uppercase">Enviar</span></a></li>
                                                <li><a id="btnReset" class="btn btn--sm"><span class="btn__text type--uppercase">Limpar</span></a></li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    </div>
</section>

<!-- Bloco de ferramentas -->
<section class="text-center space--xs">
    <div class="container">
        <div class="row">

            <div class="col-md-12">
                <div class="linha mt-50"></div>
            </div>

            <div class="col-md-4 mt-15 text-left logorp">
                <ul class="btns">
                    <li><a class="btn btn--sm" id="return" href="#"><span class="btn__text type--uppercase">Voltar</span></a></li>
                    <li><a class="btn btn--sm" id="print" href="#"><span class="btn__text type--uppercase">Imprimir</span></a></li>
                </ul>
            </div>

            <div class="col-md-8 mt-15 text-right logorp prt">
                <div class="share-bar" data-url="http://www.riodasostras.rj.gov.br/pesquisa-uab"></div>
            </div>

        </div>
    </div>
</section>

<div id="modalAlert" class="modal-container ">
    <div class="modal-content">
        <div class="boxed bg--white"><h2 class="title-p1"></h2>
            <div class="row">
                <div class="col-md-12">
                    <strong id="modalText"></strong>
                    <br>
                </div>
            </div>
        </div>
        <div class="modal-close modal-close-cross"></div>
    </div>
</div>

</body>
</html>