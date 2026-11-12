<?php
declare(strict_types=1);

namespace App\Controller;

use App\Core\ConexaoBanco;

final class HomeController extends Controller
{
    public function index(): void
    {
        $contagens = [];
        foreach (['tutores' => 'tutor', 'pets' => 'pet', 'agendamentos' => 'agendamento'] as $rotulo => $tabela) {
            $contagens[$rotulo] = (int) ConexaoBanco::obter()
                ->query('SELECT COUNT(*) FROM ' . $tabela)
                ->fetchColumn();
        }

        $this->renderizar('inicio', [
            'titulo' => 'Painel da clínica',
            'usuario' => $_SESSION['usuario'],
            'contagens' => $contagens,
        ]);
    }
}