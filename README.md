# Aplicação de Reconhecimento Facial

Esta aplicação permite o registro e o reconhecimento facial de alunos para o controle de presença em ambiente acadêmico.

## Introdução

A aplicação cliente comunica com o servidor por meio de uma API REST, utilizando JSON para o envio e recebimento de dados. A aplicação cliente utiliza HTML+ CSS + Javascript, e a aplicação no servidor utiliza PHP + MySQL.

O PHP utiliza o PSR-4, A PSR-4 é o padrão de desenvolvimento moderno de aplicações PHP e possui tudo que o desenvolvedor precisa para trabalhar com orientação a objetos.

A aplicação cliente está hospedada no subdomínio: [rest.faustinopsy.com](https://rest.faustinopsy.com/)

A aplicação servidor está hospedada no subdomínio: [webcrud.faustinopsy.com](https://webcrud.faustinopsy.com/)

A API do servidor só aceita requisições do domínio rest.faustinopsy.com.

### Fluxo do REST

![Fluxo do REST](img/restsimples.txt)

## Diagramas

### Fluxo do Administrador

![Fluxo do Administrador](img/case-admin.svg)

### Fluxo do Aluno

![Fluxo do Aluno](img/case-aluno.svg)

### Estrutura de Componentes Front-End

![Estrutura de Componentes Front-End](img/class-front.svg)

### Classes do Back-End

![Classes do Back-End](img/class-back.svg)

### Estrutura do Banco de Dados

![Estrutura do Banco de Dados](img/class-banco.svg)

### Diagrama de Sequência

![Diagrama de Sequência](img/sequencia.svg)
