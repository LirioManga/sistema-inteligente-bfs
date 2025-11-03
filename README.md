# 🧭 Mapa Inteligente do Campus

Protótipo de sistema inteligente desenvolvido em **Laravel**, que utiliza o algoritmo de **Busca em Largura (BFS)** para encontrar o **caminho mais curto** entre dois locais de um campus universitário.  
O sistema apresenta o percurso visualmente num mapa interativo com **Leaflet.js**.

---

## 🎯 Objectivo do Projecto

O projeto foi desenvolvido no âmbito da disciplina de **Engenharia de Software e Inteligência Artificial**, com o objectivo de aplicar conceitos de **agentes inteligentes**, **modelos PEAS** e **estratégias de busca sem informação** (neste caso, BFS).

O agente é responsável por explorar o ambiente (o mapa do campus) e determinar a melhor rota entre dois pontos.

---

## 🧠 Tipo de Agente

**Agente baseado em problemas**

- Formula um problema (encontrar um caminho entre origem e destino);
- Explora o espaço de estados (locais e ligações);
- Utiliza a estratégia de busca **BFS** para encontrar a solução mais curta.

---

## ⚙️ Modelo PEAS

| Elemento | Descrição |
|-----------|------------|
| **Performance** | Eficiência em encontrar o caminho mais curto e o tempo de resposta. |
| **Environment** | Mapa do campus (representado como um grafo com nós e ligações). |
| **Actuators** | Movimentos possíveis entre locais (ligados por caminhos). |
| **Sensors** | Capacidade de identificar o local atual e os caminhos disponíveis. |

---

## 🔍 Tipo de Busca

### **Busca em Largura (Breadth-First Search – BFS)**

- Tipo de busca **sem informação**.  
- Explora o grafo nível a nível, garantindo o **menor número de passos** até ao destino.  
- Ideal para ambientes simples e discretos (como um mapa de campus).

---

## 🧩 Arquitetura do Sistema - Por enquanto

```
prototipo-campus/
├── app/
│   ├── Http/Controllers/
│   │   └── RotaController.php
│   ├── Services/
│   │   └── BuscaService.php
│   └── Models/
│       ├── Local.php
│       └── Ligacao.php
├── database/
│   └── migrations/
│       ├── create_locais_table.php
│       └── create_ligacoes_table.php
├── resources/views/
│   ├── mapa.blade.php
│   └── layout.blade.php
└── routes/web.php
```

---

## 🗺️ Interface (Leaflet.js)

- Exibe o mapa do campus com **OpenStreetMap**.
- O utilizador seleciona **origem** e **destino**.
- O sistema desenha o **percurso no mapa** com uma linha azul e marcadores.

---

## 🛠️ Tecnologias Utilizadas

| Componente | Tecnologia |
|-------------|-------------|
| **Backend** | Laravel 12  |
| **Frontend** | HTML, Tailwind CSS, JavaScript |
| **Mapas** | Leaflet.js + OpenStreetMap |
| **Base de Dados** | MySQL |
| **IA** | Algoritmo de Busca em Largura (BFS) |
| **Documentação** | Draw.io, Markdown, Word/PDF |

---

## 🚀 Instalação

1. Clonar o repositório:
   ```bash
   git clone https://github.com/SEU_USUARIO/mapa-inteligente-campus.git
   cd mapa-inteligente-campus
   ```

2. Instalar dependências:
   ```bash
   composer install
   npm install && npm run dev
   ```

3. Configurar o ambiente:
   ```bash
   cp .env.example .env
   php artisan key:generate
   php artisan migrate
   ```

4. Executar o servidor:
   ```bash
   php artisan serve
   ```

5. Aceder no navegador:
   ```
   http://localhost:8000
   ```

---

## 🔮 Melhorias Futuras - Mas podes fazer isso, agora
- Permitir **rotas alternativas** ou por categorias (edifícios, cantina, biblioteca) -- mas entramos na **BUSCA HEURISTICA**.  
- Tornar o mapa **dinâmico**, permitindo adicionar novos pontos via interface.

---

## 👨‍💻 Equipa de Desenvolvimento

| Nome | Função |
|------|--------|
| [Lírio Manga] | Backend e Integração BFS |
| [Fernando Maleiane] | Documentação, Diagramas e Interface |

---

## 📄 Licença

Este projeto é de uso académico e educativo.  
© 2025 - MA-LI.
