<?php
require_once 'db.php';
$leaderboard = new Leaderboard();
?>
<!DOCTYPE html>
<html lang="en-us">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chrono Trials — Leaderboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700;900&family=Rajdhani:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --gold: #c9a84c;
            --gold-bright: #f0c84a;
            --gold-dim: #7a6330;
            --dark: #080c10;
            --darker: #040608;
            --surface: #0d1117;
            --surface2: #131920;
            --line: rgba(201,168,76,0.2);
            --line-strong: rgba(201,168,76,0.4);
            --text: #e8dfc8;
            --text-dim: #7a7060;
            --danger: #c94c4c;
            --danger-dim: rgba(201,76,76,0.15);
        }

        html, body {
            min-height: 100%;
            background: var(--darker);
            color: var(--text);
            font-family: 'Rajdhani', sans-serif;
        }

        body {
            display: flex;
            flex-direction: column;
            align-items: center;
            background-image:
                    repeating-linear-gradient(0deg, transparent, transparent 59px, rgba(201,168,76,0.04) 60px),
                    repeating-linear-gradient(90deg, transparent, transparent 59px, rgba(201,168,76,0.04) 60px);
            background-size: 60px 60px;
        }

        header {
            width: 100%;
            padding: 2rem 2rem 1rem;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .back-link {
            align-self: flex-start;
            font-family: 'Orbitron', monospace;
            font-size: 10px;
            letter-spacing: 0.2em;
            color: var(--text-dim);
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
            margin-bottom: 1.5rem;
            transition: color 0.2s;
            text-transform: uppercase;
        }
        .back-link:hover { color: var(--gold); }
        .back-link svg { width: 12px; height: 12px; }

        .eyebrow {
            font-family: 'Orbitron', monospace;
            font-size: 10px;
            letter-spacing: 0.4em;
            color: var(--gold-dim);
            text-transform: uppercase;
            margin-bottom: 0.6rem;
        }

        h1 {
            font-family: 'Orbitron', monospace;
            font-size: clamp(1.8rem, 4vw, 3rem);
            font-weight: 900;
            color: var(--gold-bright);
            letter-spacing: 0.08em;
            text-shadow: 0 0 40px rgba(240,200,74,0.3), 0 0 80px rgba(240,200,74,0.1);
            line-height: 1;
        }

        .title-line {
            width: 120px;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--gold), transparent);
            margin: 1rem auto;
        }

        main {
            width: 100%;
            max-width: 860px;
            padding: 0 1.5rem 4rem;
            display: flex;
            flex-direction: column;
            gap: 2rem;
        }

        /* ── Add entry form ── */
        .form-card {
            border: 1px solid var(--line);
            background: var(--surface);
            padding: 1.5rem;
            position: relative;
        }

        .form-card-title {
            font-family: 'Orbitron', monospace;
            font-size: 11px;
            letter-spacing: 0.25em;
            color: var(--gold-dim);
            text-transform: uppercase;
            margin-bottom: 1.25rem;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .form-card-title::after {
            content: '';
            flex: 1;
            height: 1px;
            background: var(--line);
        }

        .form-row {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr auto;
            gap: 0.75rem;
            align-items: end;
        }

        @media (max-width: 600px) {
            .form-row { grid-template-columns: 1fr 1fr; }
            .form-row .btn-submit { grid-column: 1 / -1; }
        }

        .field {
            display: flex;
            flex-direction: column;
            gap: 0.4rem;
        }

        label {
            font-size: 10px;
            letter-spacing: 0.25em;
            color: var(--text-dim);
            text-transform: uppercase;
        }

        input {
            background: var(--surface2);
            border: 1px solid var(--line);
            color: var(--text);
            font-family: 'Rajdhani', sans-serif;
            font-size: 15px;
            padding: 0.55rem 0.75rem;
            outline: none;
            transition: border-color 0.2s;
            width: 100%;
        }

        input:focus { border-color: var(--gold); }
        input::placeholder { color: var(--text-dim); opacity: 0.6; }

        input[type="time"] { font-family: 'Orbitron', monospace; font-size: 13px; }
        input[type="date"] { font-family: 'Rajdhani', sans-serif; color-scheme: dark; }

        .btn-submit {
            font-family: 'Orbitron', monospace;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            padding: 0.6rem 1.5rem;
            border: 1px solid var(--gold);
            background: var(--gold);
            color: var(--dark);
            cursor: pointer;
            clip-path: polygon(6px 0%, 100% 0%, calc(100% - 6px) 100%, 0% 100%);
            transition: all 0.2s;
            white-space: nowrap;
        }
        .btn-submit:hover { background: var(--gold-bright); border-color: var(--gold-bright); }

        .msg {
            font-size: 13px;
            padding: 0.5rem 0.75rem;
            margin-top: 0.75rem;
            display: none;
        }
        .msg.success { background: rgba(201,168,76,0.1); border: 1px solid var(--line); color: var(--gold); display: block; }
        .msg.error { background: var(--danger-dim); border: 1px solid rgba(201,76,76,0.4); color: #f08080; display: block; }

        /* ── Leaderboard table ── */
        .lb-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 1rem;
            flex-wrap: wrap;
        }

        .lb-title {
            font-family: 'Orbitron', monospace;
            font-size: 11px;
            letter-spacing: 0.25em;
            color: var(--gold-dim);
            text-transform: uppercase;
        }

        .search-wrap {
            position: relative;
        }

        .search-wrap svg {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            width: 13px;
            height: 13px;
            color: var(--text-dim);
        }

        #search {
            padding-left: 2rem;
            font-size: 13px;
            width: 200px;
        }

        .table-wrap {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 15px;
        }

        thead tr {
            background: var(--surface2);
            border-bottom: 1px solid var(--line-strong);
        }

        th {
            font-family: 'Orbitron', monospace;
            font-size: 9px;
            letter-spacing: 0.25em;
            color: var(--text-dim);
            text-transform: uppercase;
            padding: 0.75rem 1rem;
            text-align: left;
            white-space: nowrap;
            cursor: pointer;
            user-select: none;
        }

        th:hover { color: var(--gold); }
        th .sort-icon { margin-left: 4px; opacity: 0.5; font-size: 10px; }
        th.active { color: var(--gold); }
        th.active .sort-icon { opacity: 1; }

        tbody tr {
            border-bottom: 1px solid rgba(201,168,76,0.08);
            transition: background 0.15s;
        }

        tbody tr:hover { background: rgba(201,168,76,0.04); }

        td {
            padding: 0.75rem 1rem;
            vertical-align: middle;
        }

        .rank {
            font-family: 'Orbitron', monospace;
            font-size: 13px;
            color: var(--text-dim);
            min-width: 36px;
        }

        .rank-1 { color: #f0c84a; }
        .rank-2 { color: #b8c8d8; }
        .rank-3 { color: #c0844a; }

        .medal {
            display: inline-block;
            font-size: 16px;
            line-height: 1;
        }

        .username {
            font-weight: 600;
            font-size: 16px;
            letter-spacing: 0.03em;
        }

        .time-cell {
            font-family: 'Orbitron', monospace;
            font-size: 14px;
            color: var(--gold);
            letter-spacing: 0.05em;
        }

        .date-cell {
            font-size: 13px;
            color: var(--text-dim);
        }

        .action-btns {
            display: flex;
            gap: 0.5rem;
        }

        .btn-icon {
            background: none;
            border: 1px solid var(--line);
            color: var(--text-dim);
            cursor: pointer;
            padding: 4px 8px;
            font-size: 12px;
            transition: all 0.2s;
            display: flex;
            align-items: center;
        }

        .btn-icon:hover { border-color: var(--gold); color: var(--gold); }
        .btn-icon.delete:hover { border-color: var(--danger); color: var(--danger); background: var(--danger-dim); }

        .btn-icon svg { width: 13px; height: 13px; }

        .empty-state {
            text-align: center;
            padding: 3rem 1rem;
            color: var(--text-dim);
            font-size: 14px;
            letter-spacing: 0.1em;
            border: 1px dashed var(--line);
        }

        .empty-state p:first-child {
            font-family: 'Orbitron', monospace;
            font-size: 12px;
            color: var(--gold-dim);
            margin-bottom: 0.5rem;
        }

        .count-badge {
            font-family: 'Orbitron', monospace;
            font-size: 10px;
            background: rgba(201,168,76,0.12);
            color: var(--gold-dim);
            border: 1px solid var(--line);
            padding: 2px 8px;
            letter-spacing: 0.1em;
        }

        /* ── Edit modal ── */
        .modal-bg {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(4,6,8,0.85);
            z-index: 100;
            align-items: center;
            justify-content: center;
        }
        .modal-bg.open { display: flex; }

        .modal {
            background: var(--surface);
            border: 1px solid var(--line-strong);
            padding: 2rem;
            width: min(480px, 92vw);
        }

        .modal-title {
            font-family: 'Orbitron', monospace;
            font-size: 13px;
            letter-spacing: 0.2em;
            color: var(--gold);
            margin-bottom: 1.5rem;
            text-transform: uppercase;
        }

        .modal-fields { display: flex; flex-direction: column; gap: 1rem; }

        .modal-actions {
            display: flex;
            gap: 0.75rem;
            margin-top: 1.5rem;
            justify-content: flex-end;
        }

        .btn-cancel {
            font-family: 'Orbitron', monospace;
            font-size: 10px;
            letter-spacing: 0.2em;
            text-transform: uppercase;
            padding: 0.6rem 1.25rem;
            border: 1px solid var(--line);
            background: none;
            color: var(--text-dim);
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-cancel:hover { border-color: var(--text-dim); color: var(--text); }

        footer {
            margin-top: auto;
            padding: 1.5rem;
            font-size: 11px;
            letter-spacing: 0.15em;
            color: var(--text-dim);
            text-align: center;
            text-transform: uppercase;
            border-top: 1px solid var(--line);
            width: 100%;
        }
    </style>
</head>
<body>

<header>
    <a href="index.php" class="back-link">
        <svg viewBox="0 0 12 12" fill="none" stroke="currentColor" stroke-width="1.5">
            <path d="M8 1L3 6l5 5"/>
        </svg>
        Back to game
    </a>
    <p class="eyebrow">Chrono Trials</p>
    <h1>Leaderboard</h1>
    <div class="title-line"></div>
</header>

<main>

    <!-- Add entry -->
    <div class="form-card">
        <p class="form-card-title">Submit your time</p>
        <div class="form-row">
            <div class="field">
                <label for="inp-name">Username</label>
                <input type="text" id="inp-name" placeholder="YourName" maxlength="50">
            </div>
            <div class="field">
                <label for="inp-time">Time (mm:ss)</label>
                <input type="text" id="inp-time" placeholder="01:23" maxlength="8" pattern="\d{1,2}:\d{2}(:\d{2})?">
            </div>
            <div class="field">
                <label for="inp-date">Date</label>
                <input type="date" id="inp-date">
            </div>
            <button class="btn-submit" onclick="addEntry()">Add</button>
        </div>
        <div class="msg" id="form-msg"></div>
    </div>

    <!-- Leaderboard -->
    <div>
        <div class="lb-header" style="margin-bottom:1rem;">
            <div style="display:flex;align-items:center;gap:0.75rem;">
                <span class="lb-title">Rankings</span>
                <span class="count-badge" id="entry-count">0 entries</span>
            </div>
            <div class="search-wrap">
                <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                    <circle cx="6.5" cy="6.5" r="4.5"/><path d="M10.5 10.5l3.5 3.5"/>
                </svg>
                <input type="text" id="search" placeholder="Search player..." oninput="renderTable()">
            </div>
        </div>

        <div class="table-wrap">
            <table id="lb-table">
                <thead>
                <tr>
                    <th onclick="sortBy('rank')">#<span class="sort-icon">↕</span></th>
                    <th onclick="sortBy('username')">Player<span class="sort-icon">↕</span></th>
                    <th onclick="sortBy('time')" class="active">Time<span class="sort-icon" id="sort-icon-time">↑</span></th>
                    <th onclick="sortBy('date')">Date<span class="sort-icon">↕</span></th>
                    <th></th>
                </tr>
                </thead>
                <tbody id="lb-body"></tbody>
            </table>
            <div id="empty-state" class="empty-state" style="display:none;">
                <p>No times yet</p>
                <p>Be the first to submit a score above</p>
            </div>
        </div>
    </div>

</main>

<!-- Edit modal -->
<div class="modal-bg" id="modal">
    <div class="modal">
        <p class="modal-title">Edit entry</p>
        <div class="modal-fields">
            <div class="field">
                <label for="edit-name">Username</label>
                <input type="text" id="edit-name" maxlength="50">
            </div>
            <div class="field">
                <label for="edit-time">Time (mm:ss)</label>
                <input type="text" id="edit-time" maxlength="8">
            </div>
            <div class="field">
                <label for="edit-date">Date</label>
                <input type="date" id="edit-date">
            </div>
        </div>
        <div class="modal-actions">
            <button class="btn-cancel" onclick="closeModal()">Cancel</button>
            <button class="btn-submit" onclick="saveEdit()">Save</button>
        </div>
    </div>
</div>

<footer>
    Chrono Trials &nbsp;
</footer>

<script>
    let entries = [];
    let sortField = 'time';
    let sortDir = 'asc';
    let editId = null;

    function loadFromDB() {
        fetch('api.php?action=getAll')
            .then(res => res.json())
            .then(data => {
                entries = data;
                renderTable();
            })
            .catch(err => console.error('Load error:', err));
    }

    function saveToDB(entry, action, callback) {
        fetch('api.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify({ action: action, entry: entry })
        })
            .then(res => res.json())
            .then(data => {
                if (data.success) {
                    if (callback) callback();
                    loadFromDB();
                } else {
                    showMsg(data.error || 'Operation failed', 'error');
                }
            })
            .catch(err => {
                console.error('Save error:', err);
                showMsg('Server error', 'error');
            });
    }

    function addEntry() {
        const name = document.getElementById('inp-name').value.trim();
        const time = document.getElementById('inp-time').value.trim();
        const date = document.getElementById('inp-date').value;

        if (!name) return showMsg('Enter a username.', 'error');
        if (!time || !/^\d{1,2}:\d{2}(:\d{2})?$/.test(time)) return showMsg('Enter time as mm:ss or hh:mm:ss.', 'error');
        if (!date) return showMsg('Select a date.', 'error');

        saveToDB({ username: name, time: time, date: date }, 'add', () => {
            showMsg('Time submitted! Good run.', 'success');
            document.getElementById('inp-name').value = '';
            document.getElementById('inp-time').value = '';
            document.getElementById('inp-date').valueAsDate = new Date();
        });
    }

    function deleteEntry(id) {
        if (!confirm('Remove this entry?')) return;
        saveToDB({ id: id }, 'delete');
    }

    function openEdit(id) {
        const e = entries.find(x => x.id == id);
        if (!e) return;
        editId = id;
        document.getElementById('edit-name').value = e.username;
        document.getElementById('edit-time').value = e.time;
        document.getElementById('edit-date').value = e.date;
        document.getElementById('modal').classList.add('open');
    }

    function closeModal() {
        document.getElementById('modal').classList.remove('open');
        editId = null;
    }

    function saveEdit() {
        const name = document.getElementById('edit-name').value.trim();
        const time = document.getElementById('edit-time').value.trim();
        const date = document.getElementById('edit-date').value;
        if (!name || !time || !date) return;
        saveToDB({ id: editId, username: name, time: time, date: date }, 'update', () => {
            closeModal();
        });
    }

    function showMsg(text, type) {
        const msg = document.getElementById('form-msg');
        msg.textContent = text;
        msg.className = 'msg ' + type;
        setTimeout(() => { msg.className = 'msg'; }, 4000);
    }

    function parseTime(str) {
        const parts = str.split(':').map(Number);
        if (parts.length === 2) return parts[0] * 60 + parts[1];
        if (parts.length === 3) return parts[0] * 3600 + parts[1] * 60 + parts[2];
        return Infinity;
    }

    function sortBy(field) {
        if (sortField === field) { sortDir = sortDir === 'asc' ? 'desc' : 'asc'; }
        else { sortField = field; sortDir = 'asc'; }
        renderTable();
    }

    function renderTable() {
        const query = document.getElementById('search').value.toLowerCase();
        let filtered = entries.filter(e => e.username.toLowerCase().includes(query));

        filtered.sort((a, b) => {
            let va, vb;
            if (sortField === 'time') { va = parseTime(a.time); vb = parseTime(b.time); }
            else if (sortField === 'date') { va = a.date; vb = b.date; }
            else { va = a.username.toLowerCase(); vb = b.username.toLowerCase(); }
            return sortDir === 'asc' ? (va > vb ? 1 : -1) : (va < vb ? 1 : -1);
        });

        const tbody = document.getElementById('lb-body');
        const empty = document.getElementById('empty-state');

        document.getElementById('entry-count').textContent = entries.length + ' ' + (entries.length === 1 ? 'entry' : 'entries');

        if (filtered.length === 0) {
            tbody.innerHTML = '';
            empty.style.display = 'block';
            return;
        }
        empty.style.display = 'none';

        const medals = ['🥇','🥈','🥉'];

        tbody.innerHTML = filtered.map((e, i) => {
            const rankClass = i < 3 ? `rank-${i+1}` : '';
            const medal = i < 3 ? `<span class="medal">${medals[i]}</span>` : '';
            return `<tr>
        <td><span class="rank ${rankClass}">${medal || '#' + (i+1)}</span></td>
        <td><span class="username">${escHtml(e.username)}</span></td>
        <td><span class="time-cell">${escHtml(e.time)}</span></td>
        <td><span class="date-cell">${formatDate(e.date)}</span></td>
        <td>
          <div class="action-btns">
            <button class="btn-icon" title="Edit" onclick="openEdit(${e.id})">
              <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M11 2l3 3-9 9H2v-3l9-9z"/>
              </svg>
            </button>
            <button class="btn-icon delete" title="Delete" onclick="deleteEntry(${e.id})">
              <svg viewBox="0 0 16 16" fill="none" stroke="currentColor" stroke-width="1.5">
                <path d="M2 4h12M5 4V2h6v2M6 7v5M10 7v5M3 4l1 10h8l1-10"/>
              </svg>
            </button>
          </div>
        </td>
      </tr>`;
        }).join('');

        document.querySelectorAll('th').forEach(th => th.classList.remove('active'));
    }

    function escHtml(str) {
        return str.replace(/[&<>"']/g, c => ({'&':'&amp;','<':'&lt;','>':'&gt;','"':'&quot;',"'":'&#39;'}[c]));
    }

    function formatDate(str) {
        if (!str) return '';
        const [y, m, d] = str.split('-');
        return `${d}-${m}-${y}`;
    }

    document.getElementById('modal').addEventListener('click', function(e) {
        if (e.target === this) closeModal();
    });

    document.getElementById('inp-date').valueAsDate = new Date();
    loadFromDB();
</script>
</body>
</html>