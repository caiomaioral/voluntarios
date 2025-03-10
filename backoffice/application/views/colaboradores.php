<?php 

    echo $AddCss;
    echo $AddJavascripts;

?>

<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.dataTables.css" />

<script src="https://cdn.datatables.net/2.2.2/js/dataTables.js"></script>

<main role="main" class="col-md-9 ml-sm-auto col-lg-10 px-4">
    
    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Dashboard</h1>
        <div class="btn-toolbar mb-2 mb-md-0">
            <div class="btn-group mr-2">
            <button class="btn btn-sm btn-outline-secondary">Compartilhar</button>
            <button class="btn btn-sm btn-outline-secondary">Exportar</button>
            </div>
            <button class="btn btn-sm btn-outline-secondary dropdown-toggle">
            <span data-feather="calendar"></span>
            Esta semana
            </button>
        </div>
    </div>

    <h3>Pesquisar Dados</h3>
    <div class="table-responsive">

        <table id="example" class="table table-striped" style="width:100%">
        <thead>
        <tr>
            <th>Ações</th>
            <th width="320">Nome</th>
            <th>CPF</th>
            <th>E-mail</th>
            <th>Telefone</th>
            <th>Status da Assinatura</th>
            <th>Data de Cadastro</th>
        </tr>
        </thead>
        <tbody>
            <tr>
                
            </tr>
        </tbody>
        </table>
        
    </div>
</main>