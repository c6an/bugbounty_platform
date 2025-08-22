<?php
include "../db.php"; 
session_start();

$s_id   = $_SESSION["user_id"]   ?? "";
$s_name = $_SESSION["user_name"] ?? "";


$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$limit = 10; 
$offset = ($page - 1) * $limit;

$sql_count = "SELECT COUNT(*) AS cnt FROM free_board";
$result_count = $db_conn->query($sql_count);
$total_count = $result_count->fetch_assoc()['cnt'];
$total_pages = ceil($total_count / $limit);


$sql = "
    SELECT 
        f.board_id, f.board_title, f.board_date, f.board_views,
        f.board_locked, u.user_name
    FROM free_board f
    JOIN user u ON f.user_id = u.user_id
    ORDER BY f.board_id DESC
    LIMIT ? OFFSET ?
";
$stmt = $db_conn->prepare($sql);
$stmt->bind_param("ii", $limit, $offset);
$stmt->execute();
$result = $stmt->get_result();
?>

<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>hackingcamp Platform</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"/>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css"/>
    <link rel="stylesheet" href="./board_style.css" />
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
    </style>

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="../index.php">hackingcamp Platform</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ml-auto">
            <li class="nav-item"><a class="nav-link" href="../index.php">Home</a></li>
            <?php if(!$s_id){ ?>
                <li class="nav-item"><a class="nav-link" href="../login/login.php">login</a></li>
            <?php } else { ?>
                <li class="nav-item">
                    <a class="nav-link" href="../login/logout.php">logout</a>
                </li>
            <?php } ?>
            <li class="nav-item"><a class="nav-link" href="/bugbounty/report.php">report</a></li>
            <li class="nav-item"><span class="navbar-text text-light ml-2"><?= htmlspecialchars($s_name) ?></span></li>
        </ul>
    </div>
    </nav>
    <div id="board_area">
        <h1>Free Board</h1>
        <p>reference:<a href="https://github.com/payloadbox/xss-payload-list">xss payload</a></p>
        <table>
            <thead>
                <tr>
                    <th>No</th>
                    <th>Title</th>
                    <th>Writer</th>
                    <th>Date</th>
                    <th>View</th>
                    <th>Locked</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $post_num = $total_count - $offset;
            while($row = $result->fetch_assoc()) { 
                $title = strlen($row['board_title']) > 30 ? mb_substr($row['board_title'], 0, 30, 'UTF-8') . "..." : $row['board_title'];
                $is_locked = (bool)$row['board_locked'];
                $link_href = $is_locked ? "#" : "view.php?id=" . $row['board_id'];
                $link_class = $is_locked ? "locked-post-link" : "";
                $data_attributes = $is_locked ? "data-id='" . $row['board_id'] . "'" : "";
            ?>
                <tr>
                    <td><?php echo $post_num; ?></td>
                    <td>
                        <a href="<?= $link_href ?>" class="<?= $link_class ?>" <?= $data_attributes ?>>
                            <?php if($is_locked) echo ""; ?>
                            <?php echo htmlspecialchars($title); ?>
                        </a>
                    </td>
                    <td><?php echo htmlspecialchars($row['user_name']); ?></td>
                    <td><?php echo $row['board_date']; ?></td>
                    <td><?php echo $row['board_views']; ?></td>
                    <td><?php echo $row['board_locked'] ? 'Yes' : 'No'; ?></td>
                </tr>
            <?php $post_num--; } ?>
            </tbody>
        </table>

        <div class="pagination">
        <?php
        for($i = 1; $i <= $total_pages; $i++) {
            if($i == $page) {
                echo "<strong>$i</strong> ";
            } else {
                echo "<a href='index.php?page=$i'>$i</a> ";
            }
        }
        ?>
        </div>
        <div class="button-group">
            <a href="write.php" class="btn">Write</a>
        </div>
    </div>
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

                fetch('check_pw_ok.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = `view.php?id=${modalBoardIdInput.value}`;
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
</body>
</html>
