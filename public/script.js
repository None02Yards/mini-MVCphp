const expressionInput = document.getElementById('expression');
const resultDiv = document.getElementById('result');
const historyPanel = document.getElementById('history-panel');
const historyList = document.getElementById('history-list');
const confirmOverlay = document.getElementById('confirm-overlay');

let history = [];
let angleMode = 'DEG'; // Default mode

// --------------------------
// Expression handling
// --------------------------
function appendExpression(value) {
    expressionInput.value += value;
}

function resetCalculator() {
    expressionInput.value = '';
    resultDiv.textContent = '';
}

// --------------------------
// DEG/RAD toggle
// --------------------------
function toggleAngleMode() {
    angleMode = angleMode === 'DEG' ? 'RAD' : 'DEG';
    const btn = document.getElementById('angle-toggle');
    if (btn) btn.textContent = angleMode;
}

// --------------------------
// Calculator history
// --------------------------
function toggleHistory() {
    historyPanel.classList.toggle('hidden');
}

function addToHistory(expression, result) {
    const item = { expression, result };
    history.unshift(item);

    if (history.length > 10) history.pop();
    renderHistory();
}

function renderHistory() {
    historyList.innerHTML = '';
    history.forEach(entry => {
        const li = document.createElement('li');
        li.textContent = `${entry.expression} = ${entry.result}`;
        li.onclick = () => {
            expressionInput.value = entry.expression;
            resultDiv.textContent = 'Result: ' + entry.result;
        };
        historyList.appendChild(li);
    });
}

// --------------------------
// Clear history confirmation
// --------------------------
function clearHistory() {
    confirmOverlay.classList.remove('hidden');
}

function closeConfirm() {
    confirmOverlay.classList.add('hidden');
}

function confirmClearHistory() {
    history = [];
    renderHistory();
    closeConfirm();
}

// --------------------------
// Calculate function
// --------------------------
function calculate() {
    const expression = expressionInput.value.trim();
    if (!expression) {
        resultDiv.textContent = 'Error: Expression is empty';
        return;
    }

    resultDiv.textContent = 'Calculating...';

    fetch('/calculate', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: `expression=${encodeURIComponent(expression)}&angleMode=${angleMode}`
    })
    .then(res => res.json())
    .then(data => {
        if (!data.success) {
            resultDiv.textContent = 'Error: ' + data.error;
            return;
        }
        resultDiv.textContent = 'Result: ' + data.result;
        addToHistory(expression, data.result);
    })
    .catch(() => {
        resultDiv.textContent = 'Server error';
    });
}

// --------------------------
// Keyboard support
// --------------------------
document.addEventListener('keydown', function(e) {
    const allowedKeys = '0123456789+-*/().';

    if (allowedKeys.includes(e.key)) appendExpression(e.key);

    if (e.key === 'Enter') {
        e.preventDefault();
        calculate();
    }

    if (e.key === 'Backspace') {
        expressionInput.value = expressionInput.value.slice(0, -1);
    }

    if (e.key === 'Escape') {
        resetCalculator();
    }
});
