<?php
/**
 * Created by PhpStorm.
 * User: Isaque
 * Date: 26/07/2017
 * Time: 15:07
 */

namespace Delco\Util;

class StatusMessage
{
    const SUCCESS = "Processo concluído com sucesso!";
    const ERROR = "Ocorreu um erro desconhecido, tente mais tarde!";
    const REQUIRED_PARAMETERS = "È nessessario o preenchimento correto dos parametros!";
    const NO_CONTENT = "Sem registros para essa consulta";
    const MENSAGEM_ERRO_PADRAO = 'Houve um erro ao executar esta ação, verifique se o procedimento executado esta correto, confire se os dados estão corretos, e tenha certeza que você não esta executando uma operação probida, se o erro persistir contate o suporte técnico.';
    const MENSAGEM_DE_SUCESSO_PADRAO = 'Operação realizada com sucesso!';
    const TODOS_ITENS_DELETADOS = 'Todos os itens foram deletados com sucesso';
    const NEM_TODOS_ITENS_DELETADOS = 'Nem todos os itens foram deletados com sucesso';
    const NENHUM_ITEM_DELETADO = 'Nenhum dos itens foram deletados';
    const CREDENCIAL_INVALIDA = 'Credencial Invalida';
    const ACESSO_AUTORIZADO = 'Acesso Autorizado!';
}