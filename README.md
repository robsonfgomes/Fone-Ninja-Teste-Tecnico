# 🧪 Desafio Técnico – Laravel + Vue

Bem-vindo(a)! 👋  
Este desafio tem como objetivo avaliar suas habilidades práticas em **Laravel (backend)** e **Vue (frontend)**.  

---

## 📌 Contexto
Você foi contratado para implementar funcionalidades de um **ERP de estoque**.  
O sistema precisa permitir **cadastrar produtos**, **registrar compras e vendas**, controlar **estoque** e calcular **lucro**.

---

## 🎯 Objetivos do desafio
- Criar **cadastro de produtos**.  
- Implementar **compra de produtos** (entrada de estoque e atualização do custo médio).  
- Implementar **venda de produtos** (saída de estoque, cálculo de receita e lucro).  
- Criar **telas em Vue** para gerenciar produtos, compras e vendas.  

---

## 🛠️ Backend – Laravel
Implemente os seguintes endpoints:

### Produtos
- **Cadastrar produto**  
  `POST /api/produtos`  
  Campos:  
  - `nome` (obrigatório, mínimo 3 caracteres)  
  - `preco_venda` (valor sugerido de venda, deve ser positivo)  
  - `estoque_inicial = 0`  

- **Listar produtos**  
  `GET /api/produtos`  
  Retornar: id, nome, custo_medio, preco_venda e estoque atual.  

---

### Compras
- **Registrar compra**  
  `POST /api/compras`  
  Payload:
  ```json
  {
    "fornecedor": "Fornecedor X",
    "produtos": [
      { "id": 1, "quantidade": 50, "preco_unitario": 20 },
      { "id": 2, "quantidade": 30, "preco_unitario": 10 }
    ]
  }
  ```

### Regras:

  - Atualizar estoque (entrada).

  - Atualizar custo médio do produto

💰 Vendas

Registrar venda
POST /api/vendas
Payload:
  ```json
  {
    "cliente": "Fulano da Silva",
    "produtos": [
      { "id": 1, "quantidade": 2, "preco_unitario": 50 },
      { "id": 3, "quantidade": 1, "preco_unitario": 100 }
    ]
  }
  ```

Regras:
  
  - Validar estoque suficiente.
  
  - Baixar estoque (saída).
  
  - Calcular lucro da venda


  - Retornar no JSON o total da venda e o lucro calculado.

  - Cancelar venda (opcional)

  - Deve reverter o estoque.

💻 Frontend – Vue

Implemente as seguintes telas:

  - Cadastro de produto

  - Formulário com nome e preço de venda sugerido.

  - Mostrar lista de produtos com custo médio, preço e estoque.

  - Cadastro de compra

  - Formulário para adicionar produtos, quantidades e preço unitário.

  - Atualizar estoque e custo médio.

  - Cadastro de venda

  - Formulário para selecionar produtos e quantidades.

  - Mostrar total da venda e lucro estimado.

  - Exibir mensagens de sucesso ou erro (ex: “Estoque insuficiente”).

⚡ Diferencial: Tela para listar todas as vendas e compras.
