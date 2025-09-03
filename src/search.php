<?php
// search.php
include "db.php";
session_start();

$s_id   = $_SESSION["user_id"]   ?? "";
$s_name = $_SESSION["user_name"] ?? "";

$search_term = $_GET['search'] ?? '';
$category    = $_GET['catgo'] ?? 'board_title';

// $allowed_categories = ['board_title', 'board_content'];
// if (!in_array($category, $allowed_categories)) {
//     $category = 'board_title';
// }

$results = [];
if (!empty($search_term)) {

    // $like_term = "%" . $search_term . "%";
    
    
    // $sql = "SELECT board_id, board_title, board_date, user_name 
    //         FROM free_board 
    //         JOIN user ON free_board.user_id = user.user_id
    //         WHERE {$category} LIKE ? 
    //         ORDER BY board_id DESC";
    
    // $stmt = $db_conn->prepare($sql);
    // $stmt->bind_param("s", $like_term);
    // $stmt->execute();
    // $result_set = $stmt->get_result();
    
    // while ($row = $result_set->fetch_assoc()) {
    //     $results[] = $row;
        try {
 //   $escaped_search_term = $db_conn->real_escape_string($search_term);

    $sql = "SELECT board_id, board_title, board_date, user_id, board_locked
            FROM free_board
            WHERE `{$category}` LIKE '%{$search_term}%'
            ORDER BY board_id DESC";

    $result_set = $db_conn->query($sql);
    if ($result_set) {
        while ($row = $result_set->fetch_assoc()) {
    $uid = (int)$row['user_id'];
    $user_sql = "SELECT user_name FROM user WHERE user_id = $uid LIMIT 1";
    $user_res = $db_conn->query($user_sql);
    $user_name = "";
    if ($user_res && $user_res->num_rows > 0) {
        $user_row = $user_res->fetch_assoc();
        $user_name = $user_row['user_name'];
    }
    $row['user_name'] = $user_name;
    $results[] = $row;
}
    }

    } catch (mysqli_sql_exception $e) {
     }
}
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <title>hackingcamp Platform</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="style.css"/>
    <style>
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.7);
            backdrop-filter: blur(5px);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal-overlay.show {
            display: flex;
        }
        .modal-content {
            background: var(--panel);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            padding: clamp(24px, 3.5vw, 40px);
            box-shadow: var(--shadow);
            width: 90%;
            max-width: 500px;
            text-align: center;
        }
        .modal-content h1 { font-size: 1.5rem; }
        .modal-content p { color: var(--muted); }
        .modal-form { margin-top: 2rem; }
        #modal-error-message {
            color: var(--danger);
            font-weight: 600;
            margin-top: 1rem;
            min-height: 1.2em;
        }
         .pinned-post td {
            color: #e5355eff;
            font-weight: bold;
        }
        .pinned-post td a {
            color: #e5355eff;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <!--
    $sql = "SELECT board_id, board_title, board_date, user_id
            FROM free_board
            WHERE {$category} LIKE '%{$search_term}%'
            ORDER BY board_id DESC";
-->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <a class="navbar-brand" href="index.php">hackingcamp Platform</a>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="./board/index.php">board</a></li>
            <?php if(!$s_id){ ?>
                <li class="nav-item"><a class="nav-link" href="./login/login.php">login</a></li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="./login/logout.php">logout</a>
                </li>
            <?php } ?>
            <li class="nav-item"><a class="nav-link" href="./report.php">report</a></li>
            <li class="nav-item"><span class="navbar-text text-light ml-2"><?= htmlspecialchars($s_name) ?></span></li>
        </ul>
    </div>
</nav>

<div class="container mt-5">
    <h1>Search Results</h1>

    <div id="search_box" class="mb-4">
        <form action="search.php" method="get">
            <select name="catgo">
                <option value="board_title" <?= $category === 'board_title' ? 'selected' : '' ?>>Title</option>
                <option value="board_content" <?= $category === 'board_content' ? 'selected' : '' ?>>Content</option>
            </select>
            <input type="text" name="search" size="40" value="<?= htmlspecialchars($search_term) ?>" required="required" />
            <button>Search</button>
        </form>
    </div>

    <?php if (!empty($search_term)): ?>
        <?php if (count($results) > 0): ?>
            <p class="search-summary">
                Search results for <span>"<?= htmlspecialchars($search_term) ?>"</span> (Total: <?= count($results) ?>)
            </p>
            <ul class="search-results-list">
                <?php foreach ($results as $row): ?>
                    <li>
                        <?php
                            $is_locked = (bool)$row['board_locked'];
                            $link_href = $is_locked ? "#" : "./board/view.php?id=" . $row['board_id'];
                            $link_class = $is_locked ? "locked-post-link" : "";
                            $data_attributes = $is_locked ? "data-id='" . $row['board_id'] . "'" : "";
                        ?>
                        <a href="<?= $link_href ?>" class="<?= $link_class ?>" <?= $data_attributes ?>>
                            <h5><?= htmlspecialchars($row['board_title']) ?></h5>
                        </a>
                        <small>
                            Writer: <?= htmlspecialchars($row['user_name']) ?> | Date: <?= htmlspecialchars($row['board_date']) ?>
                        </small>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php else: ?>
            <div class="not-found-container">
                <h4>404 Not Found</h4>
                <p>No search results found for the requested term: <span>"<?= $search_term ?>"</span></p>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <p>Please enter a search term.</p>
    <?php endif; ?>
    <div class="modal-overlay" id="password-modal">
        <div class="modal-content">
            <h1>Password Required</h1>
            <p>This is a secret post. Please enter the password to continue.</p>
            <form id="password-form" class="modal-form">
                <input type="hidden" name="id" id="modal-board-id">
                <div class="form-group">
                    <label for="modal-secret-pw">PASSWORD</label>
                    <input type="password" id="modal-secret-pw" name="secret_pw" class="form-control" required autofocus>
                </div>
                <div id="modal-error-message"></div>
                <div class="button-group">
                    <button type="button" class="btn" id="modal-cancel-btn">Cancel</button>
                    <button type="submit" class="btn">Unlock</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const modal = document.getElementById('password-modal');
        const modalForm = document.getElementById('password-form');
        const modalBoardIdInput = document.getElementById('modal-board-id');
        const modalPasswordInput = document.getElementById('modal-secret-pw');
        const modalCancelBtn = document.getElementById('modal-cancel-btn');
        const modalErrorMessage = document.getElementById('modal-error-message');

        document.querySelectorAll('.locked-post-link').forEach(link => {
            link.addEventListener('click', (e) => {
                e.preventDefault();
                const boardId = link.getAttribute('data-id');
                modalBoardIdInput.value = boardId;
                modal.classList.add('show');
                modalPasswordInput.focus();
            });
        });

        const closeModal = () => {
            modal.classList.remove('show');
            modalForm.reset();
            modalErrorMessage.textContent = '';
        };
        modalCancelBtn.addEventListener('click', closeModal);
        modal.addEventListener('click', (e) => {
            if (e.target === modal) {
                closeModal();
            }
        });

        modalForm.addEventListener('submit', (e) => {
            e.preventDefault();
            const formData = new FormData(modalForm);
            formData.append('action', 'view');

            fetch('./board/check_pw_ok.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    window.location.href =`./board/view.php?id=${modalBoardIdInput.value}`;
                } else {
                    modalErrorMessage.textContent = data.message || 'An error occurred.';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalErrorMessage.textContent = 'Could not connect to the server.';
            });
        });
    });
</script>
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>