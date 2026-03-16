// Todo App JavaScript
document.addEventListener('DOMContentLoaded', function() {
    // DOM Elements
    const todoInput = document.getElementById('todo-input');
    const addBtn = document.getElementById('add-btn');
    const todoList = document.getElementById('todo-list');
    const filterBtns = document.querySelectorAll('.filter-btn');
    const clearCompletedBtn = document.getElementById('clear-completed');
    const clearAllBtn = document.getElementById('clear-all');
    const totalCountEl = document.getElementById('total-count');
    const completedCountEl = document.getElementById('completed-count');
    const remainingCountEl = document.getElementById('remaining-count');
    const themeBtns = document.querySelectorAll('.theme-btn');
    
    // State
    let todos = JSON.parse(localStorage.getItem('todos')) || [];
    let currentFilter = 'all';
    
    // Initialize the app
    initApp();
    
    // Event Listeners
    addBtn.addEventListener('click', addTodo);
    todoInput.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') addTodo();
    });
    
    clearCompletedBtn.addEventListener('click', clearCompleted);
    clearAllBtn.addEventListener('click', clearAll);
    
    filterBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Update active filter button
            filterBtns.forEach(b => b.classList.remove('active'));
            this.classList.add('active');
            
            // Set current filter and render
            currentFilter = this.dataset.filter;
            renderTodos();
        });
    });
    
    themeBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all theme buttons
            themeBtns.forEach(b => b.classList.remove('active'));
            // Add active class to clicked button
            this.classList.add('active');
            
            // Change theme
            const theme = this.dataset.theme;
            changeTheme(theme);
        });
    });
    
    // Functions
    function initApp() {
        // Set default theme
        themeBtns[0].classList.add('active');
        
        // Render initial todos
        renderTodos();
        
        // Update stats
        updateStats();
    }
    
    function addTodo() {
        const text = todoInput.value.trim();
        
        if (text === '') {
            showNotification('Please enter a task', 'error');
            todoInput.focus();
            return;
        }
        
        // Create new todo object
        const newTodo = {
            id: Date.now(),
            text: text,
            completed: false,
            createdAt: new Date().toISOString()
        };
        
        // Add to todos array
        todos.push(newTodo);
        
        // Save to localStorage
        saveTodos();
        
        // Clear input
        todoInput.value = '';
        todoInput.focus();
        
        // Render todos
        renderTodos();
        
        // Update stats
        updateStats();
        
        // Show success notification
        showNotification('Task added successfully!', 'success');
    }
    
    function toggleTodo(id) {
        // Find todo by id and toggle completed status
        todos = todos.map(todo => {
            if (todo.id === id) {
                return { ...todo, completed: !todo.completed };
            }
            return todo;
        });
        
        // Save to localStorage
        saveTodos();
        
        // Render todos
        renderTodos();
        
        // Update stats
        updateStats();
    }
    
    function deleteTodo(id) {
        // Filter out the todo with the given id
        todos = todos.filter(todo => todo.id !== id);
        
        // Save to localStorage
        saveTodos();
        
        // Render todos
        renderTodos();
        
        // Update stats
        updateStats();
        
        // Show notification
        showNotification('Task deleted', 'info');
    }
    
    function editTodo(id, newText) {
        if (newText.trim() === '') {
            deleteTodo(id);
            return;
        }
        
        // Find todo by id and update text
        todos = todos.map(todo => {
            if (todo.id === id) {
                return { ...todo, text: newText.trim() };
            }
            return todo;
        });
        
        // Save to localStorage
        saveTodos();
        
        // Render todos
        renderTodos();
        
        // Show notification
        showNotification('Task updated', 'info');
    }
    
    function clearCompleted() {
        // Keep only active todos
        const completedCount = todos.filter(todo => todo.completed).length;
        
        if (completedCount === 0) {
            showNotification('No completed tasks to clear', 'info');
            return;
        }
        
        todos = todos.filter(todo => !todo.completed);
        
        // Save to localStorage
        saveTodos();
        
        // Render todos
        renderTodos();
        
        // Update stats
        updateStats();
        
        // Show notification
        showNotification(`Cleared ${completedCount} completed task(s)`, 'success');
    }
    
    function clearAll() {
        if (todos.length === 0) {
            showNotification('No tasks to clear', 'info');
            return;
        }
        
        if (!confirm('Are you sure you want to delete all tasks?')) {
            return;
        }
        
        // Clear all todos
        todos = [];
        
        // Save to localStorage
        saveTodos();
        
        // Render todos
        renderTodos();
        
        // Update stats
        updateStats();
        
        // Show notification
        showNotification('All tasks cleared', 'success');
    }
    
    function renderTodos() {
        // Clear the todo list
        todoList.innerHTML = '';
        
        // Filter todos based on current filter
        let filteredTodos = [];
        
        switch (currentFilter) {
            case 'active':
                filteredTodos = todos.filter(todo => !todo.completed);
                break;
            case 'completed':
                filteredTodos = todos.filter(todo => todo.completed);
                break;
            default: // 'all'
                filteredTodos = [...todos];
        }
        
        // If no todos after filtering, show empty state
        if (filteredTodos.length === 0) {
            const emptyState = document.createElement('div');
            emptyState.className = 'empty-state';
            emptyState.innerHTML = `
                <i class="fas fa-clipboard-list"></i>
                <h3>No ${currentFilter !== 'all' ? currentFilter : ''} tasks</h3>
                <p>${getEmptyStateMessage()}</p>
            `;
            todoList.appendChild(emptyState);
            return;
        }
        
        // Render each todo
        filteredTodos.forEach(todo => {
            const todoItem = createTodoElement(todo);
            todoList.appendChild(todoItem);
        });
    }
    
    function createTodoElement(todo) {
        const todoItem = document.createElement('div');
        todoItem.className = 'todo-item';
        todoItem.dataset.id = todo.id;
        
        const checkbox = document.createElement('div');
        checkbox.className = `todo-checkbox ${todo.completed ? 'checked' : ''}`;
        checkbox.addEventListener('click', () => toggleTodo(todo.id));
        
        const todoText = document.createElement('div');
        todoText.className = `todo-text ${todo.completed ? 'completed' : ''}`;
        todoText.textContent = todo.text;
        
        // Enable double-click to edit
        todoText.addEventListener('dblclick', function() {
            const currentText = this.textContent;
            const input = document.createElement('input');
            input.type = 'text';
            input.value = currentText;
            input.className = 'edit-input';
            
            // Replace text with input
            this.replaceWith(input);
            input.focus();
            input.select();
            
            // Handle edit completion
            input.addEventListener('blur', function() {
                editTodo(todo.id, this.value);
            });
            
            input.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    this.blur();
                }
            });
        });
        
        const todoActions = document.createElement('div');
        todoActions.className = 'todo-actions';
        
        const editBtn = document.createElement('button');
        editBtn.className = 'todo-action-btn';
        editBtn.innerHTML = '<i class="fas fa-edit"></i>';
        editBtn.title = 'Edit task';
        editBtn.addEventListener('click', () => {
            todoText.dispatchEvent(new Event('dblclick'));
        });
        
        const deleteBtn = document.createElement('button');
        deleteBtn.className = 'todo-action-btn delete';
        deleteBtn.innerHTML = '<i class="fas fa-trash"></i>';
        deleteBtn.title = 'Delete task';
        deleteBtn.addEventListener('click', () => deleteTodo(todo.id));
        
        todoActions.appendChild(editBtn);
        todoActions.appendChild(deleteBtn);
        
        todoItem.appendChild(checkbox);
        todoItem.appendChild(todoText);
        todoItem.appendChild(todoActions);
        
        return todoItem;
    }
    
    function getEmptyStateMessage() {
        switch (currentFilter) {
            case 'active':
                return 'All tasks are completed!';
            case 'completed':
                return 'No completed tasks yet';
            default:
                return 'Add your first task using the input above';
        }
    }
    
    function updateStats() {
        const total = todos.length;
        const completed = todos.filter(todo => todo.completed).length;
        const remaining = total - completed;
        
        totalCountEl.textContent = total;
        completedCountEl.textContent = completed;
        remainingCountEl.textContent = remaining;
    }
    
    function saveTodos() {
        localStorage.setItem('todos', JSON.stringify(todos));
    }
    
    function changeTheme(theme) {
        const root = document.documentElement;
        
        // Remove existing theme class
        document.body.classList.remove('theme-default', 'theme-green', 'theme-purple', 'theme-orange');
        
        // Add new theme class
        document.body.classList.add(`theme-${theme}`);
        
        // Update CSS variables based on theme
        let primaryColor, primaryDark, accentColor;
        
        switch (theme) {
            case 'green':
                primaryColor = '#2ecc71';
                primaryDark = '#27ae60';
                accentColor = '#ffd166';
                break;
            case 'purple':
                primaryColor = '#9b59b6';
                primaryDark = '#8e44ad';
                accentColor = '#ffd166';
                break;
            case 'orange':
                primaryColor = '#e67e22';
                primaryDark = '#d35400';
                accentColor = '#ffd166';
                break;
            default: // default (blue)
                primaryColor = '#4a6bff';
                primaryDark = '#3a56e0';
                accentColor = '#ffd166';
        }
        
        // Update CSS variables
        root.style.setProperty('--primary-color', primaryColor);
        root.style.setProperty('--primary-dark', primaryDark);
        root.style.setProperty('--accent-color', accentColor);
        
        // Update elements that use primary color
        document.querySelectorAll('.filter-btn.active, .add-btn, .stat-value').forEach(el => {
            el.style.backgroundColor = primaryColor;
            el.style.backgroundImage = `linear-gradient(135deg, ${primaryColor} 0%, ${primaryDark} 100%)`;
        });
        
        // Save theme preference
        localStorage.setItem('todo-theme', theme);
        
        showNotification(`Theme changed to ${theme}`, 'info');
    }
    
    function showNotification(message, type) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = `notification notification-${type}`;
        notification.textContent = message;
        
        // Add to body
        document.body.appendChild(notification);
        
        // Remove after 3 seconds
        setTimeout(() => {
            notification.classList.add('fade-out');
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.parentNode.removeChild(notification);
                }
            }, 300);
        }, 3000);
        
        // Add some basic notification styles if not already present
        if (!document.querySelector('#notification-styles')) {
            const style = document.createElement('style');
            style.id = 'notification-styles';
            style.textContent = `
                .notification {
                    position: fixed;
                    top: 20px;
                    right: 20px;
                    padding: 15px 20px;
                    border-radius: 10px;
                    color: white;
                    font-weight: 500;
                    z-index: 1000;
                    box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
                    animation: slideIn 0.3s ease;
                }
                
                @keyframes slideIn {
                    from { transform: translateX(100%); opacity: 0; }
                    to { transform: translateX(0); opacity: 1; }
                }
                
                .notification-success {
                    background: linear-gradient(135deg, #2ecc71 0%, #27ae60 100%);
                }
                
                .notification-error {
                    background: linear-gradient(135deg, #ff6b6b 0%, #ff4757 100%);
                }
                
                .notification-info {
                    background: linear-gradient(135deg, #4a6bff 0%, #3a56e0 100%);
                }
                
                .fade-out {
                    animation: fadeOut 0.3s ease forwards;
                }
                
                @keyframes fadeOut {
                    from { opacity: 1; }
                    to { opacity: 0; transform: translateY(-10px); }
                }
            `;
            document.head.appendChild(style);
        }
    }
    
    // Load saved theme preference
    const savedTheme = localStorage.getItem('todo-theme') || 'default';
    const savedThemeBtn = document.querySelector(`.theme-btn[data-theme="${savedTheme}"]`);
    if (savedThemeBtn) {
        savedThemeBtn.click();
    }
});
