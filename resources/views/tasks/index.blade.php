<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>

        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    </head>
    <body class="bg-light">
        <div class="d-flex flex-column" style="min-height: 100vh;">
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="container-fluid">
                    <a class="navbar-brand" href="{{ route('dashboard') }}">{{ config('app.name', 'Laravel') }}</a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="navbarNav">
                        <ul class="navbar-nav me-auto">
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('dashboard') ? 'active' : '' }}" href="{{ route('dashboard') }}">{{ __('Dashboard') }}</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ request()->routeIs('tasks.*') ? 'active' : '' }}" href="{{ route('tasks.index') }}">{{ __('Tasks') }}</a>
                            </li>
                        </ul>

                        <ul class="navbar-nav">
                            <li class="nav-item dropdown">
                                <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    {{ Auth::user()->name }}
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                    <li><a class="dropdown-item" href="{{ route('profile.edit') }}">{{ __('Profile') }}</a></li>
                                    <li><hr class="dropdown-divider"></li>
                                    <li>
                                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                            @csrf
                                            <button type="submit" class="dropdown-item">{{ __('Log Out') }}</button>
                                        </form>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </nav>

            <header class="bg-white border-bottom">
                <div class="container-fluid py-4 px-4">
                    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                        {{ auth()->user()->isAdmin() ? __('All Tasks') : __('My Tasks') }}
                    </h2>
                </div>
            </header>

            <main class="flex-grow-1">
                <div class="py-4">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-12">
                                <div class="card">
                                    <div class="card-body">
                                        <div class="p-6 text-gray-900">
                                            <div id="alert-container"></div>

                                            <a href="{{ route('tasks.create') }}" class="btn btn-primary mb-3">Add New Task</a>

                                            <form id="filter-form" class="mb-4 row g-3">
                                                <div class="col-md-4">
                                                    <label for="status" class="form-label">Status</label>
                                                    <select name="status" id="status" class="form-select">
                                                        <option value="">All</option>
                                                        <option value="pending">Pending</option>
                                                        <option value="in-progress">In Progress</option>
                                                        <option value="completed">Completed</option>
                                                    </select>
                                                </div>
                                                <div class="col-md-4">
                                                    <label for="due_date" class="form-label">Due Date</label>
                                                    <input type="date" id="due_date" name="due_date" class="form-control">
                                                </div>
                                                <div class="col-md-4 d-flex align-items-end gap-2">
                                                    <button type="submit" class="btn btn-secondary">Filter</button>
                                                    <button type="button" id="reset-filters" class="btn btn-outline-secondary">Reset</button>
                                                </div>
                                            </form>

                                            <div class="table-responsive">
                                                <table class="table table-striped">
                                                    <thead>
                                                        <tr>
                                                            @if(auth()->user()->isAdmin())
                                                                <th>Owner</th>
                                                            @endif
                                                            <th>Title</th>
                                                            <th>Status</th>
                                                            <th>Due Date</th>
                                                            <th>Actions</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody id="task-table-body">
                                                        <tr>
                                                            <td colspan="{{ auth()->user()->isAdmin() ? 5 : 4 }}" class="text-center text-muted py-4">
                                                                Loading tasks...
                                                            </td>
                                                        </tr>
                                                    </tbody>
                                                </table>
                                            </div>

                                            <div id="pagination" class="d-flex justify-content-center"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </main>
        </div>

        <script>
            const isAdmin = @json(auth()->user()->isAdmin());
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
            const taskTableBody = document.getElementById('task-table-body');
            const alertContainer = document.getElementById('alert-container');
            const filterForm = document.getElementById('filter-form');
            const resetFiltersButton = document.getElementById('reset-filters');
            const paginationContainer = document.getElementById('pagination');
            const statusInput = document.getElementById('status');
            const dueDateInput = document.getElementById('due_date');

            function escapeHtml(value) {
                return String(value ?? '')
                    .replace(/&/g, '&amp;')
                    .replace(/</g, '&lt;')
                    .replace(/>/g, '&gt;')
                    .replace(/"/g, '&quot;')
                    .replace(/'/g, '&#039;');
            }

            function showAlert(message, type = 'success') {
                alertContainer.innerHTML = `<div class="alert alert-${type}">${escapeHtml(message)}</div>`;
            }

            function currentQuery(page = 1) {
                const params = new URLSearchParams();

                if (statusInput.value) {
                    params.set('status', statusInput.value);
                }

                if (dueDateInput.value) {
                    params.set('due_date', dueDateInput.value);
                }

                params.set('page', page);

                return params;
            }

            function renderPagination(payload) {
                const currentPage = payload.current_page ?? 1;
                const lastPage = payload.last_page ?? 1;

                if (lastPage <= 1) {
                    paginationContainer.innerHTML = '';
                    return;
                }

                let html = '<nav><ul class="pagination">';

                html += `<li class="page-item ${currentPage === 1 ? 'disabled' : ''}"><button class="page-link" data-page="${currentPage - 1}" ${currentPage === 1 ? 'disabled' : ''}>Previous</button></li>`;

                for (let page = 1; page <= lastPage; page++) {
                    html += `<li class="page-item ${page === currentPage ? 'active' : ''}"><button class="page-link" data-page="${page}">${page}</button></li>`;
                }

                html += `<li class="page-item ${currentPage === lastPage ? 'disabled' : ''}"><button class="page-link" data-page="${currentPage + 1}" ${currentPage === lastPage ? 'disabled' : ''}>Next</button></li>`;
                html += '</ul></nav>';

                paginationContainer.innerHTML = html;
            }

            function renderTasks(tasks) {
                if (!tasks.length) {
                    taskTableBody.innerHTML = `<tr><td colspan="${isAdmin ? 5 : 4}" class="text-center text-muted py-4">No tasks found.</td></tr>`;
                    return;
                }

                taskTableBody.innerHTML = tasks.map((task) => {
                    const ownerColumn = isAdmin ? `<td>${escapeHtml(task.user?.name ?? 'Unknown')}</td>` : '';

                    return `
                        <tr>
                            ${ownerColumn}
                            <td>${escapeHtml(task.title)}</td>
                            <td>${escapeHtml(task.status)}</td>
                            <td>${escapeHtml(task.due_date)}</td>
                            <td>
                                <a href="/tasks/${task.id}/edit" class="btn btn-sm btn-warning me-2">Edit</a>
                                <button type="button" class="btn btn-sm btn-danger" data-delete-id="${task.id}">Delete</button>
                            </td>
                        </tr>
                    `;
                }).join('');
            }

            async function loadTasks(page = 1) {
                taskTableBody.innerHTML = `<tr><td colspan="${isAdmin ? 5 : 4}" class="text-center text-muted py-4">Loading tasks...</td></tr>`;

                const response = await fetch(`/api/tasks?${currentQuery(page).toString()}`, {
                    headers: {
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                if (!response.ok) {
                    taskTableBody.innerHTML = `<tr><td colspan="${isAdmin ? 5 : 4}" class="text-center text-danger py-4">Unable to load tasks.</td></tr>`;
                    return;
                }

                const payload = await response.json();
                renderTasks(payload.data ?? []);
                renderPagination(payload);
            }

            async function deleteTask(taskId) {
                if (!window.confirm('Are you sure you want to delete this task?')) {
                    return;
                }

                const response = await fetch(`/api/tasks/${taskId}`, {
                    method: 'DELETE',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': csrfToken,
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    credentials: 'same-origin'
                });

                const payload = await response.json().catch(() => ({}));

                if (!response.ok) {
                    showAlert(payload.message || 'Unable to delete task.', 'danger');
                    return;
                }

                showAlert(payload.message || 'Task deleted successfully.');
                loadTasks();
            }

            filterForm.addEventListener('submit', (event) => {
                event.preventDefault();
                loadTasks();
            });

            resetFiltersButton.addEventListener('click', () => {
                statusInput.value = '';
                dueDateInput.value = '';
                loadTasks();
            });

            paginationContainer.addEventListener('click', (event) => {
                if (event.target.matches('[data-page]')) {
                    loadTasks(Number(event.target.dataset.page));
                }
            });

            taskTableBody.addEventListener('click', (event) => {
                if (event.target.matches('[data-delete-id]')) {
                    deleteTask(event.target.dataset.deleteId);
                }
            });

            loadTasks();
        </script>
    </body>
</html>
