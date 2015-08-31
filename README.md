# TesouroDireto

Projeto criado para manter um histórico de preços de títulos públicos federais do Tesouro Nacional.

O principal objetivo é ter um sistema 100% portátil e descentralizado, de forma que ele possa ser instalado e executado através de um USB Drive e levado à qualquer lugar. Mais especificamente, ele foi desenvolvido sobre uma instalação portátil de um servidor XAMPP.

O TesouroDireto conta com algumas características que garantem a descentralização e baixa dependência do sistema:

  * roda em servidores portáteis
  * utiliza banco de dados SQLITE3
  * web scrap para coleta de taxas diretamente do site do Tesouro Direto
  * sincronia de arquivo de banco de dados com pasta do Dropbox

O histórico de taxas pode ter duas origens distintas
  * website do Tesouro Direto
  * banco de dados compartilhado existente em uma pasta do Dropbox

Sempre que o servidor estiver ativo e a página /update estiver aberta, o TesouroDireto importa um arquivo do banco de dados SQLITE da pasta Dropbox, realiza uma mescla com o banco de dados local, atualiza os dados através de taxas obtidas do site do Tesouro Direto e exporta este banco de dados de volta ao Dropbox. Desta forma, o sistema funciona de forma local, porém há a possibilidade de múltiplos peers se auxiliarem na atualização do banco de dados.

Atualmente, o projeto se encontra inacabado. Apenas os gráficos iniciais funcionam. A ideia final é ter um sistema com funções de notificações por e-mail, gráficos de séries temporais e usuários.

Obviamente, sugere-se que este sistema seja utilizado apenas por pessoas conhecidas, já que o acesso à pasta Dropbox e banco de dados compartilhado pode trazer riscos aos usuários.
