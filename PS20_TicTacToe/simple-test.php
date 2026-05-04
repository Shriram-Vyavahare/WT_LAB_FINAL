<?php
session_start();

// Initialize simple board
if (!isset($_SESSION['board'])) {
    $_SESSION['board'] = ['', '', '', '', '', '', '', '', ''];
    $_SESSION['player'] = 'X';
}

// Handle moves
if (isset($_POST['cell'])) {
    $cell = intval($_POST['cell']);
    if ($_SESSION['board'][$cell] === '') {
        $_SESSION['board'][$cell] = $_SESSION['player'];
        $_SESSION['player'] = $_SESSION['player'] === 'X' ? 'O' : 'X';
    }
}

// Handle reset
if (isset($_POST['reset'])) {
    $_SESSION['board'] = ['', '', '', '', '', '', '', '', ''];
    $_SESSION['player'] = 'X';
}
?>

<!DOCTYPE html>
<html>
<head>
    <title>Simple Test</title>
    <style>
        .board { display: grid; grid-template-columns: repeat(3, 100px); gap: 5px; }
        .cell { width: 100px; height: 100px; border: 1px solid #000; display: flex; align-items: center; justify-content: center; font-size: 24px; }
        button { width: 100%; height: 100%; font-size: 24px; }
    </style>
</head>
<body>
    <h1>Simple Tic-Tac-Toe Test</h1>
    <p>Current Player: <?php echo $_SESSION['player']; ?></p>
    
    <div class="board">
        <?php for ($i = 0; $i < 9; $i++): ?>
            <div class="cell">
                <?php if ($_SESSION['board'][$i] === ''): ?>
                    <form method="POST" style="width: 100%; height: 100%;">
                        <button type="submit" name="cell" value="<?php echo $i; ?>">Click</button>
                    </form>
                <?php else: ?>
                    <?php echo $_SESSION['board'][$i]; ?>
                <?php endif; ?>
            </div>
        <?php endfor; ?>
    </div>
    
    <form method="POST">
        <button type="submit" name="reset">Reset</button>
    </form>
    
    <h3>Debug Info:</h3>
    <pre><?php print_r($_POST); ?></pre>
    <pre><?php print_r($_SESSION); ?></pre>
</body>
</html>