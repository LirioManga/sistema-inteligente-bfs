<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Triagem Hospitalar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in { animation: fadeIn 0.3s ease-in; }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>
</head>
<body class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen">
    <!-- Header -->
    <header class="bg-white shadow-md">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
                </svg>
                <h1 class="text-xl font-bold text-gray-800">Sistema de Triagem</h1>
            </div>
            <button onclick="toggleAdminLogin()" class="text-sm text-blue-600 hover:text-blue-800 font-medium">
                Área Admin
            </button>
        </div>
    </header>

    <div class="container mx-auto px-4 py-8 max-w-4xl">
        <!-- Patient View (Default) -->
        <div id="patientView" class="space-y-6">
            <!-- Welcome Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
                <h2 class="text-2xl md:text-3xl font-bold text-gray-800 mb-2">Bem-vindo ao Sistema de Triagem</h2>
                <p class="text-gray-600">Descreva seus sintomas e nós indicaremos o departamento adequado para seu atendimento.</p>
            </div>

            <!-- Symptoms Input Card -->
            <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
                <label class="block text-lg font-semibold text-gray-700 mb-3">Descreva seus sintomas</label>
                <textarea id="symptomsInput" rows="5" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Ex: Estou com febre alta, tosse seca e dificuldade para respirar..."></textarea>

                <!-- Frequent Symptoms Suggestions -->
                <div class="mt-4">
                    <p class="text-sm text-gray-600 mb-2">Sintomas frequentes:</p>
                    <div class="flex flex-wrap gap-2">
                        <button onclick="addSuggestion('febre alta')" class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm hover:bg-blue-100 transition">febre alta</button>
                        <button onclick="addSuggestion('dor no peito')" class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm hover:bg-blue-100 transition">dor no peito</button>
                        <button onclick="addSuggestion('dor de cabeça intensa')" class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm hover:bg-blue-100 transition">dor de cabeça intensa</button>
                        <button onclick="addSuggestion('náusea e vômito')" class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm hover:bg-blue-100 transition">náusea e vômito</button>
                        <button onclick="addSuggestion('dificuldade para respirar')" class="px-3 py-1 bg-blue-50 text-blue-700 rounded-full text-sm hover:bg-blue-100 transition">dificuldade para respirar</button>
                    </div>
                </div>

                <div id="symptomsError" class="mt-2 text-sm text-red-600 hidden"></div>

                <button onclick="analyzeSymptoms()" class="mt-4 w-full bg-blue-600 text-white py-3 rounded-lg font-semibold hover:bg-blue-700 transition duration-200">
                    Analisar Sintomas
                </button>
            </div>

            <!-- Result Card (Hidden by default) -->
            <div id="resultCard" class="bg-white rounded-lg shadow-lg p-6 md:p-8 hidden fade-in">
                <div class="flex items-start space-x-4">
                    <div class="flex-shrink-0">
                        <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div class="flex-1">
                        <h3 class="text-xl font-bold text-gray-800 mb-2">Departamento Recomendado</h3>
                        <div id="departmentResult" class="text-gray-700"></div>
                        <button onclick="resetForm()" class="mt-4 text-blue-600 hover:text-blue-800 font-medium">
                            Nova consulta →
                        </button>
                    </div>
                </div>
            </div>

            <!-- Loading Spinner -->
            <div id="loadingSpinner" class="hidden text-center py-8">
                <div class="inline-block animate-spin rounded-full h-12 w-12 border-b-2 border-blue-600"></div>
                <p class="mt-4 text-gray-600">Analisando sintomas...</p>
            </div>
        </div>

        <!-- Admin Login Modal -->
        <div id="adminLoginModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
            <div class="bg-white rounded-lg shadow-xl p-6 md:p-8 w-full max-w-md fade-in">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Login Admin</h2>
                    <button onclick="toggleAdminLogin()" class="text-gray-400 hover:text-gray-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
                <form onsubmit="handleAdminLogin(event)">
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Usuário</label>
                            <input type="text" id="adminUsername" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="admin">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Senha</label>
                            <input type="password" id="adminPassword" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="••••••••">
                        </div>
                        <div id="loginError" class="text-sm text-red-600 hidden"></div>
                        <button type="submit" class="w-full bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                            Entrar
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Admin Dashboard -->
        <div id="adminView" class="hidden space-y-6">
            <div class="bg-white rounded-lg shadow-lg p-6 md:p-8">
                <div class="flex justify-between items-center mb-6">
                    <h2 class="text-2xl font-bold text-gray-800">Gestão de Departamentos</h2>
                    <button onclick="logout()" class="text-sm text-red-600 hover:text-red-800 font-medium">
                        Sair
                    </button>
                </div>

                <!-- Add/Edit Department Form -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4" id="formTitle">Adicionar Departamento</h3>
                    <form onsubmit="saveDepartment(event)">
                        <input type="hidden" id="deptId">
                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Nome do Departamento *</label>
                                <input type="text" id="deptName" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent" placeholder="Ex: Cardiologia">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Descrição *</label>
                                <textarea id="deptDescription" required rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" placeholder="Ex: Especializado em doenças do coração e sistema cardiovascular"></textarea>
                            </div>
                            <div class="flex space-x-3">
                                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                                    Salvar
                                </button>
                                <button type="button" onclick="cancelEdit()" id="cancelBtn" class="hidden flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg font-semibold hover:bg-gray-400 transition">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>

                <!-- Departments List -->
                <div>
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Departamentos Cadastrados</h3>
                    <div id="departmentsList" class="space-y-3">
                        <!-- Departments will be rendered here -->
                    </div>
                    <div id="emptyState" class="text-center py-8 text-gray-500">
                        Nenhum departamento cadastrado ainda.
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mock data storage (replace with actual backend calls)
        let departments = [];
        let isAuthenticated = false;

        // Load initial data
        function loadDepartments() {
            const saved = localStorage.getItem('departments');
            if (saved) {
                departments = JSON.parse(saved);
            }
            renderDepartments();
        }

        // Save to localStorage (simulate backend)
        function saveDepartmentsToStorage() {
            localStorage.setItem('departments', JSON.stringify(departments));
        }

        // Toggle admin login modal
        function toggleAdminLogin() {
            const modal = document.getElementById('adminLoginModal');
            modal.classList.toggle('hidden');
            document.getElementById('loginError').classList.add('hidden');
        }

        // Handle admin login
        function handleAdminLogin(e) {
            e.preventDefault();
            const username = document.getElementById('adminUsername').value;
            const password = document.getElementById('adminPassword').value;

            // Mock authentication (replace with actual backend call)
            if (username === 'admin' && password === 'admin123') {
                isAuthenticated = true;
                toggleAdminLogin();
                showAdminView();
            } else {
                const errorDiv = document.getElementById('loginError');
                errorDiv.textContent = 'Usuário ou senha incorretos';
                errorDiv.classList.remove('hidden');
            }
        }

        // Show admin view
        function showAdminView() {
            document.getElementById('patientView').classList.add('hidden');
            document.getElementById('adminView').classList.remove('hidden');
            loadDepartments();
        }

        // Logout
        function logout() {
            isAuthenticated = false;
            document.getElementById('adminView').classList.add('hidden');
            document.getElementById('patientView').classList.remove('hidden');
        }

        // Render departments list
        function renderDepartments() {
            const list = document.getElementById('departmentsList');
            const emptyState = document.getElementById('emptyState');

            if (departments.length === 0) {
                list.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            list.innerHTML = departments.map(dept => `
                <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                    <div class="flex justify-between items-start">
                        <div class="flex-1">
                            <h4 class="font-semibold text-gray-800">${dept.nome}</h4>
                            <p class="text-sm text-gray-600 mt-1">${dept.descricao}</p>
                        </div>
                        <div class="flex space-x-2 ml-4">
                            <button onclick="editDepartment(${dept.id})" class="text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                            <button onclick="deleteDepartment(${dept.id})" class="text-red-600 hover:text-red-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            `).join('');
        }

        // Save department (create or update)
        function saveDepartment(e) {
            e.preventDefault();
            const id = document.getElementById('deptId').value;
            const nome = document.getElementById('deptName').value.trim();
            const descricao = document.getElementById('deptDescription').value.trim();

            if (!nome || !descricao) return;

            if (id) {
                // Update existing
                const index = departments.findIndex(d => d.id == id);
                departments[index] = { id: parseInt(id), nome, descricao };
            } else {
                // Create new
                const newId = departments.length > 0 ? Math.max(...departments.map(d => d.id)) + 1 : 1;
                departments.push({ id: newId, nome, descricao });
            }

            saveDepartmentsToStorage();
            renderDepartments();
            cancelEdit();
        }

        // Edit department
        function editDepartment(id) {
            const dept = departments.find(d => d.id === id);
            if (!dept) return;

            document.getElementById('deptId').value = dept.id;
            document.getElementById('deptName').value = dept.nome;
            document.getElementById('deptDescription').value = dept.descricao;
            document.getElementById('formTitle').textContent = 'Editar Departamento';
            document.getElementById('cancelBtn').classList.remove('hidden');

            window.scrollTo({ top: 0, behavior: 'smooth' });
        }

        // Delete department
        function deleteDepartment(id) {
            if (confirm('Tem certeza que deseja excluir este departamento?')) {
                departments = departments.filter(d => d.id !== id);
                saveDepartmentsToStorage();
                renderDepartments();
            }
        }

        // Cancel edit
        function cancelEdit() {
            document.getElementById('deptId').value = '';
            document.getElementById('deptName').value = '';
            document.getElementById('deptDescription').value = '';
            document.getElementById('formTitle').textContent = 'Adicionar Departamento';
            document.getElementById('cancelBtn').classList.add('hidden');
        }

        // Add symptom suggestion
        function addSuggestion(text) {
            const input = document.getElementById('symptomsInput');
            const current = input.value.trim();
            input.value = current ? `${current}, ${text}` : text;
            input.focus();
        }

        // Analyze symptoms (patient view)
        async function analyzeSymptoms() {
            const symptoms = document.getElementById('symptomsInput').value.trim();
            const errorDiv = document.getElementById('symptomsError');

            errorDiv.classList.add('hidden');

            if (!symptoms || symptoms.length < 10) {
                errorDiv.textContent = 'Por favor, descreva seus sintomas com mais detalhes (mínimo 10 caracteres)';
                errorDiv.classList.remove('hidden');
                return;
            }

            // Show loading
            document.getElementById('loadingSpinner').classList.remove('hidden');

            const url = 'http://localhost:8000/gemini/interpretar';

            try {
                const response = await fetch('/gemini/interpretar', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        texto: symptoms
                    })
                });

                if (!response.ok) {
                    throw new Error(`Response status: ${response.status}`);
                }

                const result = await response.json();
                console.log(result);
            } catch (error) {
                console.error(`Erro: ${error}`)
            }

            // Simulate API call to backend
            // Replace this with actual fetch to your backend
            setTimeout(() => {
                // Mock response
                // fetch('/gemini/interpretar', {
                //     method: 'POST',
                //     headers: {
                //     'Content-type': 'application/json',
                //     // 'apikey': apiKey
                //     },
                //     // body: data
                // }).then(response => {
                //     if (response.ok) {
                //         console.log
                //     return response.json();
                //     }
                //     throw new Error('Request failed!');
                // }, networkError => {
                //     console.log(networkError.message)
                // })


                // const mockDepartment = {
                //     nome: 'Cardiologia',
                //     descricao: 'Com base nos sintomas informados, recomendamos atendimento no departamento de Cardiologia.'
                // };

                // displayResult(mockDepartment);
            }, 1500);
        }

        // Display result
        function displayResult(department) {
            document.getElementById('loadingSpinner').classList.add('hidden');

            const resultDiv = document.getElementById('departmentResult');
            resultDiv.innerHTML = `
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <p class="font-semibold text-lg text-blue-800">${department.nome}</p>
                    <p class="text-gray-700 mt-2">${department.descricao}</p>
                </div>
            `;

            document.getElementById('resultCard').classList.remove('hidden');
            document.getElementById('symptomsInput').disabled = true;
        }

        // Reset form
        function resetForm() {
            document.getElementById('symptomsInput').value = '';
            document.getElementById('symptomsInput').disabled = false;
            document.getElementById('resultCard').classList.add('hidden');
            document.getElementById('symptomsError').classList.add('hidden');
        }

        // Initialize
        loadDepartments();
    </script>
</body>
</html>
