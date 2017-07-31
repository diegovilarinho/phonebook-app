<!DOCTYPE html>
<html>
<head>
    <title>Lista Telefônica | Diego Vilarinho | MadeiraMadeira</title>
    <link type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" rel="stylesheet">
    <link type="text/css" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <link href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/css/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="css/custom.css">
</head>
<body>

    <div class="container">
        <div class="row">
            <div class="col-lg-12 margin-tb">                   
                <div class="pull-left">
                    <h2>Lista Telefônica</h2>
                </div>
                <div class="pull-right">
                <button type="button" class="btn btn-success" data-toggle="modal" data-target="#create-item">
                      Adicionar novo contato
                </button>
                </div>
            </div>
        </div>

        <div class="panel panel-primary">
          <div class="panel-heading">Gerenciamento de Contatos</div>
          <div class="panel-body">
            <table class="table table-bordered table-responsive">
                <thead>
                    <tr>
                    <th>Nome</th>
                    <th>Telefone</th>
                    <th class="email-column">E-mail</th>
                    <th>Ação</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
            </table>

            <ul id="pagination" class="pagination-sm"></ul>
          </div>
        </div>

        <div class="panel panel-primary">
          <div class="panel-heading">Gráfico de Quantidade de Cadastros / Data</div>
          <div class="panel-body">
            <canvas id="chartCreatedAt"></canvas>
          </div>
        </div>

        <div class="row">
            <div class="col-lg-12" style="100px">                   
                
            </div>
        </div>

        <!-- Create Item Modal -->
        <div class="modal fade" id="create-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Adicionar Novo Contato</h4>
              </div>

              <div class="modal-body">
                    <form data-toggle="validator" action="v1/contacts/add" method="POST">

                        <div class="form-group">
                            <label class="control-label" for="title">Nome:</label>
                            <input type="text" name="name" class="form-control" data-error="Por favor, digite seu nome." required />
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="title">Telefone:</label>
                            <input type="text" name="phone" class="form-control" data-error="Por favor, digite seu telefone." required />
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="title">E-mail:</label>
                            <input type="email" name="email" class="form-control" data-error="Por favor, digite seu e-mail." required />
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn crud-submit btn-success">Adicionar Contato</button>
                        </div>

                    </form>

              </div>
            </div>

          </div>
        </div>

        <!-- Edit Item Modal -->
        <div class="modal fade" id="edit-item" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
          <div class="modal-dialog" role="document">
            <div class="modal-content">
              <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                <h4 class="modal-title" id="myModalLabel">Editar Contato</h4>
              </div>

              <div class="modal-body">
                    <form data-toggle="validator" action="v1/contacts/edit" method="put">
                        <input type="hidden" name="id" class="edit-id">

                        <div class="form-group">
                            <label class="control-label" for="title">Nome:</label>
                            <input type="text" name="name" class="form-control" data-error="Por favor, digite seu nome." required />
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="title">Telefone:</label>
                            <input type="text" name="phone" class="form-control" data-error="Por favor, digite seu telefone." required />
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group">
                            <label class="control-label" for="title">E-mail:</label>
                            <input type="email" name="email" class="form-control" data-error="Por favor, digite seu e-mail." required />
                            <div class="help-block with-errors"></div>
                        </div>

                        <div class="form-group">
                            <button type="submit" class="btn btn-success crud-submit-edit">Editar Contato</button>
                        </div>
                    </form>
              </div>
            </div>
          </div>
        </div>

    </div>
    
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.1.0/jquery.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.0.0-alpha/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/twbs-pagination/1.3.1/jquery.twbsPagination.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/1000hz-bootstrap-validator/0.11.5/validator.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
    <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.6.0/Chart.bundle.min.js"></script>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.11/jquery.mask.min.js"></script>
    <script type="text/javascript">
        var url = "http://api.phonebook.mmadeira.dev/";
    </script>
    <script src="js/manage-data.js"></script>
</body>
</html>