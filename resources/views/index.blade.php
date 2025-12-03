<!DOCTYPE html>
<html lang="pt">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistema de Triagem Hospitalar</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .fade-in {
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: translateY(-10px);
            }

            to {
                opacity: 1;
                transform: translateY(0);
            }
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

            <div class="bg-white rounded-lg shadow-lg p-6 md:p-8 flex flex-col gap-4">
                <div class="flex gap-10">
                    <div class="w-1/2">
                        <label class="block text-lg font-semibold text-gray-700 mb-3">Seu nome:</label>
                        <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" id="user-name" type="text">
                    </div>

                    <div class="w-1/2">
                        <label class="block text-lg font-semibold text-gray-700 mb-3">Seu email:</label>
                        <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none" id="user-email" type="text">
                    </div>
                </div>
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
            <!-- Header with Logout -->
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex justify-between items-center">
                    <h2 class="text-2xl font-bold text-gray-800">Dashboard Administrativo</h2>
                    <button onclick="logout()" class="px-4 py-2 bg-red-600 text-white rounded-lg font-medium hover:bg-red-700 transition">
                        Sair
                    </button>
                </div>
            </div>

            <!-- Statistics Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total de Departamentos</p>
                            <p class="text-3xl font-bold text-blue-600 mt-2" id="totalDepartments">0</p>
                        </div>
                        <svg class="w-12 h-12 text-blue-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Total de Triagens</p>
                            <p class="text-3xl font-bold text-green-600 mt-2" id="totalTriages">0</p>
                        </div>
                        <svg class="w-12 h-12 text-green-200" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M9 2a1 1 0 000 2h2a1 1 0 100-2H9z"></path>
                            <path fill-rule="evenodd" d="M4 5a2 2 0 012-2 3 3 0 003 3h2a3 3 0 003-3 2 2 0 012 2v11a2 2 0 01-2 2H6a2 2 0 01-2-2V5zm3 4a1 1 0 000 2h.01a1 1 0 100-2H7zm3 0a1 1 0 000 2h3a1 1 0 100-2h-3zm-3 4a1 1 0 100 2h.01a1 1 0 100-2H7zm3 0a1 1 0 100 2h3a1 1 0 100-2h-3z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>

                <div class="bg-white rounded-lg shadow-lg p-6">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-600 text-sm font-medium">Triagens Pendentes</p>
                            <p class="text-3xl font-bold text-orange-600 mt-2" id="pendingTriages">0</p>
                        </div>
                        <svg class="w-12 h-12 text-orange-200" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Navigation Tabs -->
            <div class="bg-white rounded-lg shadow-lg p-2">
                <div class="flex space-x-2">
                    <button onclick="showTab('departments')" id="tabDepartments" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg font-medium transition">
                        Departamentos
                    </button>
                    <button onclick="showTab('triages')" id="tabTriages" class="flex-1 px-4 py-2 bg-gray-200 text-gray-700 rounded-lg font-medium hover:bg-gray-300 transition">
                        Triagens
                    </button>
                </div>
            </div>

            <!-- Departments Tab -->
            <div id="departmentsTab" class="bg-white rounded-lg shadow-lg p-6 md:p-8">
                <h3 class="text-xl font-bold text-gray-800 mb-6">Gestão de Departamentos</h3>

                <!-- Add/Edit Department Form -->
                <div class="bg-gray-50 rounded-lg p-6 mb-6">
                    <h4 class="text-lg font-semibold text-gray-800 mb-4" id="formTitle">Adicionar Departamento</h4>
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
                    <h4 class="text-lg font-semibold text-gray-800 mb-4">Departamentos Cadastrados</h4>
                    <div id="departmentsList" class="space-y-3">
                        <!-- Departments will be rendered here -->
                    </div>
                    <div id="emptyStateDept" class="text-center py-8 text-gray-500">
                        Nenhum departamento cadastrado ainda.
                    </div>
                </div>
            </div>

            <!-- Triages Tab -->
            <div id="triagesTab" class="bg-white rounded-lg shadow-lg p-6 md:p-8 hidden">
                <div class="flex justify-between items-center mb-6">
                    <h3 class="text-xl font-bold text-gray-800">Gestão de Triagens</h3>
                    <button onclick="loadTriages()" class="px-4 py-2 bg-blue-600 text-white rounded-lg font-medium hover:bg-blue-700 transition">
                        Atualizar
                    </button>
                </div>

                <!-- Filter -->
                <!-- <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Filtrar por Status</label>
                    <select id="triageFilter" onchange="filterTriages()" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        <option value="all">Todas</option>
                        <option value="pendente">Pendentes</option>
                        <option value="atendida">Atendidas</option>
                    </select>
                </div> -->

                <!-- Triages List -->
                <div id="triagesList" class="space-y-4">
                    <!-- Triages will be rendered here -->
                </div>
                <div id="emptyStateTriage" class="text-center py-8 text-gray-500">
                    Nenhuma triagem encontrada.
                </div>
            </div>

            <!-- Edit Triage Modal -->
            <div id="editTriageModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 px-4">
                <div class="bg-white rounded-lg shadow-xl p-6 md:p-8 w-full max-w-2xl fade-in max-h-[90vh] overflow-y-auto">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-2xl font-bold text-gray-800">Editar Triagem</h3>
                        <button onclick="closeEditTriageModal()" class="text-gray-400 hover:text-gray-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    <form onsubmit="updateTriage(event)">
                        <input type="hidden" id="editTriageId">

                        <div class="space-y-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Sintomas do Paciente</label>
                                <textarea id="editTriageSymptoms" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50" rows="4"></textarea>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Departamento Recomendado *</label>
                                <select id="editTriageDepartment" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                    <option value="">Selecione um departamento</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-2">Data</label>
                                <input type="text" id="editTriageDate" readonly class="w-full px-4 py-2 border border-gray-300 rounded-lg bg-gray-50">
                            </div>

                            <div class="flex space-x-3 pt-4">
                                <button type="submit" class="flex-1 bg-blue-600 text-white py-2 rounded-lg font-semibold hover:bg-blue-700 transition">
                                    Salvar Alterações
                                </button>
                                <button type="button" onclick="closeEditTriageModal()" class="flex-1 bg-gray-300 text-gray-700 py-2 rounded-lg font-semibold hover:bg-gray-400 transition">
                                    Cancelar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Mock data storage (replace with actual backend calls)
        const API_URL = 'http://localhost:8000';
        let departments = [];
        let triages = [];
        let isAuthenticated = false;
        let currentEditingTriageId = null;

        // Load initial data
        function loadDepartments() {
            const saved = localStorage.getItem('departments');
            if (saved) {
                departments = JSON.parse(saved);
            }
            renderDepartments();
            updateStatistics();
        }

        function loadTriages() {
            const saved = localStorage.getItem('triages');

            (async function() {
                const response = await fetch(`${API_URL}/triagem/listar`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                triages = await response.json();
                console.log(triages)
                // if (saved) {
                //     triages = JSON.parse(saved);
                // }
                renderTriages();
                updateStatistics();
            })()
        }

        // Save to localStorage (simulate backend)
        function saveDepartmentsToStorage() {
            localStorage.setItem('departments', JSON.stringify(departments));
        }

        function saveTriagesToStorage() {
            localStorage.setItem('triages', JSON.stringify(triages));
        }

        // Update statistics
        function updateStatistics() {
            document.getElementById('totalDepartments').textContent = departments.length;
            document.getElementById('totalTriages').textContent = triages.length;
            const pending = triages.filter(t => t.status === 'pendente').length;
            document.getElementById('pendingTriages').textContent = pending;
        }

        // Tab navigation
        function showTab(tab) {
            const deptTab = document.getElementById('departmentsTab');
            const triageTab = document.getElementById('triagesTab');
            const btnDept = document.getElementById('tabDepartments');
            const btnTriage = document.getElementById('tabTriages');

            if (tab === 'departments') {
                deptTab.classList.remove('hidden');
                triageTab.classList.add('hidden');
                btnDept.classList.add('bg-blue-600', 'text-white');
                btnDept.classList.remove('bg-gray-200', 'text-gray-700');
                btnTriage.classList.add('bg-gray-200', 'text-gray-700');
                btnTriage.classList.remove('bg-blue-600', 'text-white');
            } else {
                deptTab.classList.add('hidden');
                triageTab.classList.remove('hidden');
                btnTriage.classList.add('bg-blue-600', 'text-white');
                btnTriage.classList.remove('bg-gray-200', 'text-gray-700');
                btnDept.classList.add('bg-gray-200', 'text-gray-700');
                btnDept.classList.remove('bg-blue-600', 'text-white');
                loadTriages();
            }
        }

        // Toggle admin login modal
        function toggleAdminLogin() {
            const modal = document.getElementById('adminLoginModal');
            modal.classList.toggle('hidden');
            document.getElementById('loginError').classList.add('hidden');
        }

        window.addEventListener('load', () => {
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('admin')) {
                isAuthenticated = true;
                showAdminView();
            }
        })
        // Handle admin login
        function handleAdminLogin(e) {
            e.preventDefault();
            const username = document.getElementById('adminUsername').value;
            const password = document.getElementById('adminPassword').value;

            // Mock authentication (replace with actual backend call)
            if (username === 'admin' && password === 'admin123') {
                const urlParams = new URLSearchParams(window.location.search);
                urlParams.set('admin', 'true');
                window.location.search = urlParams.toString();

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
            loadTriages();
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
            const emptyState = document.getElementById('emptyStateDept');

            (async function() {
                const response = await fetch(`${API_URL}/departamentos`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                departments = await response.json();
                console.log(departments);
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
                                <!--
                                <button onclick="editDepartment(${dept.id})" class="text-blue-600 hover:text-blue-800">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                </button>
                                <button onclick="deleteDepartment(${dept.id})" class="text-red-600 hover:text-red-800">
                                    <svg class="w-5
                                    h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                    </svg>
                                </button>
                                -->
                            </div>
                        </div>
                    </div>
                `).join('');
            })()
        }

        // Render triages list
        function renderTriages(filter = 'all') {
            const list = document.getElementById('triagesList');
            const emptyState = document.getElementById('emptyStateTriage');

            if (triages.length === 0) {
                list.innerHTML = '';
                emptyState.classList.remove('hidden');
                return;
            }

            emptyState.classList.add('hidden');
            list.innerHTML = triages.map(triage => {
                const statusColor = 'bg-green-100 text-green-800';

                const dept = departments.find(d => d.id === triage.departamentoId);
                const deptName = dept ? dept.nome : 'Não atribuído';

                return `
                    <div class="bg-white border border-gray-200 rounded-lg p-4 hover:shadow-md transition">
                        <div class="flex justify-between items-start mb-3">
                            <div class="flex items-center space-x-3">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold ${statusColor}">
                                    ${triage.categoria.toUpperCase()}
                                </span>
                                <span class="text-sm text-gray-500">${triage.created_at}</span>
                            </div>
                            <button onclick="openEditTriageModal(${triage.id})" class="text-blue-600 hover:text-blue-800">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="space-y-2 flex gap-4">
                            <div class="w-1/2">
                                <div>
                                    <p class="text-xs font-medium text-gray-500">SINTOMAS</p>
                                    <p class="text-sm text-gray-700">${triage.sintomas}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">DEPARTAMENTO</p>
                                    <p class="text-sm font-semibold text-gray-800">${triage.departamento}</p>
                                </div>
                                <div>
                                    <p class="text-xs font-medium text-gray-500">GRAVIDADE</p>
                                    <p class="text-sm text-gray-700">${triage.gravidade}</p>
                                </div>
                            </div>
                            <div class="w-1/2">
                                    <div>
                                        <p class="text-xs font-medium text-gray-500">PACIENTE</p>

                                        <div class="flex items-center gap-16">
                                            <p class="text-xs font-medium text-gray-500">NOME:</p>
                                            <p class="text-sm text-gray-700">${triage.paciente.name}</p>
                                        </div>
                                        <div class="flex items-center gap-16">
                                            <p class="text-xs font-medium text-gray-500">EMAIL:</p>
                                            <p class="text-sm text-gray-700">${triage.paciente.email}</p>
                                        </div>
                                    </div>
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        // Filter triages
        function filterTriages() {
            const filter = document.getElementById('triageFilter').value;
            renderTriages(filter);
        }

        // Open edit triage modal
        function openEditTriageModal(triageId) {
            const triage = triages.find(t => t.id === triageId);
            if (!triage) return;

            currentEditingTriageId = triageId;
            document.getElementById('editTriageId').value = triage.id;
            document.getElementById('editTriageSymptoms').value = triage.sintomas;
            document.getElementById('editTriageDate').value = triage.created_at;

            // Populate department select
            (async function() {
                const response = await fetch(`${API_URL}/departamentos`, {
                    method: 'GET',
                    headers: {
                        'Content-Type': 'application/json',
                    }
                });

                departments = await response.json();
                console.log(departments);

                const deptSelect = document.getElementById('editTriageDepartment');
                deptSelect.innerHTML = '<option value="">Selecione um departamento</option>' +
                    departments.map(d => `<option value="${d.nome}" ${d.id === triage.departamentoId ? 'selected' : ''}>${d.nome}</option>`).join('');

                document.getElementById('editTriageModal').classList.remove('hidden');
            })();

        }

        // Close edit triage modal
        function closeEditTriageModal() {
            document.getElementById('editTriageModal').classList.add('hidden');
            currentEditingTriageId = null;
        }

        // Update triage
        function updateTriage(e) {
            e.preventDefault();

            const triageId = parseInt(document.getElementById('editTriageId').value);
            // const departamentoId = parseInt(document.getElementById('editTriageDepartment').value);
            const selectedDepto = document.getElementById('editTriageDepartment').value;
            // const status = document.getElementById('editTriageStatus').value;

            console.log(selectedDepto);
            console.log(triageId);
            (async function() {
                const response = await fetch(`${API_URL}/triagem/${triageId}/departamento`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        departamento: selectedDepto,
                    })
                });

                const updated = await response.json();

                console.log(updated);
            })();
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
                departments[index] = {
                    id: parseInt(id),
                    nome,
                    descricao
                };
            } else {
                // Create new
                const newId = departments.length > 0 ? Math.max(...departments.map(d => d.id)) + 1 : 1;
                departments.push({
                    id: newId,
                    nome,
                    descricao
                });
            }

            saveDepartmentsToStorage();
            renderDepartments();
            updateStatistics();
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

            window.scrollTo({
                top: 0,
                behavior: 'smooth'
            });
        }

        // Delete department
        function deleteDepartment(id) {
            if (confirm('Tem certeza que deseja excluir este departamento?')) {
                departments = departments.filter(d => d.id !== id);
                saveDepartmentsToStorage();
                renderDepartments();
                updateStatistics();
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
            const name = document.getElementById('user-name').value.trim();
            const email = document.getElementById('user-email').value.trim();

            errorDiv.classList.add('hidden');

            if (!name || name.length < 3 || !email || email.length < 5 || !email.includes("@")) {
                errorDiv.textContent = 'Por favor, insira nome e email validos!';
                errorDiv.classList.remove('hidden');
                return;
            }

            if (!symptoms || symptoms.length < 10) {
                errorDiv.textContent = 'Por favor, descreva seus sintomas com mais detalhes (mínimo 10 caracteres)';
                errorDiv.classList.remove('hidden');
                return;
            }

            // Show loading
            document.getElementById('loadingSpinner').classList.remove('hidden');

            try {
                // Register user
                const registerUserResponse = await fetch(`${API_URL}/pacientes/registar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        name: name,
                        email: email
                    })
                });

                if (!registerUserResponse.ok) {
                    throw new Error(`Erro na API: ${response}`);
                }

                // Reister the triage
                const response = await fetch(`${API_URL}/gemini/interpretar`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        texto: symptoms
                    })
                });

                if (!response.ok) {
                    throw new Error(`Erro na API: ${response}`);
                }

                const data = await response.json();

                // Salvar triagem no localStorage
                // const newTriageId = triages.length > 0 ? Math.max(...triages.map(t => t.id)) + 1 : 1;
                // const newTriage = {
                //     id: newTriageId,
                //     sintomas: symptoms,
                //     departamentoId: data.departamento_id || null,
                //     status: 'pendente',
                //     data: new Date().toLocaleString('pt-BR')
                // };
                // triages.push(newTriage);
                // saveTriagesToStorage();

                // Adapte conforme a resposta da sua API
                // Exemplo: se a API retorna { departamento: "Cardiologia", descricao: "..." }
                const department = {
                    categoria: data.categoria || 'Departamento Geral',
                    descricao: data.mensagem || 'Recomendação baseada nos sintomas informados.'
                };
                console.log(data);

                displayResult(department);

            } catch (error) {
                console.error('Erro ao analisar sintomas:', error);
                document.getElementById('loadingSpinner').classList.add('hidden');
                errorDiv.textContent = 'Erro ao conectar com o servidor. Tente novamente.';
                errorDiv.classList.remove('hidden');
            }
        }

        // Display result
        function displayResult(department) {
            document.getElementById('loadingSpinner').classList.add('hidden');

            const resultDiv = document.getElementById('departmentResult');
            resultDiv.innerHTML = `
                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded">
                    <p class="font-semibold text-lg text-blue-800">${department.categoria}</p>
                    <p class="text-gray-700 mt-2">${department.descricao}</p>
                </div>
            `;

            document.getElementById('resultCard').classList.remove('hidden');
            document.getElementById('symptomsInput').disabled = true;

            // Scroll suave até o resultado
            setTimeout(() => {
                document.getElementById('resultCard').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }, 100);
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
