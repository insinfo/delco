$(function () {
    var pesquisaUABViewModel = new PesquisaUABViewModel();
    pesquisaUABViewModel.init();
});

PesquisaUABViewModel = function () {

    this.BASE_WEBSERVICE_URL = 'http://192.168.133.12/portalPmro/FrontEnd/api/';
    //form
    this.nome = $('#nome');
    this.email = $('#email');
    this.telefone = $('#telefone');
    this.logradouro = $('#logradouro');
    this.bairro = $('#bairro');
    this.complemento = $('#complemento');
    this.cidade = $('#cidade');
    this.estado = $('#estado');
    this.curso01 = $('#curso01');
    this.curso02 = $('#curso02');
    this.curso03 = $('#curso03');
    this.curso04 = $('#curso04');

    this.btnSave = $('#btnSave');
    this.btnReset = $('#btnReset');
    this.modalAlert = $('#modalAlert');
    this.modalText = $('#modalText');
    this.cursosBlock = $('#cursosBlock');
};
PesquisaUABViewModel.prototype.init = function () {
    var self = this;
    self.events();
};
PesquisaUABViewModel.prototype.events = function () {
    var self = this;

    self.btnSave.click(function () {
        self.save();
    });

    self.btnReset.click(function () {
        self.resetForm();
    });
};
PesquisaUABViewModel.prototype.validateForm = function () {
    var self = this;

    if(self.nome.val().trim() === "")
    {
        self.showModal('Digite seu nome completo');
        return false;
    }

    if(self.email.val().trim() === "")
    {
        self.showModal('Digite seu email');
        return false;
    }

    if(self.telefone.val().trim() === "")
    {
        self.showModal('Digite seu telefone');
        return false;
    }

    if(self.logradouro.val().trim() === "")
    {
        self.showModal('Digite seu logradouro');
        return false;
    }

    if(self.bairro.val().trim() === "")
    {
        self.showModal('Digite o bairro');
        return false;
    }

    if(self.cidade.val().trim() === "")
    {
        self.showModal('Digite a cidade');
        return false;
    }

    if(self.cidade.val().trim() === "Default")
    {
        self.showModal('Selecione o estado');
        return false;
    }
    var isChecAtLeastOne = false;
    self.cursosBlock.find('input[type=checkbox]').each(function () {
        if(this.checked){
            isChecAtLeastOne = true;
            return;
        }
    });

    if(!isChecAtLeastOne)
    {
        self.showModal('Selecione ao menos um curso');
        return false;
    }

    return true;
};
PesquisaUABViewModel.prototype.resetForm = function () {
    var self = this;
    self.nome.val('');
    self.email.val('');
    self.telefone.val('');
    self.logradouro.val('');
    self.bairro.val('');
    self.complemento.val('');
    self.cidade.val('');
    self.estado.val('Default');
    self.curso01.prop('checked',false);
    self.curso02.prop('checked',false);
    self.curso03.prop('checked',false);
    self.curso04.prop('checked',false);
};
PesquisaUABViewModel.prototype.showModal = function (mensage){
    var self = this;
    self.modalText.text(mensage);
    self.modalAlert.toggleClass('modal-active');
};
PesquisaUABViewModel.prototype.save = function () {
    var self = this;

    if(self.validateForm() === false){
        return;
    }
    var dataToSend = {
        "nome": self.nome.val(),
        "email": self.email.val(),
        "telefone": self.telefone.val(),
        "logradouro": self.logradouro.val(),
        "bairro": self.bairro.val(),
        "complemento": self.complemento.val(),
        "cidade": self.cidade.val(),
        "estado": self.estado.val(),
        "curso01": self.curso01.is(':checked'),
        "curso02": self.curso02.is(':checked'),
        "curso03": self.curso03.is(':checked'),
        "curso04": self.curso04.is(':checked')
    };

    dataToSend = JSON.stringify(dataToSend);
    console.log(dataToSend);

    $.ajax({
        type: 'PUT',
        url: self.BASE_WEBSERVICE_URL + 'pesquisa/uab/',
        headers: null,
        dataType: 'json',// data type of response
        data: dataToSend,
        contentType: 'application/json; charset=utf-8', //traditional: self,
        success: function (data) {
            console.log(data['message']);
            self.showModal('Obrigado pelo seu Cadastro!');
            self.resetForm();
        },
        error: function (jqXHR, textStatus, errorThrown) {
            console.log(jqXHR.responseJSON);
            self.showModal('Não foi pocivel realizar o seu cadastro, ouve um erro desconhecido!');
            self.resetForm();
        }

    });
};