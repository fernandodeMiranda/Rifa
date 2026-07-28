<?php

namespace App\Helpers;

use App\Core\Session;

/**
 * Mensagens de uma exibição só (sucesso/erro) entre redirecionamentos.
 */
final class Flash
{
    public static function sucesso(string $mensagem): void
    {
        Session::flash('sucesso', $mensagem);
    }

    public static function erro(string $mensagem): void
    {
        Session::flash('erro', $mensagem);
    }

    public static function consumirSucesso(): ?string
    {
        return Session::flash('sucesso');
    }

    public static function consumirErro(): ?string
    {
        return Session::flash('erro');
    }
}
