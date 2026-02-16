<?php require_once __DIR__ . '/layout/header.php'; ?>

<h2>Bem-vindo ao Sistema TCGBalcão</h2>
<p>Você está logado como <strong><?= $_SESSION['perfil'] ?? 'Usuário' ?></strong> na loja <strong><?= $_SESSION['LOJA']['nome'] ?? '' ?></strong>.</p>

<div style="margin-top:20px;">
    <h3>Alertas do Sistema</h3>
    <ul>
        <li>📢 Nenhum alerta no momento.</li>
        <li>✅ Sistema funcionando normalmente.</li>
    </ul>
</div>

<?php require_once __DIR__ . '/layout/footer.php'; ?>

