$(function () {
    var cadastroServidorViewModel = new CadastroServidorViewModel();
    cadastroServidorViewModel.init();
});

function CadastroServidorViewModel() {

    this.webserviceJubarteBaseURL = WEBSERVICE_JUBARTE_BASE_URL;
    this.webservicePmroBaseURL = WEBSERVICE_PMRO_BASE_URL;
    this.restClient = new RESTClient();
    this.loaderApi = new LoaderAPI();
    this.modalApi = window.location !== window.parent.location ? window.parent.getModal() : new ModalAPI();

    //form pessoa fisica
    this.formPeFisicaValidate = new FormValidationAPI('formPessoa');
    this.idPessoa = null;
    this.inputNomePeFisica = $('#inputNomePeFisica');
    this.inputEmailPrinPeFisica = $('#inputEmailPrinPeFisica');
    this.inputEmailAdPeFisica = $('#inputEmailAdPeFisica');
    this.inputCPF = $('#inputCPF');
    this.selectSexo = $('#selectSexo');
    this.inputDataNascimento = $('#inputDataNascimento');
    this.inputRG = $('#inputRG');
    this.inputOrgaoEmissor = $('#inputOrgaoEmissor');
    this.inputDataEmissao = $('#inputDataEmissao');
    this.selectEstadoOrgaoEmissor = $('#selectEstadoOrgaoEmissor');
    this.selectNacionalidade = $('#selectNacionalidade');
    this.selectNaturalidadeUF = $('#selectNaturalidadeUF');
    this.selectNaturalidadeMunicipio = $('#selectNaturalidadeMunicipio');
    this.inputProfissao = $('#inputProfissao');
    this.selectGrupoSanguineo = $('#selectGrupoSanguineo');
    this.selectFatorRH = $('#selectFatorRH');
    this.inputPIS = $('#inputPIS');
    this.selectEstadoCivil = $('#selectEstadoCivil');
    this.inputNomePai = $('#inputNomePai');
    this.inputNomeMae = $('#inputNomeMae');
    this.dataTableServidores = new ModernDataTable('tableListServidores');

    //form telefone
    this.formTelefoneValidate = new FormValidationAPI('telefoneContainer');
    this.telefoneContainer = $('#telefoneContainer');
    this.btnAddTelefone = $('#btnAddTelefone');
    this.btnRemoveTelefone = $('#btnRemoveTelefone');
    this.limitTelefones = 3;

    //form endereco
    this.formEnderecoValidate = new FormValidationAPI('enderecoContainer');
    this.templateFormEndereco = $('#templateFormEndereco');
    this.enderecoContainer = $('#enderecoContainer');
    this.limitEndereco = 3;

    this.btnSalvar = $('.btnSalvar');

    //form busca CEP
    this.selectUfBuscaCEP = $('#selectUfBuscaCEP');
    this.selectMunicipioBuscaCEP = $('#selectMunicipioBuscaCEP');
    this.inputBairroBuscaCEP = $('#inputBairroBuscaCEP');
    this.inputLogradouroBuscaCEP = $('#inputLogradouroBuscaCEP');
    this.btnBuscarCEPEnd = $('#btnBuscarCEPEnd');
    this.correntEndereco = null;

    // lista CEPS
    this.tableListCEPCorreios = $('#tableListCEPCorreios');
    this.dataTableCEPCorreios = new ModernDataTable('tableListCEPCorreios');
    this.modalBuscaCEP = $('#modalBuscaCEP');

    //form modal escala de trabalho
    this.modalEscala = $('#modalEscala');
    this.selectLocalBiometria = $('#selectLocalBiometria');
    this.selectHoraEntrada = $('#selectHoraEntrada');
    this.selectHoraSaida = $('#selectHoraSaida');
    this.btnAddWorkload = $('#btnAddWorkload');
    this.containerDiasSemana = $('#containerDiasSemana');
    this.jTimeline = new jTimeline($('#timeline'));

    //form matricula
    this.templateFormMatricula = $('#templateFormMatricula');
    this.matriculaContainer = $('#matriculaContainer');
    this.formMatriculaValidate = new FormValidationAPI('matriculaContainer');

    //modal
    this.modalListaServidor = $('#modalListaServidor');
    this.modalOrganograma = $('#modalOrganograma');
    this.treeViewOrganograma = new ModernTreeView('treeViewOrganograma');
    this.correntInputOrganograma = null;

    //photo
    this.btnAddPhoto = $('#btnAddPhoto');
    this.btnOnWebCam = $('#btnOnWebCam');
    this.btnTakePhoto = $('#btnTakePhoto');
    this.canvasPhotoView = $('#canvasPhotoView');
    this.inputFileSelectPhoto = $('#inputFileSelectPhoto');
    this.videoWebCamView = $('#videoWebCamView');
    this.currentCapturedImage = null;
    this.isPhoto = false;
    this.inputFotoPerfil = $('#inputFotoPerfil');
    this.modalGetFoto = $('#modalGetFoto');
    this.imgPhotoEditor = $('#imgPhotoEditor');
}

CadastroServidorViewModel.prototype.init = function () {
    var self = this;


    self.iniciarTimeline();

    //inicialiaza plugins
    $('.select').selectpicker();
    self.eventos();
    self.maskForm();
    self.getPaises();
    self.getUFs();
    self.getMunicipios();
    self.listCEPbyEndereco();
    self.getAllServidores();
    self.getCargos();
    self.getFuncoes();
    self.getVinculos();
    self.getJornadas();
    self.getLocais();
    self.fillSelectHora();
    self.getOrganogramas();

};
CadastroServidorViewModel.prototype.eventos = function () {
    var self = this;

    //adiciona item de carga horaria
    self.btnAddWorkload.click(function () {

        self.containerDiasSemana.find('input[type=checkbox]:checked').each(function (idx, item) {
            var checkbox = $(item);
            var idLocal = self.selectLocalBiometria.val();
            var local = self.selectLocalBiometria.find('option:selected').text();
            var entrada = self.selectHoraEntrada.val();
            var saida = self.selectHoraSaida.val();
            var day = parseInt(checkbox.val());
            self.jTimeline.addValue(idLocal, local, day, entrada, saida);
        });
        self.modalEscala.modal('hide');
    });

    //salva o cadastro
    self.btnSalvar.click(function () {
        self.saveServidor();
    });

    //adiciona/remove form de telefone
    self.btnAddTelefone.on('click', function () {
        self.addFormTelefone();
    });
    self.btnRemoveTelefone.on('click', function () {
        self.removeFormTelefone();
    });
    //adiciona/remove form de endereco
    self.enderecoContainer.on('click', '.btnAddEndereco', function () {
        self.addFormEndereco();
    });

    self.enderecoContainer.on('click', '.btnRemoveEndereco', function () {
        self.removeFormEndereco($(this).closest('.enderecoItem'));
    });

    self.matriculaContainer.on('click', '.btnRemoveMatricula', function () {
        self.removeFormMatricula($(this).closest('.matriculaItem'));
    });

    //buscar endeço nos correios pelo cep informado e preencher os campos de endereço
    $(document).on('click', '.btnPreencherEndereco', function () {
        var divEndereco = $(this).closest(('div[class^="enderecoItem"]'));
        var correntCep = divEndereco.find('[name="cep"]').cleanVal();
        if (!correntCep) {
            //alert('CEP não pode ser vazio');
            self.modalApi.showModal(ModalAPI.ERROR, "Erro", 'CEP não pode ser vazio', "OK");
        }
        else {
            self.getEnderecoByCEP(correntCep, divEndereco);
        }

    });
    //
    $(document).on('click', '.btnShowModalBuscaCEP', function () {
        self.correntEndereco = $(this).closest(('div[class^="enderecoItem"]'));
    });

    //evento on change do select uf para obter municipios
    self.enderecoContainer.on('change', '[name="uf"]', function (e) {
        var divEndereco = $(this).closest(('div[class^="enderecoItem"]'));
        var selectMunicipio = divEndereco.find('[name="municipio"]');
        var selectUF = $(this);
        self.getMunicipios(selectMunicipio, selectUF.val());
    });

    //quando selecionar um estado de UfBuscaCEP obtem os municipios deste estado
    self.selectUfBuscaCEP.change(function (e) {
        self.getMunicipios(self.selectMunicipioBuscaCEP, self.selectUfBuscaCEP.val());
    });

    //quando selecionar um pais diferente de Brasil desativa os selects uf e municipio
    self.enderecoContainer.on('change', '[name="pais"]', function (e) {
        var divEndereco = $(this).closest(('div[class^="enderecoItem"]'));

        if ($(this).find("option:selected").text() !== 'Brasil') {
            divEndereco.find('[name="municipio"]').prop('disabled', true);
            divEndereco.find('[name="uf"]').prop('disabled', true);
        }
        else {
            divEndereco.find('[name="municipio"]').prop('disabled', false);
            divEndereco.find('[name="uf"]').prop('disabled', false);
        }
        self.updateFields();
    });

    self.matriculaContainer.on('click', '.btnAddMatricula', function () {
        self.addFormMatricula();
    });

    //BUSCA CEP POR ENDERECO
    self.btnBuscarCEPEnd.click(function () {
        var dataToSender = {
            'uf': converterEstados(self.selectUfBuscaCEP.find('option:selected').text()),
            'municipio': self.selectMunicipioBuscaCEP.find('option:selected').text(),
            'bairro': self.inputBairroBuscaCEP.val(),
            'logradouro': self.inputLogradouroBuscaCEP.val()
        };
        console.log(dataToSender);
        self.dataTableCEPCorreios.setDataToSender(dataToSender);
        self.dataTableCEPCorreios.reload();
    });

    //quando selecionar um estado de selectNaturalidadeUF obtem os municipios deste estado
    self.selectNaturalidadeUF.change(function (e) {
        self.getMunicipios(self.selectNaturalidadeMunicipio, self.selectNaturalidadeUF.val());
    });

    self.inputCPF.keypress(function (e) {
        //ao precionar enter
        if (e.which === 13) {
            var cpf = self.inputCPF.val();
            if (validaCPF(cpf)) {
                self.getServidor(cpf.replace(/[^\d]+/g, ''));
            }
        }
    });

    //
    self.matriculaContainer.on('click', '[name="inputLotacao"] , [name="inputLocalTrabalho"]', function () {
        self.correntInputOrganograma = $(this);
        self.modalOrganograma.modal('show');
    });

    //inicializa a webcam para tirar foto
    var isOpenWebCam = false;
    self.btnOnWebCam.click(function () {
        if (isOpenWebCam === false) {
            Webcam.set({
                width: 320,
                height: 240,
                image_format: 'png',
                jpeg_quality: 100
            });
            Webcam.attach('#videoWebCamView');
            isOpenWebCam = true;
        } else {
            Webcam.reset();
            isOpenWebCam = false;
        }
    });

    //tira uma photo da imagem da webcam
    self.btnTakePhoto.click(function () {
        Webcam.snap(function (data_uri) {
            self.canvasPhotoView.find('#imgPhotoEditor').cropper('replace', data_uri);
        });
    });

    //seleciona um arquivo para fazer upload
    self.inputFileSelectPhoto.on('change', function () {
        var selectedFile = this.files[0];
        var reader = new FileReader();
        reader.onloadend = function (e) {
            var data_uri = e.target.result;
            self.canvasPhotoView.find('#imgPhotoEditor').cropper('replace', data_uri);
        };
        reader.readAsDataURL(selectedFile);
    });

    /*var event = jQuery.Event( "mousewheel" );
    event.deltaY = -50;
    self.canvasPhotoView.find('.cropper-container').trigger(event);
    self.canvasPhotoView.on('mousewheel','.cropper-container',function () {
      console.log('asd');
    });*/

    //btn modal get cropped image
    self.btnAddPhoto.click(function () {
        // Get the Cropper.js instance after initialized
        var cropper = self.canvasPhotoView.find('#imgPhotoEditor').data('cropper');
        var imageData = cropper.getCroppedCanvas({
            width: 256,
            height: 256,
            minWidth: 256,
            minHeight: 256,
            maxWidth: 4096,
            maxHeight: 4096,
            fillColor: '#fff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high'
        }).toDataURL();
        self.inputFotoPerfil.find('img').attr('src', imageData);

        Webcam.reset();
        isOpenWebCam = false;
        self.modalGetFoto.modal('hide');
        self.inputFotoPerfil.find('div').focus();
    });
    //remove a photo ao pressionar delete
    self.inputFotoPerfil.on('keyup','div', function (e) {
        var key = event.keyCode || event.charCode;
        if (key === 8 || key === 46 || key === 110) {
            self.inputFotoPerfil.find('img').attr('src', '/cdn/Assets/icons/userNoImage.jpg');
        }
    });
    //opem modal get photo
    var isOpenPhotoEditor = false;
    self.inputFotoPerfil.on('click','div', function (e) {
        self.modalGetFoto.one('shown.bs.modal', function (e) {
           if(isOpenPhotoEditor === false) {
               // cropper foto perfil
               $('#imgPhotoEditor').cropper({
                   aspectRatio: 1 / 1,
                   responsive: true,
                   preview: '#photoPreview'
               });
               isOpenPhotoEditor = true;
           }else {
               isOpenPhotoEditor = false;
           }

        }).one('hide.bs.modal',function () {
            Webcam.reset();
            isOpenWebCam = false;
            self.inputFotoPerfil.find('div').focus();
        }).modal('show');
    });

};

CadastroServidorViewModel.prototype.iniciarTimeline = function () {
    var self = this;
    self.jTimeline.setTitle('Carga Horária');
    self.jTimeline.load();
};

CadastroServidorViewModel.prototype.updateFields = function () {
    var self = this;
    var selects = $('.select');
    //selects.selectpicker('destroy');
    //selects.selectpicker('refresh');
    //selects.selectpicker();
    var template = document.getElementById("templateFormEndereco");
    var isInternetExplorer = template.content === undefined;
    if (!isInternetExplorer) {
        selects.selectpicker('refresh');
    } else {
        selects.selectpicker('destroy');
        //selects.selectpicker();
    }
    self.maskForm();
};
CadastroServidorViewModel.prototype.resetForm = function () {
    var self = this;

    //form pessoa fisica
    self.idPessoa = null;
    self.inputNomePeFisica.val();
    self.inputEmailPrinPeFisica.val();
    self.inputEmailAdPeFisica.val();
    self.inputCPF.val('');
    self.selectSexo.val('');
    self.inputDataNascimento.val('');
    self.inputRG.val('');
    self.inputOrgaoEmissor.val('');
    self.inputDataEmissao.val('');
    self.selectEstadoOrgaoEmissor.val('');
    self.selectNacionalidade.val('');

    self.selectNaturalidadeUF.val('');
    self.selectNaturalidadeMunicipio.val('');
    self.inputProfissao.val('');
    self.selectGrupoSanguineo.val('');
    self.selectFatorRH.val('');

    self.inputPIS.val('');
    self.selectEstadoCivil.val('');
    self.inputNomePai.val('');
    self.inputNomeMae.val('');


    self.updateFields();

};
CadastroServidorViewModel.prototype.maskForm = function () {
    var self = this;

    self.inputCPF.mask('000.000.000-00', {reverse: true});
    self.inputDataEmissao.mask('00/00/0000');
    self.inputDataNascimento.mask('00/00/0000');

    self.enderecoContainer.find('[name="cep"]').each(function () {
        $(this).mask('99999-999');
    });

    $(document).on('focus', '[name="numeroTelefone"]', function () {
        var numeroTelefone = $(this);
        var tipoTelefone = numeroTelefone.closest('div[class^="telefoneItem"]').find('[name="tipoTelefone"]');

        if (!tipoTelefone.val()) {
            $(this).keydown(function () {
                return false;
            });
            //self.modalApi.warning('Selecione o tipo de telefone primeiro!');
            tipoTelefone.focus();
        }
        else if (tipoTelefone.val() === 'Móvel') {
            numeroTelefone.unbind('keydown');
            numeroTelefone.mask("(00)00000-0000");
        }
        else if (tipoTelefone.val() === 'WhatsApp') {
            numeroTelefone.unbind('keydown');
            numeroTelefone.mask("(00)00000-0000");
        }
        else {
            numeroTelefone.unbind('keydown');
            numeroTelefone.mask("(00)0000-0000");
        }

    });

};
CadastroServidorViewModel.prototype.validaForm = function () {
    var self = this;

    //valida pessoa fisica
    if (self.formPeFisicaValidate.validate(true, true) === false) {

        self.modalApi.modalError('O(s) campo(s) ' + self.formPeFisicaValidate.getInvalidFields() + ' são obrigatorios!');
        return false;
    }

    //valida telefones
    if (!self.formTelefoneValidate.validate(true, true)) {
        self.modalApi.modalError('O(s) campo(s) ' + self.formTelefoneValidate.getInvalidFields() + ' são obrigatorios!');
        return false;
    }

    //valida endereços
    if (!self.formEnderecoValidate.validate(true, true)) {
        self.modalApi.modalError('O(s) campo(s) ' + self.formEnderecoValidate.getInvalidFields() + ' são obrigatorios!');
        return false;
    }

    //valida matriculas
    if (!self.formMatriculaValidate.validate(true, true)) {
        self.modalApi.modalError('O(s) campo(s) ' + self.formMatriculaValidate.getInvalidFields() + ' são obrigatorios!');
        return false;
    }
    return true;

};
CadastroServidorViewModel.prototype.addFormTelefone = function (numeroTelefone, tipoTelefone) {
    var self = this;

    var correntCountTel = self.telefoneContainer.find('.telefoneItem').length;

    if (correntCountTel < self.limitTelefones) {
        var html = $('<div class="telefoneItem"><!-- telefoneItem -->\n' +
            '        <div class="col-md-2 inputBlock">\n' +
            '            <label>Tipo</label>\n' +
            '            <select name="tipoTelefone" class="select" required>\n' +
            '                <option selected="" disabled="">Selecione</option>\n' +
            '                <option value="Residencial">Residencial</option>\n' +
            '                <option value="Comercial">Comercial</option>\n' +
            '                <option value="Móvel">Móvel</option>\n' +
            '                <option value="WhatsApp">WhatsApp</option>\n' +
            '                <option value="Outro">Outro</option>\n' +
            '            </select>\n' +
            '        </div>\n' +
            '        <div class="col-md-2 inputBlock">\n' +
            '            <label>Telefone</label>\n' +
            '            <input name="numeroTelefone" type="text"\n' +
            '                    class="form-control" required>\n' +
            '        </div>\n' +
            '    </div>\n' +
            '</div><!-- /telefoneItem -->');

        if (numeroTelefone && tipoTelefone) {
            html.find('[name="numeroTelefone"]').val(numeroTelefone);
            html.find('[name="tipoTelefone"]').val(tipoTelefone);
        }

        self.telefoneContainer.append(html);
        self.updateFields();
    }
};
CadastroServidorViewModel.prototype.removeFormTelefone = function () {
    var self = this;
    var telefoneItem = null;
    self.telefoneContainer.find('.telefoneItem').each(function () {
        telefoneItem = this;
    });

    var correntCountTel = self.telefoneContainer.find('.telefoneItem').length;

    if (correntCountTel > 1) {
        $(telefoneItem).remove();
    }

};
CadastroServidorViewModel.prototype.addFormEndereco = function (enderecoData) {
    var self = this;

    var correntCountEnde = self.telefoneContainer.find('.enderecoItem').length;

    //get the template element:
    var template = document.getElementById("templateFormEndereco");

    //get the DIV element from the template:
    var html = template.content === undefined ? template.querySelector("div") : template.content.querySelector("div");
    html = $(html).clone();

    var idEnderecoDivergente = 'enderecoDivergente' + genId();
    html.find('input[name="enderecoDivergente"]').prop('id', idEnderecoDivergente);
    html.find('label[for="enderecoDivergente"]').prop('for', idEnderecoDivergente);

    if (correntCountEnde < self.limitEndereco) {
        self.enderecoContainer.append(html);

        if (enderecoData) {
            html.find('[name="cep"]').val(enderecoData['cep']);
            html.find('[name="tipoEndereco"]').val(enderecoData['tipo']);
            html.find('[name="bairro"]').val(enderecoData['bairro']);
            html.find('[name="tipoLogradouro"]').val(enderecoData['tipoLogradouro']);
            html.find('[name="logradouro"]').val(enderecoData['logradouro']);
            html.find('[name="numeroLogradouro"]').val(enderecoData['numero']);
            html.find('[name="complemento"]').val(enderecoData['complemento']);
            html.find('[name="divergente"]').prop('checked', enderecoData['divergente']);
            html.find('[name="validacao"]').val(enderecoData['validacao']);

            self.getPaises(html.find('[name="pais"]'), enderecoData['idPais']);
            self.getUFs(html.find('[name="uf"]'), enderecoData['idUf']);
            self.getMunicipios(html.find('[name="municipio"]'), enderecoData['idUf'], enderecoData['idMunicipio']);

        } else {
            self.getPaises(html.find('[name="pais"]'));
            self.getUFs(html.find('[name="uf"]'));
            self.getMunicipios(html.find('[name="municipio"]'));
        }
        self.updateFields();
    }
};
CadastroServidorViewModel.prototype.removeFormEndereco = function (enderecoItem) {
    var self = this;

    var correntCountEnd = self.enderecoContainer.find('.enderecoItem').length;

    if (correntCountEnd > 1) {
        $(enderecoItem).remove();
    }
};
CadastroServidorViewModel.prototype.addFormMatricula = function (data) {
    var self = this;

    var correntCount = self.matriculaContainer.find('.matriculaItem').length;

    //get the template element:
    var template = document.getElementById("templateFormMatricula");

    //get the DIV element from the template:
    var html = template.content === undefined ? template.querySelector("div") : template.content.querySelector("div");
    html = $(html).clone();

    if (correntCount < 2) {

        if (data) {

            var matricula = data['matricula'];

            html.find('[name="inputMatricula"]').val(matricula['matricula']);
            html.find('[name="inputDataAdmissao"]').val(sqlDateToBrasilDate(matricula['dataAdmissao']));
            html.find('[name="inputDataExoneracao"]').val(matricula['dataExoneracao']);
            html.find('[name="selectVinculo"]').val(matricula['idVinculo']);
            html.find('[name="selectCargo"]').val(matricula['idCargo']);
            html.find('[name="selectFG"]').val(matricula['idFuncaoGratificada']);
            html.find('[name="selectJornadaTrabalho"]').val(matricula['idJornadaTrabalho']);

            var inputLotacao = html.find('[name="inputLotacao"]');
            var inputLocalTrabalho = html.find('[name="inputLocalTrabalho"]');

            inputLotacao.attr('data-val', matricula['idLotacao']);
            inputLotacao.val(data['siglaLotacao']);

            inputLocalTrabalho.attr('data-val', matricula['idLocalTrabalho']);
            inputLocalTrabalho.val(data['siglaLocalTrabalho']);

        }
        self.matriculaContainer.append(html);
        self.updateFields();
    }
};
CadastroServidorViewModel.prototype.removeFormMatricula = function (matriculaItem) {
    var self = this;

    var correntCount = self.matriculaContainer.find('.matriculaItem').length;

    if (correntCount > 1) {
        $(matriculaItem).remove();
    }
};
//CARREGA DADOS DE ENDERECO
CadastroServidorViewModel.prototype.getPaises = function (select, idPaisToSelect) {
    var self = this;
    self.loaderApi.show();
    self.restClient.setWebServiceURL(self.webservicePmroBaseURL + 'pais');
    self.restClient.setMethodGET();
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.loaderApi.hide();
        if (!select) {
            populateSelect($('[name="pais"]'), data, 'id', 'nome', 'brasil');
            populateSelect(self.selectNacionalidade, data, 'id', 'nome', 'brasil');
        }
        else {
            populateSelect(select, data, 'id', 'nome', 'brasil');
            if (idPaisToSelect) {
                select.val(idPaisToSelect);
            }
        }
        self.updateFields();
    });
    self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
        //alert('Erro em obter Paises');
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
        self.loaderApi.hide();
    });
    self.restClient.exec();
};
CadastroServidorViewModel.prototype.getUFs = function (select, idUfToSelect) {
    var self = this;
    self.loaderApi.show();
    var dataToSender = {'idPais': 33};
    self.restClient.setWebServiceURL(self.webservicePmroBaseURL + 'uf');
    self.restClient.setMethodPOST();
    self.restClient.setDataToSender(dataToSender);
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.loaderApi.hide();
        if (!select) {
            populateSelect($('[name="uf"]'), data, 'id', 'nome', 'rio de janeiro');
            populateSelect(self.selectEstadoOrgaoEmissor, data, 'id', 'nome', 'rio de janeiro');
            populateSelect(self.selectUfBuscaCEP, data, 'id', 'nome', 'rio de janeiro');
            populateSelect(self.selectNaturalidadeUF, data, 'id', 'nome', 'rio de janeiro');
        }
        else {
            populateSelect(select, data, 'id', 'nome', 'rio de janeiro');
            if (idUfToSelect) {
                select.val(idUfToSelect);
            }
        }
        self.updateFields();

    });
    self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
        self.loaderApi.hide();
        //alert('Erro em obter lista de estados');
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
    });
    self.restClient.exec();
};
CadastroServidorViewModel.prototype.getMunicipios = function (select, idUF, idMunicipioToSelect) {
    var self = this;
    self.loaderApi.show();
    if (idUF == null) {
        idUF = 20;
    }
    var dataToSender = {'idUF': idUF};
    self.restClient.setWebServiceURL(self.webservicePmroBaseURL + 'municipio');
    self.restClient.setMethodPOST();
    self.restClient.setDataToSender(dataToSender);
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.loaderApi.hide();
        if (!select) {
            populateSelect($('[name="municipio"]'), data, 'id', 'nome', 'rio das ostras');
            populateSelect(self.selectMunicipioBuscaCEP, data, 'id', 'nome', 'rio das ostras');
            populateSelect(self.selectNaturalidadeMunicipio, data, 'id', 'nome', 'rio das ostras');
        }
        else {
            populateSelect(select, data, 'id', 'nome', 'rio das ostras');
            if (idMunicipioToSelect) {
                select.val(idMunicipioToSelect);
            }
        }
        self.updateFields();
    });
    self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
        self.loaderApi.hide();
        // alert('Erro em obter lista de municipios');
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
    });
    self.restClient.exec();
};
CadastroServidorViewModel.prototype.getEnderecoByCEP = function (cep, refCorrentDivEndereco) {
    var self = this;
    self.loaderApi.show();

    /*refCorrentDivEndereco.find('[name="bairro"]').prop('disabled', true);
    refCorrentDivEndereco.find('[name="uf"]').prop('disabled', true);
    refCorrentDivEndereco.find('[name="municipio"]').prop('disabled', true);
    refCorrentDivEndereco.find('[name="logradouro"]').prop('disabled', true);
    refCorrentDivEndereco.find('[name="tipoLogradouro"]').prop('disabled', true);*/

    self.restClient.setWebServiceURL(self.webserviceJubarteBaseURL + 'correios/endereco/' + cep);
    self.restClient.setMethodGET();
    self.restClient.setDataToSender(null);
    self.restClient.setSuccessCallbackFunction(function (data) {
        //set o input validacao para true
        var estadoCorreios = data['uf'];
        var municipioCorreios = data['municipio'];
        var tipoLogradouroCorreios = data['tipo'];

        refCorrentDivEndereco.find('[name="validacao"]').val('true');
        refCorrentDivEndereco.find('[name="cep"]').val(data['cep']);

        //seleciona o PAIS
        var selectPais = refCorrentDivEndereco.find('[name="pais"]');
        setSelectIsContain(selectPais, 'brasil');
        self.updateFields();

        //seleciona o ESTADO
        var selectUf = refCorrentDivEndereco.find('[name="uf"]');
        setSelectIsContain(selectUf, estadoCorreios);
        self.updateFields();

        //dispara o evento change preenchendo o select municipio com as municipios
        //do estado do CEP e seta o municipio do CEP
        var timerFunc = setInterval(function () {
            if (self.loaderApi.isLoading() === false) {
                clearInterval(timerFunc);
                var selectMunicipio = refCorrentDivEndereco.find('[name="municipio"]');
                setSelectIsContain(selectMunicipio, municipioCorreios);
                self.updateFields();
            }
        }, 500);

        refCorrentDivEndereco.find('[name="bairro"]').val(data['bairro']);
        refCorrentDivEndereco.find('[name="logradouro"]').val(data['logradouro']);

        var selectTipoLogradouro = refCorrentDivEndereco.find('[name="tipoLogradouro"]');
        if (!setSelectIsContain(selectTipoLogradouro, tipoLogradouroCorreios)) {
            selectTipoLogradouro.append($('<option>', {
                value: tipoLogradouroCorreios, text: tipoLogradouroCorreios
            }).attr("selected", true));
        }
        self.updateFields();
        self.loaderApi.hide();
    });
    self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
        self.loaderApi.hide();
        refCorrentDivEndereco.find('[name="bairro"]').prop('disabled', false);
        refCorrentDivEndereco.find('[name="uf"]').prop('disabled', false);
        refCorrentDivEndereco.find('[name="municipio"]').prop('disabled', false);
        refCorrentDivEndereco.find('[name="logradouro"]').prop('disabled', false);
        refCorrentDivEndereco.find('[name="tipoLogradouro"]').prop('disabled', false);
        //alert('Não foi possível obter endereço pelo CEP informado.');
        self.modalApi.showModal(ModalAPI.ERROR, "Erro", jqXHR.responseJSON, "OK");
    });
    self.restClient.exec();
};
//INICIALIZA DATATABLE BUSCA CEP POR ENDERECO
CadastroServidorViewModel.prototype.listCEPbyEndereco = function () {
    var self = this;

    var columnsConfiguration = [
        {"key": "tipo"},
        {"key": "logradouro"},
        {"key": "complemento"},
        {"key": "bairro"},
        {"key": "municipio"},
        {"key": "uf"},
        {"key": "cep"}
    ];

    var dataToSender = {
        'uf': 'RJ',
        'municipio': 'Rio das Ostras',
        'bairro': 'Centro',
        'logradouro': 'Rodovia Amaral'
    };

    self.dataTableCEPCorreios.setDisplayCols(columnsConfiguration);
    self.dataTableCEPCorreios.hideActionBtnDelete();
    self.dataTableCEPCorreios.hideRowSelectionCheckBox();
    self.dataTableCEPCorreios.setIsColsEditable(false);

    self.dataTableCEPCorreios.setDataToSender(dataToSender);
    self.dataTableCEPCorreios.setSourceURL(self.webserviceJubarteBaseURL + "correios/cep");
    self.dataTableCEPCorreios.setSourceMethodPOST();
    self.dataTableCEPCorreios.setOnClick(function (data) {
        var cep = data['cep'].replace('-', '').trim();
        self.getEnderecoByCEP(cep, self.correntEndereco);
        self.modalBuscaCEP.modal('hide');
    });
    self.dataTableCEPCorreios.load();
};
//CRIA OU ATUALIZA SERVIDOR
CadastroServidorViewModel.prototype.saveServidor = function () {
    var self = this;

    //valida
    if (!self.validaForm()) {
        return;
    }

    // OBTEM DADOS DE PESSOA
    var dataToSender = {
        'idPessoa': self.idPessoa,
        'tipoPessoa': 'fisica',
        'nome': smartCapitalize(self.inputNomePeFisica.val()),
        'emailPrincipal': self.inputEmailPrinPeFisica.val().toLowerCase(),
        'emailAdicional': self.inputEmailAdPeFisica.val().toLowerCase(),

        'cpf': self.inputCPF.val().replace(/[^\d]+/g, ''),
        'rg': self.inputRG.val(),
        'dataEmissao': self.inputDataEmissao.val(),
        'orgaoEmissor': self.inputOrgaoEmissor.val(),
        'ufOrgaoEmissor': self.selectEstadoOrgaoEmissor.val(),
        'paisNacionalidade': self.selectNacionalidade.val(),
        'dataNascimento': self.inputDataNascimento.val(),
        'sexo': self.selectSexo.val(),

        'grupoSanguineo': self.selectGrupoSanguineo.val(),
        'fatorRH': self.selectFatorRH.val(),
        'profissao': self.inputProfissao.val(),

        'pis': self.inputPIS.val(),
        'estadoCivil': self.selectEstadoCivil.val(),
        'nomePai': self.inputNomePai.val(),
        'nomeMae': self.inputNomeMae.val(),

        'naturalidadeMunicipio': self.selectNaturalidadeMunicipio.val(),
        'naturalidadeUF': self.selectNaturalidadeUF.val(),

        'imagem': self.inputFotoPerfil.find('img').attr('src')
    };

    // OBTEM TELEFONES
    var telefones = [];
    self.telefoneContainer.find('.telefoneItem').each(function (index) {
        var telefoneItem = {};
        $(this).find(':input').not('button, input[aria-label="Search"]').each(function (index) {
            if (this.name === 'numeroTelefone') {
                telefoneItem[this.name] = this.value.replace(/[^\d]+/g, '');
            }
            else {
                telefoneItem[this.name] = this.value;
            }
        });
        telefones.push(telefoneItem);
    });
    dataToSender['telefones'] = telefones;

    // OBTEM ENDEREÇOS
    var enderecos = [];
    self.enderecoContainer.find('.enderecoItem').each(function (index) {
        var enderecoItem = {};
        $(this).find(':input').not('button, input[aria-label="Search"]').each(function (index) {
            if (this.name === 'cep') {
                enderecoItem[this.name] = this.value.replace(/[^\d]+/g, '');
            }
            else {
                enderecoItem[this.name] = this.value;
            }
        });
        enderecos.push(enderecoItem);
    });
    dataToSender['enderecos'] = enderecos;

    // OBTEM MATRICULAS DE SERVIDORES
    var servidores = [];
    self.matriculaContainer.find('.matriculaItem').each(function (index) {
        var matriculaItem = {};
        $(this).find(':input').not('button, input[aria-label="Search"]').each(function (index) {
            switch (this.name) {
                case 'inputMatricula':
                    matriculaItem['matricula'] = this.value;
                    break;
                case 'selectCargo':
                    matriculaItem['idCargo'] = this.value;
                    break;
                case 'selectFG':
                    matriculaItem['idFuncaoGratificada'] = this.value;
                    break;
                case 'inputDataAdmissao':
                    matriculaItem['dataAdmissao'] = this.value;
                    break;
                case 'inputDataExoneracao':
                    matriculaItem['dataExoneracao'] = null;
                    break;
                case 'selectVinculo':
                    matriculaItem['idVinculo'] = this.value;
                    break;
                case 'inputLotacao':
                    matriculaItem['idLotacao'] = $(this).attr('data-val');
                    break;
                case 'inputLocalTrabalho':
                    matriculaItem['idLocalTrabalho'] = $(this).attr('data-val');
                    break;
                case 'selectJornadaTrabalho':
                    matriculaItem['idJornadaTrabalho'] = this.value;
                    break;
                case 'checkboxCapturouBiometria':
                    matriculaItem['biometria'] = $(this).is(':checked');
                    break;
                case 'checkboxMatriculaAtivo':
                    matriculaItem['ativo'] = $(this).is(':checked');
                    break;
            }
        });
        servidores.push(matriculaItem);
    });
    dataToSender['servidores'] = servidores;

    // OBTEM CARGA HORARIA DE SERVIDORES
    dataToSender['cargaHoraria'] = self.jTimeline.getSeries();

    console.log(JSON.stringify(dataToSender));

    //self.loaderApi.show();
    //var id = self.idPessoa ? self.idPessoa : '';
    self.restClient.setWebServiceURL(self.webserviceJubarteBaseURL + 'servidores');
    self.restClient.setMethodPUT();
    self.restClient.setDataToSender(dataToSender);
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.loaderApi.hide();
        self.modalApi.success(data['message']);
        window.location = "/pages/cadastroServidor"
    });
    self.restClient.setErrorCallbackFunction(function (jqXHR, textStatus, errorThrown) {
        self.loaderApi.hide();
        console.log(jqXHR.responseJSON);
        self.modalApi.modalError(jqXHR.responseJSON);
    });
    self.restClient.exec();

};
//LISTA SERVIDORES
CadastroServidorViewModel.prototype.getAllServidores = function () {
    var self = this;

    self.dataTableServidores.setDisplayCols([
        {"key": "id"},
        {"key": "matricula"},
        {"key": "nome"},
        {"key": "dataNascimento"},
        {"key": "cpf"},
        {"key": "rg"},
        {"key": "siglaLotacao"}
    ]);

    self.dataTableServidores.hideActionBtnDelete();
    self.dataTableServidores.hideRowSelectionCheckBox();
    self.dataTableServidores.setIsColsEditable(false);
    self.dataTableServidores.setDataToSender({});
    self.dataTableServidores.setSourceURL(self.webserviceJubarteBaseURL + 'servidores');
    self.dataTableServidores.setSourceMethodPOST();
    self.dataTableServidores.setOnDeleteItemAction(function (ids) {
    });
    self.dataTableServidores.setOnClick(function (data) {
        /*console.log(data['cpf']);*/
        self.modalListaServidor.modal('hide');
        self.getServidor(data['cpf']);
    });
    //desabilita o Loader padrão
    //self.dataTablePerfil.defaultLoader(false);
    self.dataTableServidores.setOnReloadAction(function () {
    });
    // mostra o Loader quando clicar no botão reload do dataTable
    self.dataTableServidores.setOnLoadedContent(function () {
    });
    // quando concluir o carregamento esconde o loader
    self.dataTableServidores.setOnAddItemAction(function () {

    });
    self.dataTableServidores.setOnDeleteItemAction(function (ids) {

    });
    self.dataTableServidores.load();
};
CadastroServidorViewModel.prototype.deleteServidor = function (ids) {
    var self = this;

    //console.log(ids);

    if (ids.length === 0) {
        self.modalApi.showModal(ModalAPI.WARNING, 'Pessoas', 'É necessário selecionar ao menos uma pessoa!', 'OK');
        return;
    }

    self.modalApi.showConfirmation(ModalAPI.WARNING, 'Confirmação', 'Tem certeza que deseja remover o(s) pessoa(s) selecionada(s)? A operação não poderá ser desfeita.', 'Sim', 'Não').onClick('Sim', function () {

        self.loaderApi.show();
        var dataToSend = {"ids": ids, "tipoPessoa": 'fisica'};
        self.restClient.setDataToSender(dataToSend);
        self.restClient.setWebServiceURL(self.webserviceJubarteBaseURL + 'pessoas');
        self.restClient.setMethodDELETE();
        self.restClient.setSuccessCallbackFunction(function () {
            self.dataTablePessoaFisica.reload();
            self.loaderApi.hide();
            self.modalApi.notify(ModalAPI.SUCCESS, 'Sistemas', 'Sistema(s) excluído(s) com sucesso');
        });
        self.restClient.setErrorCallbackFunction(function (callback) {
            self.modalApi.showModal(ModalAPI.ERROR, 'Sistemas', callback.responseJSON, 'OK');
            self.loaderApi.hide();
        });
        self.restClient.exec();
    });

};
CadastroServidorViewModel.prototype.getServidor = function (cpf) {
    var self = this;

    self.loaderApi.show();
    self.restClient.setDataToSender({});
    self.restClient.setWebServiceURL(self.webserviceJubarteBaseURL + 'servidores/cpf/' + cpf);
    self.restClient.setMethodGET();
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.loaderApi.hide();
        self.fillFormPessoa(data);
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.modalApi.showModal(ModalAPI.ERROR, 'Sistemas', callback.responseJSON, 'OK');
        self.loaderApi.hide();
    });
    self.restClient.exec();

};
CadastroServidorViewModel.prototype.getCargos = function (select) {
    var self = this;

    self.loaderApi.show();
    self.restClient.setDataToSender({});
    self.restClient.setWebServiceURL(self.webserviceJubarteBaseURL + 'cargos');
    self.restClient.setMethodPOST();
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.loaderApi.hide();
        if (!select) {
            var template = document.getElementById("templateFormMatricula");
            var templateContent = template.content === undefined ? template.querySelector("div") : template.content.querySelector("div");
            var selectCargo = templateContent.querySelector('select[name="selectCargo"]');
            nativeFillSelect(selectCargo, data['data'], 'id', 'nome');
            fillSelect($('[name="selectCargo"]'), data['data'], 'id', 'nome');
        } else {
            fillSelect(select, data['data'], 'id', 'nome');
        }
        self.updateFields();
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.modalApi.showModal(ModalAPI.ERROR, 'Sistemas', callback.responseJSON, 'OK');
        self.loaderApi.hide();
    });
    self.restClient.exec();
};
CadastroServidorViewModel.prototype.getFuncoes = function (select) {
    var self = this;

    self.loaderApi.show();
    self.restClient.setDataToSender({});
    self.restClient.setWebServiceURL(self.webserviceJubarteBaseURL + 'funcoes');
    self.restClient.setMethodPOST();
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.loaderApi.hide();
        if (!select) {
            var template = document.getElementById("templateFormMatricula");
            var templateContent = template.content === undefined ? template.querySelector("div") : template.content.querySelector("div");
            var selectFG = templateContent.querySelector('select[name="selectFG"]');
            nativeFillSelect(selectFG, data['data'], 'id', 'nome');
            fillSelect($('[name="selectFG"]'), data['data'], 'id', 'nome');
        } else {
            fillSelect(select, data['data'], 'id', 'nome');
        }
        self.updateFields();
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.modalApi.showModal(ModalAPI.ERROR, 'Sistemas', callback.responseJSON, 'OK');
        self.loaderApi.hide();
    });
    self.restClient.exec();
};
CadastroServidorViewModel.prototype.getVinculos = function (select) {
    var self = this;
    self.loaderApi.show();
    self.restClient.setDataToSender({});
    self.restClient.setWebServiceURL(self.webserviceJubarteBaseURL + 'vinculos');
    self.restClient.setMethodPOST();
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.loaderApi.hide();
        if (!select) {
            var template = document.getElementById("templateFormMatricula");
            var templateContent = template.content === undefined ? template.querySelector("div") : template.content.querySelector("div");
            var selectVinculo = templateContent.querySelector('select[name="selectVinculo"]');
            nativeFillSelect(selectVinculo, data['data'], 'id', 'vinculo');
            fillSelect($('[name="selectVinculo"]'), data['data'], 'id', 'vinculo');
        } else {
            fillSelect(select, data['data'], 'id', 'vinculo');
        }
        self.updateFields();
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.modalApi.showModal(ModalAPI.ERROR, 'Sistemas', callback.responseJSON, 'OK');
        self.loaderApi.hide();
    });
    self.restClient.exec();
};
CadastroServidorViewModel.prototype.getJornadas = function (select) {
    var self = this;
    self.loaderApi.show();
    self.restClient.setDataToSender({});
    self.restClient.setWebServiceURL(self.webserviceJubarteBaseURL + 'jornadas');
    self.restClient.setMethodPOST();
    self.restClient.setSuccessCallbackFunction(function (data) {
        self.loaderApi.hide();
        if (!select) {
            var template = document.getElementById("templateFormMatricula");
            var templateContent = template.content === undefined ? template.querySelector("div") : template.content.querySelector("div");
            var selectJornadaTrabalho = templateContent.querySelector('select[name="selectJornadaTrabalho"]');
            nativeFillSelect(selectJornadaTrabalho, data['data'], 'id', 'descricao');
            fillSelect($('[name="selectJornadaTrabalho"]'), data['data'], 'id', 'descricao');
        } else {
            fillSelect(select, data['data'], 'id', 'vinculo');
        }
        self.updateFields();
    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.modalApi.showModal(ModalAPI.ERROR, 'Sistemas', callback.responseJSON, 'OK');
        self.loaderApi.hide();
    });
    self.restClient.exec();
};
CadastroServidorViewModel.prototype.getLocais = function () {
    var self = this;
    self.loaderApi.show();
    self.restClient.setDataToSender({});
    self.restClient.setWebServiceURL(self.webserviceJubarteBaseURL + 'locais');
    self.restClient.setMethodPOST();
    self.restClient.setSuccessCallbackFunction(function (data) {

        self.loaderApi.hide();
        var options = '<option value=""></option>';
        var locais = data['data'];
        for (var j = 0; j < locais.length; j++) {
            var id = locais[j]['id'];
            var label = locais[j]['unidade'] + ' - ' + locais[j]['setor'];
            options += '<option value="' + id + '">' + label + '</option>';
        }
        self.selectLocalBiometria[0].innerHTML = options;
        /*self.selectLocalBiometria.selectpicker();
        self.updateFields();*/

    });
    self.restClient.setErrorCallbackFunction(function (callback) {
        self.modalApi.showModal(ModalAPI.ERROR, 'Sistemas', callback.responseJSON, 'OK');
        self.loaderApi.hide();
    });
    self.restClient.exec();
};
CadastroServidorViewModel.prototype.getOrganogramas = function () {
    var self = this;
    self.loaderApi.show();

    self.treeViewOrganograma.setupDisplayItems(
        [
            {'key': 'idOrganograma', 'type': ModernTreeView.ID},
            {'key': 'text', 'type': ModernTreeView.LABEL}
        ]
    );
    self.treeViewOrganograma.setExplorerStyle();
    self.treeViewOrganograma.setMethodPOST();
    self.treeViewOrganograma.setWebServiceURL(self.webserviceJubarteBaseURL + 'organogramas/hierarquia');
    self.treeViewOrganograma.setOnLoadSuccessCallback(function (response) {
        self.loaderApi.hide();
    });
    self.treeViewOrganograma.setOnLoadErrorCallback(function (callback) {
        self.loaderApi.hide();
    });
    self.treeViewOrganograma.setOnClick(function (data) {
        if (self.correntInputOrganograma) {
            self.correntInputOrganograma.val(data['sigla']);
            self.correntInputOrganograma.attr('data-val', data['idOrganograma']);
            self.correntInputOrganograma = null;
        }
        self.modalOrganograma.modal('hide');
    });
    self.treeViewOrganograma.load();
};
CadastroServidorViewModel.prototype.fillSelectHora = function () {
    var self = this;

    var options = '';
    for (var i = 0; i < 24; i++) {
        var hora = i.toLocaleString('en-US', {minimumIntegerDigits: 2, useGrouping: false}) + ':00';
        options += '<option>' + hora + '</option>';
    }
    self.selectHoraEntrada[0].innerHTML = options;
    self.selectHoraSaida[0].innerHTML = options;
};
CadastroServidorViewModel.prototype.fillFormPessoa = function (data) {
    var self = this;

    var pessoa = data['pessoa']['fisica'];
    var enderecos = data['pessoa']['enderecos'];
    var telefones = data['pessoa']["telefones"];

    self.idPessoa = pessoa['id'];
    self.inputNomePeFisica.val(pessoa['nome']);
    self.inputEmailPrinPeFisica.val(pessoa['emailPrincipal']);
    self.inputEmailAdPeFisica.val(pessoa['emailAdicional']);
    self.inputCPF.val(pessoa['cpf']);
    self.inputCPF.mask("000.000.000-00");
    self.selectSexo.val(pessoa['sexo']);
    self.inputDataNascimento.val(sqlDateToBrasilDate(pessoa['dataNascimento']));
    self.inputRG.val(pessoa['rg']);
    self.inputOrgaoEmissor.val(pessoa['orgaoEmissor']);
    self.inputDataEmissao.val(sqlDateToBrasilDate(pessoa['dataEmissao']));
    self.selectEstadoOrgaoEmissor.val(pessoa['idUfOrgaoEmissor']);
    self.selectNacionalidade.val(pessoa['idPaisNacionalidade']);
    self.inputFotoPerfil.find('img').attr('src',pessoa['image']);

    self.getUFs(self.selectNaturalidadeUF, pessoa['naturalidadeUF']);
    self.getMunicipios(self.selectNaturalidadeMunicipio, pessoa['naturalidadeUF'], pessoa['naturalidadeMunicipio']);

    self.inputProfissao.val(pessoa['profissao']);
    self.selectGrupoSanguineo.val(pessoa['grupoSanguineo']);
    self.selectFatorRH.val(pessoa['fatorRH']);
    self.inputPIS.val(pessoa['pis']);
    self.selectEstadoCivil.val(pessoa['estadoCivil']);
    self.inputNomePai.val(pessoa['nomePai']);
    self.inputNomeMae.val(pessoa['nomeMae']);

    self.updateFields();

    if (enderecos.length >= 1) {
        self.enderecoContainer.empty();
    }

    enderecos.forEach(function (item, index, array) {
        self.addFormEndereco(item);
    });

    if (telefones.length >= 1) {
        self.telefoneContainer.empty();
    }

    telefones.forEach(function (item, index, array) {
        self.addFormTelefone(item['numero'], item['tipo']);
    });

    self.matriculaContainer.empty();
    var servidor = data['servidor'];
    servidor.forEach(function (item, index, array) {
        self.addFormMatricula(item);
    });

};

