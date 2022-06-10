### FORSETI CODI CHALLENGE

Foi criado uma aplicação básica para extrair dados de um site e gravar num arquivo CSV que é baixado automaticamente.

## Portanto, o que foi seguido:
- Extrair as informações necessárias (manchete, data/hora e link dos detalhes)
- Buscar notícias até a página 5
- Salvar esses dados estruturados num arquivo csv
- Garantir que as notícias não se dupliquem no registro final -> Nesse caso, toda vez é gerado um novo arquivo CSV, seria necessário caso fosse gravado num banco de dados.

## TESTAR
Para testar a aplicação é bem simples.
São necessários apenas dois arquivos: o index.php e a classe que faz todo o trabalho /classes/class.wc.php
O arquivo index chama a classe que é ativada automaticamente, a qual busca os dados no site, das 5 primeiras páginas e grava os dados num arquivo CSV que é gerado automaticamente com a data atual e é baixado no navegador.
Roda em qualquer ambiente que esteja sendo executado um servidor PHP. 